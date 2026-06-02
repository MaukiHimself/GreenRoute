(function () {
    const STORAGE_KEY = 'portal-sidebar-expanded';
    const sidebar = document.getElementById('portal-sidebar');
    const toggle = document.getElementById('portal-sidebar-toggle');

    if (!sidebar || !toggle) {
        return;
    }

    function setExpanded(expanded) {
        sidebar.classList.toggle('is-expanded', expanded);
        document.body.classList.toggle('portal-sidebar-expanded', expanded);
        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        const icon = toggle.querySelector('i');
        if (icon) {
            icon.className = expanded ? 'bi bi-chevron-left' : 'bi bi-list';
        }
        try {
            localStorage.setItem(STORAGE_KEY, expanded ? '1' : '0');
        } catch (e) {
            /* ignore */
        }
    }

    const saved = localStorage.getItem(STORAGE_KEY);
    setExpanded(saved === '1');

    toggle.addEventListener('click', function () {
        setExpanded(!sidebar.classList.contains('is-expanded'));
    });

    sidebar.querySelectorAll('.portal-sidebar__parent').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const group = btn.closest('.portal-sidebar__group');
            if (!group) {
                return;
            }

            if (!sidebar.classList.contains('is-expanded')) {
                setExpanded(true);
                group.classList.add('is-open');
                return;
            }

            group.classList.toggle('is-open');
        });
    });

    sidebar.querySelectorAll('.portal-sidebar__link[data-tab]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            if (!sidebar.classList.contains('is-expanded')) {
                setExpanded(true);
            }
        });
    });
})();
