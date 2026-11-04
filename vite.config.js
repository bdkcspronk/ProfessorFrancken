import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fontAwesomeSubset from './vite/fontawesome-subset.js';

export default defineConfig(({ command }) => ({
    // Relative urls in the files that are built, like the fonts in the stylesheets, instead of
    // the ASSET_URL of .env, so that they also load on a phone during development and
    // browsers don't block them as fonts from another website
    base: command === 'build' ? '/build/' : '',
    plugins: [
        laravel({
            input: [
                // The fonts and icons, the stylesheet of the website and the admin, and the one
                // to print pages of the admin
                'resources/assets/css/vendor.css',
                'resources/assets/sass/app.scss',
                'resources/assets/sass/print.scss',
                // The scripts of every page of the website and of the admin
                'resources/assets/js/site.js',
                'resources/assets/js/admin.js',
                // The scripts of single pages
                'resources/assets/js/BoardPhotos.js',
                'resources/assets/js/photos.js',
                'resources/assets/js/markdown-editor.js',
                'resources/assets/js/404.js',
            ],
            refresh: true,
        }),
        fontAwesomeSubset({
            // Icons that the sources don't name: those of the toolbar of the markdown editor
            // (EasyMDE). Icons that only come from the database, like icons of sectors that
            // were added later, can be added here as well
            extra: [
                'bold', 'italic', 'strikethrough', 'heading', 'header', 'code', 'quote-left', 'link',
                'image', 'table', 'list-ul', 'list-ol', 'eye', 'question-circle',
            ],
        }),
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
    // The development server runs in Docker (see the vite service of docker-compose.yaml)
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: { host: 'localhost' },
    },
}));
