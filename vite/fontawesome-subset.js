// A Vite plugin that builds Font Awesome with only the icons that the website uses: it finds
// the names of icons in the sources (classes like "fas fa-users", and names that are put
// together in Blade like "fa-{{ $icon }}", which are strings like 'users' in the code),
// keeps only the rules of those icons and cuts the fonts down to their glyphs. The result is
// written to resources/assets/fontawesome, which resources/assets/css/vendor.css imports
import fs from 'node:fs';
import path from 'node:path';
import subsetFont from 'subset-font';

const PACKAGE = 'node_modules/@fortawesome/fontawesome-free';
const OUTPUT = 'resources/assets/fontawesome';
const SOURCES = ['resources/views', 'resources/assets/js', 'src', 'config', 'database/migrations'];
const STYLES = [
    { css: 'solid.css', font: 'fa-solid-900' },
    { css: 'regular.css', font: 'fa-regular-400' },
    { css: 'brands.css', font: 'fa-brands-400' },
];

// The rule of an icon, like ".fa-users {\n  --fa: "\f0c0"; }"
const ICON_RULE = /\.fa-([a-z0-9-]+) \{\s*--fa: "\\([0-9a-f]+)"; \}\s*/g;

function files(directory) {
    return fs.readdirSync(directory, { withFileTypes: true }).flatMap((entry) => {
        const file = path.join(directory, entry.name);
        return entry.isDirectory() ? files(file) : [file];
    });
}

// Every "fa-name" in the sources, and every string of a single word that is the name of an icon
function usedNames(iconNames, extra) {
    const used = new Set(extra);
    for (const file of SOURCES.flatMap(files)) {
        if (!/\.(php|js)$/.test(file)) {
            continue;
        }
        const text = fs.readFileSync(file, 'utf8');
        for (const [, name] of text.matchAll(/\bfa-([a-z0-9]+(?:-[a-z0-9]+)*)/g)) {
            used.add(name);
        }
        for (const [, name] of text.matchAll(/['"]([a-z0-9]+(?:-[a-z0-9]+)*)['"]/g)) {
            if (iconNames.has(name)) {
                used.add(name);
            }
        }
    }
    return used;
}

// The icon rules of a stylesheet, and the stylesheet without the icons that are not used
function keepIcons(css, used, codepoints) {
    return css.replace(ICON_RULE, (rule, name, codepoint) => {
        if (!used.has(name)) {
            return '';
        }
        codepoints.add(parseInt(codepoint, 16));
        return rule;
    });
}

export default function fontAwesomeSubset({ extra = [] } = {}) {
    let generated = null;

    async function generate() {
        const read = (file) => fs.readFileSync(path.join(PACKAGE, 'css', file), 'utf8');
        const core = read('fontawesome.css');
        const sheets = STYLES.map((style) => ({ ...style, text: read(style.css) }));

        const iconNames = new Set();
        for (const text of [core, ...sheets.map((sheet) => sheet.text)]) {
            for (const [, name] of text.matchAll(ICON_RULE)) {
                iconNames.add(name);
            }
        }
        const used = usedNames(iconNames, extra);

        const codepoints = new Set();
        const brandCodepoints = new Set();
        let output = keepIcons(core, used, codepoints);

        fs.mkdirSync(OUTPUT, { recursive: true });
        for (const sheet of sheets) {
            const isBrands = sheet.font === 'fa-brands-400';
            const text = keepIcons(sheet.text, used, isBrands ? brandCodepoints : codepoints)
                // Only the woff2 font, which every browser that we support can use
                .replace(/src: url\("\.\.\/webfonts\/([a-z0-9-]+)\.woff2"\) format\("woff2"\)[^;]*;/, 'src: url("./$1.woff2") format("woff2");');
            output += '\n' + text;

            const glyphs = String.fromCodePoint(...(isBrands ? brandCodepoints : codepoints));
            const font = fs.readFileSync(path.join(PACKAGE, 'webfonts', sheet.font + '.woff2'));
            const subset = glyphs === '' ? font : await subsetFont(font, glyphs, { targetFormat: 'woff2' });
            fs.writeFileSync(path.join(OUTPUT, sheet.font + '.woff2'), subset);
        }

        fs.writeFileSync(path.join(OUTPUT, 'fontawesome.css'), output);

        const missing = [...used].filter((name) => !iconNames.has(name) && !/^(fw|lg|sm|xs|[0-9]?x|spin|pulse|border|pull-left|pull-right|stack|stack-1x|stack-2x|inverse|rotate-[0-9]+|flip-[a-z]+|ul|li|solid|regular|brands|beat|bounce|fade|flip|shake)$/.test(name));
        return { icons: used.size, missing };
    }

    return {
        name: 'fontawesome-subset',
        async buildStart() {
            generated ??= generate();
            const { missing } = await generated;
            if (missing.length > 0) {
                this.warn('Icons that Font Awesome does not have: ' + missing.join(', '));
            }
        },
    };
}
