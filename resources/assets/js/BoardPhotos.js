// The photos of the boards (see association/boards) fade in from top to bottom: a photo is
// only shown when it has loaded and the photos above it are shown, so that photos do not
// pop up all over the page in the order in which they happen to arrive. Photos that are
// above the screen do not hold up the others, as they may not have been loaded when the
// page was scrolled past them quickly, and neither do photos that can not be loaded
(function () {
    var container = document.querySelector('[data-board-photos]');
    if (!container) {
        return;
    }

    var photos = Array.prototype.slice.call(container.querySelectorAll('[data-board-photo]'));
    var scheduled = false;
    container.classList.add('is-revealing');

    function isAboveScreen(photo) {
        return photo.getBoundingClientRect().bottom < 0;
    }

    function reveal() {
        scheduled = false;
        var waiting = false;

        photos = photos.filter(function (photo) {
            if (photo.complete && !waiting) {
                photo.classList.add('is-shown');
                return false;
            }
            if (!photo.complete && !isAboveScreen(photo)) {
                waiting = true;
            }
            return true;
        });

        if (photos.length === 0) {
            window.removeEventListener('scroll', schedule);
        }
    }

    function schedule() {
        if (!scheduled) {
            scheduled = true;
            window.requestAnimationFrame(reveal);
        }
    }

    photos.forEach(function (photo) {
        photo.addEventListener('load', schedule);
        photo.addEventListener('error', schedule);
    });
    window.addEventListener('scroll', schedule, { passive: true });
    reveal();
})();
