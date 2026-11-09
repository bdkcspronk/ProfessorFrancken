// The tabs of the admin scroll sideways when they don't fit (see admin/components/navigation),
// so on load the open tabs are scrolled into view
(function () {
    document.querySelectorAll('.admin-tabs, .admin-subtabs__list').forEach(function (list) {
        var active = list.querySelector('.is-active');
        if (active && list.scrollWidth > list.clientWidth) {
            var listBox = list.getBoundingClientRect();
            var activeBox = active.getBoundingClientRect();
            list.scrollLeft += activeBox.left - listBox.left - (listBox.width - activeBox.width) / 2;
        }
    });
})();
