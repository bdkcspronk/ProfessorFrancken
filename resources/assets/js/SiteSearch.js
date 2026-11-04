// The search icon of the header: a first click opens the search field to its left, a click
// with something in the field searches. An empty field closes again when it loses the focus
// or with Escape. Without javascript the icon goes to the search page
(function () {
    var form = document.querySelector('[data-header-search]');
    if (!form) {
        return;
    }

    var input = form.querySelector('input');
    var button = form.querySelector('button');

    function open() {
        form.classList.add('is-open');
        input.removeAttribute('tabindex');
        button.setAttribute('aria-expanded', 'true');
        input.focus();
    }

    function close() {
        form.classList.remove('is-open');
        input.setAttribute('tabindex', '-1');
        button.setAttribute('aria-expanded', 'false');
    }

    form.addEventListener('submit', function (event) {
        if (!form.classList.contains('is-open')) {
            event.preventDefault();
            open();
        } else if (input.value.trim() === '') {
            event.preventDefault();
            input.focus();
        }
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            input.value = '';
            close();
            button.focus();
        }
    });

    // Closes when the focus leaves the search, unless something was typed
    form.addEventListener('focusout', function () {
        setTimeout(function () {
            if (!form.contains(document.activeElement) && input.value.trim() === '') {
                close();
            }
        }, 0);
    });
})();
