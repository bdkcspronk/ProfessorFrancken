// The menu with all pages (see layout/_header.blade.php): the Menu button slides in a panel
// with the sections of the menu and turns into a cross that closes it. On wide screens the
// pages of a section come out in a column next to the sections, like on the website of
// ASML; on smaller screens they slide over the sections, with a button to go back. Escape
// goes back a level or closes the menu. When it is used with a keyboard the focus moves
// along; after a click it stays put, as browsers would otherwise draw a focus ring around
// the item that got the focus
(function () {
    var menu = document.querySelector('[data-site-menu]');
    var toggle = document.querySelector('[data-site-menu-toggle]');
    if (!menu || !toggle) {
        return;
    }

    var levels = menu.querySelectorAll('[data-site-menu-level]');
    var main = menu.querySelector('[data-site-menu-level="main"]');
    var sections = menu.querySelectorAll('[data-site-menu-open]');
    var current = main;
    var openedFrom = null;
    var usingKeyboard = false;

    // The same width as the lg breakpoint of the stylesheet
    var columns = window.matchMedia('(min-width: 992px)');

    // The first link or button that is shown (the Back button is hidden in columns)
    function firstFocusable(level) {
        return Array.prototype.find.call(level.querySelectorAll('a, button'), function (element) {
            return element.offsetParent !== null;
        });
    }

    // A level that slides in is still outside the panel when it gets the focus; without
    // preventScroll the browser would scroll the panel sideways to show it
    function focus(element) {
        if (usingKeyboard) {
            element.focus({ preventScroll: true });
        }
    }

    // A click from the keyboard (Enter or space) has no mouse clicks, so its detail is 0
    function rememberInput(event) {
        usingKeyboard = event.detail === 0;
    }

    // Only the levels that are shown can be reached with the keyboard: the sections and the
    // pages of a section next to each other in columns, or only one of them otherwise
    function showLevel(level) {
        var name = level.getAttribute('data-site-menu-level');
        current = level;

        Array.prototype.forEach.call(levels, function (other) {
            var active = other === level || (other === main && columns.matches);
            other.classList.toggle('is-active', active);
            other.classList.toggle('is-behind', other === main && !active);
            if (active) {
                other.removeAttribute('inert');
            } else {
                other.setAttribute('inert', '');
            }
        });

        menu.classList.toggle('has-section', level !== main);
        Array.prototype.forEach.call(sections, function (section) {
            var isCurrent = section.getAttribute('data-site-menu-open') === name;
            section.classList.toggle('is-current', isCurrent);
            section.setAttribute('aria-expanded', isCurrent ? 'true' : 'false');
        });
    }

    // Switching between columns and sliding levels keeps the section that is open
    columns.addEventListener('change', function () {
        showLevel(current);
    });

    function open() {
        showLevel(main);
        menu.classList.add('is-open');
        document.documentElement.classList.add('site-menu-is-open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Close the menu');
        focus(firstFocusable(main));
    }

    function close() {
        menu.classList.remove('is-open');
        document.documentElement.classList.remove('site-menu-is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Menu');
        focus(toggle);
    }

    function back() {
        showLevel(main);
        focus(openedFrom || firstFocusable(main));
    }

    showLevel(main);

    toggle.addEventListener('click', function (event) {
        rememberInput(event);
        if (menu.classList.contains('is-open')) {
            close();
        } else {
            open();
        }
    });

    menu.addEventListener('click', function (event) {
        var target = event.target.closest('[data-site-menu-open], [data-site-menu-back], [data-site-menu-close]');
        if (!target) {
            return;
        }
        rememberInput(event);

        if (target.hasAttribute('data-site-menu-close')) {
            close();
        } else if (target.hasAttribute('data-site-menu-back')) {
            back();
        } else {
            var level = menu.querySelector('[data-site-menu-level="' + target.getAttribute('data-site-menu-open') + '"]');
            openedFrom = target;
            showLevel(level);
            focus(firstFocusable(level));
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape' || !menu.classList.contains('is-open')) {
            return;
        }
        usingKeyboard = true;

        if (current === main) {
            close();
        } else {
            back();
        }
    });
})();
