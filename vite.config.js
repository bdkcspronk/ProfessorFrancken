import fs from 'node:fs';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const stylesheets = {
    'resources/assets/sass/app.scss': '/dist/css/app.css',
    'resources/assets/sass/print.scss': '/dist/css/print.css',
};

// The pages of the admin still load the stylesheets with mix(), so after a build this
// writes the mix-manifest.json that mix() reads, pointing to the files that Vite built
function mixManifest() {
    return {
        name: 'mix-manifest',
        apply: 'build',
        closeBundle() {
            const manifest = JSON.parse(fs.readFileSync('public/build/manifest.json', 'utf8'));
            const mix = Object.fromEntries(Object.entries(stylesheets).map(
                ([entry, path]) => [path, '/build/' + manifest[entry].file]
            ));
            fs.writeFileSync('public/mix-manifest.json', JSON.stringify(mix, null, 4) + '\n');
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: Object.keys(stylesheets),
            // Not public/hot, as mix() would then load the stylesheets of the admin from
            // the development server
            hotFile: 'public/vite.hot',
            refresh: true,
        }),
        mixManifest(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // So that "node_modules/bootstrap/..." can be imported
                loadPaths: [import.meta.dirname],
                // Bootstrap 4 and our own stylesheets use @import and the global functions
                // of Sass, which still work but give a warning for every use
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'slash-div', 'if-function'],
            },
        },
    },
    // The development server runs in Docker (see the npm service of docker-compose.yaml)
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: { host: 'localhost' },
    },
});
