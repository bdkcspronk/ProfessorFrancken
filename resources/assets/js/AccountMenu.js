// The person in the header when logged in (see layout/_header.blade.php): a click opens the
// pages of the profile below it instead of going to the profile. A click elsewhere, Escape
// or opening the menu with all pages closes it again
(function () {
    var wrapper = document.querySelector('[data-account-menu]');
    if (!wrapper) {
        return;
    }

    var toggle = wrapper.querySelector('[data-account-menu-toggle]');
    var links = wrapper.querySelectorAll('.account-menu__link');

    function isOpen() {
        return wrapper.classList.contains('is-open');
    }

    function open(usingKeyboard) {
        wrapper.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        if (usingKeyboard) {
            links[0].focus();
        }
    }

    function close() {
        wrapper.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function (event) {
        event.preventDefault();
        if (isOpen()) {
            close();
            return;
        }

        // Only one of the two menus is open at a time
        var siteMenuToggle = document.querySelector('[data-site-menu-toggle][aria-expanded="true"]');
        if (siteMenuToggle) {
            siteMenuToggle.click();
        }

        // A click from the keyboard (Enter) has no mouse clicks, so its detail is 0
        open(event.detail === 0);
    });

    document.addEventListener('click', function (event) {
        if (isOpen() && !wrapper.contains(event.target)) {
            close();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && isOpen()) {
            close();
            toggle.focus();
        }
    });

    // Closes when the focus leaves it with the keyboard
    wrapper.addEventListener('focusout', function () {
        setTimeout(function () {
            if (isOpen() && !wrapper.contains(document.activeElement) && document.activeElement !== document.body) {
                close();
            }
        }, 0);
    });
})();
