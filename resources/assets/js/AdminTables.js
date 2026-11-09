// Tables that are wider than the screen of a phone scroll sideways instead of running off
// the screen, so every table of the admin gets the wrapper of Bootstrap that does that
(function () {
    document.querySelectorAll('.admin-content table').forEach(function (table) {
        if (table.closest('.table-responsive')) {
            return;
        }

        var wrapper = document.createElement('div');
        wrapper.className = 'table-responsive';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });
})();
