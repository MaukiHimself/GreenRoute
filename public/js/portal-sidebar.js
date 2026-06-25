(function () {
    const STORAGE_KEY = 'portal-sidebar-expanded';
    const sidebar = document.getElementById('portal-sidebar');
    const toggle = document.getElementById('portal-sidebar-toggle');
    const mobileToggle = document.getElementById('mobile-sidebar-toggle');
    const closeBtn = document.getElementById('portal-sidebar-close');
    const overlay = document.getElementById('portal-sidebar-overlay');

    if (!sidebar || !toggle) {
        return;
    }

    function isMobile() {
        return window.matchMedia('(max-width: 991.98px)').matches;
    }

    function setExpanded(expanded) {
        sidebar.classList.toggle('is-expanded', expanded);
        document.body.classList.toggle('portal-sidebar-expanded', expanded && !isMobile());

        if (isMobile()) {
            sidebar.classList.toggle('is-mobile-open', expanded);
        } else {
            sidebar.classList.remove('is-mobile-open');
        }

        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        const icon = toggle.querySelector('i');
        if (icon) {
            icon.className = expanded && !isMobile() ? 'bi bi-chevron-left' : 'bi bi-list';
        }

        if (overlay) {
            if (isMobile() && expanded) {
                overlay.classList.add('is-visible');
                document.body.style.overflow = 'hidden';
            } else {
                overlay.classList.remove('is-visible');
                document.body.style.overflow = '';
            }
        }

        try {
            if (!isMobile()) {
                localStorage.setItem(STORAGE_KEY, expanded ? '1' : '0');
            }
        } catch (e) {
            /* ignore */
        }
    }

    function closeSidebar() {
        setExpanded(false);
    }

    function openSidebar() {
        setExpanded(true);
    }

    const saved = localStorage.getItem(STORAGE_KEY);
    setExpanded(!isMobile() && saved === '1');

    toggle.addEventListener('click', function () {
        if (isMobile()) {
            if (sidebar.classList.contains('is-mobile-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
            return;
        }
        setExpanded(!sidebar.classList.contains('is-expanded'));
    });

    if (mobileToggle) {
        mobileToggle.addEventListener('click', function (e) {
            e.preventDefault();
            openSidebar();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    window.addEventListener('resize', function () {
        if (!isMobile()) {
            if (overlay) {
                overlay.classList.remove('is-visible');
            }
            document.body.style.overflow = '';
            sidebar.classList.remove('is-mobile-open');
            setExpanded(localStorage.getItem(STORAGE_KEY) === '1');
        } else {
            document.body.classList.remove('portal-sidebar-expanded');
            closeSidebar();
        }
    });

    sidebar.querySelectorAll('.portal-sidebar__parent').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const group = btn.closest('.portal-sidebar__group');
            if (!group) {
                return;
            }

            if (!sidebar.classList.contains('is-expanded') && !sidebar.classList.contains('is-mobile-open')) {
                openSidebar();
                group.classList.add('is-open');
                return;
            }

            group.classList.toggle('is-open');
        });
    });

    sidebar.querySelectorAll('.portal-sidebar__link[data-tab]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            // Only suppress navigation when this link points to the current URL
            // (i.e. it's meant to switch an in-page tab). Otherwise let the browser navigate.
            if (link.getAttribute('data-tab') === window.location.pathname) {
                e.preventDefault();
                if (!sidebar.classList.contains('is-expanded') && !sidebar.classList.contains('is-mobile-open')) {
                    openSidebar();
                }
            }
        });
    });

    sidebar.querySelectorAll('.portal-sidebar__link[href]').forEach(function (link) {
        const href = link.getAttribute('href');
        if (!href || href === '#') {
            return;
        }
        link.addEventListener('click', function () {
            if (isMobile()) {
                closeSidebar();
            }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isMobile() && sidebar.classList.contains('is-mobile-open')) {
            closeSidebar();
        }
    });
})();