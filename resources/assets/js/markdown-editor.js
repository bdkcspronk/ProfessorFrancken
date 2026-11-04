// The editor of the fields with Markdown in the admin (components/forms/markdown.blade.php):
// every textarea with data-markdown-editor gets a toolbar and a preview, and keeps a draft in
// the browser under the name in data-autosave-id
import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

document.querySelectorAll('textarea[data-markdown-editor]').forEach((element) => {
    new EasyMDE({
        element,
        autosave: {
            enabled: true,
            uniqueId: element.dataset.autosaveId,
        },
        // Our own Font Awesome has the icons of the toolbar (see vite.config.js), so EasyMDE
        // doesn't need to load Font Awesome from a CDN
        autoDownloadFontAwesome: false,
        spellChecker: false,
        promptURLs: true,
        toolbar: [
            'bold', 'italic', 'strikethrough',
            '|',
            'heading-1', 'heading-2', 'heading-3',
            '|',
            'code', 'quote', 'link', 'image', 'table',
            '|',
            'unordered-list', 'ordered-list',
            '|',
            'preview',
            'guide',
        ],
        status: false,
        maxHeight: '600px',
    });
});
