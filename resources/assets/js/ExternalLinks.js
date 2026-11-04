// Links to other websites open in a new tab, and say so when hovered. Links in text get
// an icon (.external-link), so that it is clear beforehand that they leave the website
(function () {
    // Links in these parts of the page, or around an image, only open in a new tab
    var WITHOUT_ICON = 'header, footer, .home-footer, .sponsors, nav';
    var TEXT = 'p, li, td, dd, address, h1, h2, h3, h4, h5, h6';

    function isExternal(link) {
        return /^https?:$/.test(link.protocol) && link.hostname !== window.location.hostname;
    }

    function start() {
        Array.prototype.forEach.call(document.querySelectorAll('a[href]'), function (link) {
            if (!isExternal(link)) {
                return;
            }

            link.target = '_blank';
            link.rel = (link.rel + ' noopener').trim();
            if (!link.title) {
                link.title = 'Opens ' + link.hostname.replace(/^www\./, '') + ' in a new tab';
            }

            var inText = link.closest(TEXT) !== null && link.closest(WITHOUT_ICON) === null;
            if (inText && link.querySelector('img, svg, i') === null) {
                link.classList.add('external-link');
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})();
