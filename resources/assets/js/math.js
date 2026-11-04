// Renders the LaTeX on a page with KaTeX. KaTeX and its fonts are only loaded when the text of
// the page contains one of the delimiters, which most pages don't
export function renderMath(delimiters) {
    const text = document.body.textContent;
    if (!delimiters.some(({ left }) => text.includes(left))) {
        return;
    }

    Promise.all([
        import('katex/contrib/auto-render'),
        import('katex/dist/katex.min.css'),
    ]).then(([{ default: renderMathInElement }]) => {
        renderMathInElement(document.body, { delimiters });
    });
}

// The delimiters of the website, which also allow inline maths between single dollar signs
export const SITE_DELIMITERS = [
    { left: '$$', right: '$$', display: true },
    { left: '$', right: '$', display: false },
    { left: '\\[', right: '\\]', display: true },
    { left: '\\(', right: '\\)', display: false },
];

// The delimiters of the admin, where a dollar sign is more likely to be just a dollar sign
export const ADMIN_DELIMITERS = [
    { left: '$$', right: '$$', display: true },
    { left: '\\[', right: '\\]', display: true },
    { left: '\\(', right: '\\)', display: false },
];
