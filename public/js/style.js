document.addEventListener('DOMContentLoaded', function () {
    /*
    |--------------------------------------------------------------------------
    | LUCIDE ICONS
    |--------------------------------------------------------------------------
    */
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    /*
    |--------------------------------------------------------------------------
    | SIDEBAR MOBILE
    |--------------------------------------------------------------------------
    | Mendukung beberapa kemungkinan ID tombol:
    | - menuToggle
    | - sidebarMobileToggle
    | - sidebarMobileBtn
    | - sidebarCollapseBtn
    */
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    const toggleButtons = [
        document.getElementById('menuToggle'),
        document.getElementById('sidebarMobileToggle'),
        document.getElementById('sidebarMobileBtn'),
        document.getElementById('sidebarCollapseBtn'),
        document.querySelector('.menu-toggle')
    ].filter(Boolean);

    function openSidebar() {
        if (!sidebar) return;

        sidebar.classList.add('active');

        if (overlay) {
            overlay.classList.add('active');
        }

        document.body.classList.add('no-scroll');
    }

    function closeSidebar() {
        if (!sidebar) return;

        sidebar.classList.remove('active');

        if (overlay) {
            overlay.classList.remove('active');
        }

        document.body.classList.remove('no-scroll');
    }

    function toggleSidebar() {
        if (!sidebar) return;

        if (sidebar.classList.contains('active')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            toggleSidebar();
        });
    });

    if (overlay) {
        overlay.addEventListener('click', function () {
            closeSidebar();
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | CLOSE SIDEBAR SAAT KLIK MENU DI MOBILE
    |--------------------------------------------------------------------------
    */
    const menuLinks = document.querySelectorAll('.sidebar .menu a');

    menuLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ACTIVE MENU OTOMATIS BERDASARKAN URL
    |--------------------------------------------------------------------------
    */
    const currentUrl = window.location.href;

    menuLinks.forEach(function (link) {
        if (!link.href) return;

        const linkUrl = link.href;

        if (currentUrl === linkUrl || currentUrl.startsWith(linkUrl + '/')) {
            link.classList.add('active');
        }
    });

    /*
    |--------------------------------------------------------------------------
    | TOOLTIP CURSOR
    |--------------------------------------------------------------------------
    | Pakai attribute:
    | data-tooltip="Teks tooltip"
    |
    | Optional:
    | data-tooltip-dir="top"
    | data-tooltip-dir="bottom"
    | data-tooltip-dir="left"
    | data-tooltip-dir="right"
    */
    const tooltipTargets = document.querySelectorAll('[data-tooltip]');

    let tooltip = document.querySelector('.tooltip-cursor');

    if (!tooltip && tooltipTargets.length > 0) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-cursor';
        document.body.appendChild(tooltip);
    }

    function moveTooltip(event, direction) {
        if (!tooltip) return;

        const offset = 14;
        const tooltipRect = tooltip.getBoundingClientRect();

        let left = event.clientX + offset;
        let top = event.clientY + offset;

        if (direction === 'top') {
            left = event.clientX - tooltipRect.width / 2;
            top = event.clientY - tooltipRect.height - offset;
        }

        if (direction === 'bottom') {
            left = event.clientX - tooltipRect.width / 2;
            top = event.clientY + offset;
        }

        if (direction === 'left') {
            left = event.clientX - tooltipRect.width - offset;
            top = event.clientY - tooltipRect.height / 2;
        }

        if (direction === 'right') {
            left = event.clientX + offset;
            top = event.clientY - tooltipRect.height / 2;
        }

        const maxLeft = window.innerWidth - tooltipRect.width - 8;
        const maxTop = window.innerHeight - tooltipRect.height - 8;

        left = Math.max(8, Math.min(left, maxLeft));
        top = Math.max(8, Math.min(top, maxTop));

        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';
    }

    tooltipTargets.forEach(function (target) {
        target.addEventListener('mouseenter', function (event) {
            if (!tooltip) return;

            const text = target.getAttribute('data-tooltip');
            const direction = target.getAttribute('data-tooltip-dir') || 'bottom';

            tooltip.textContent = text;
            tooltip.setAttribute('data-dir', direction);
            tooltip.classList.add('show');

            moveTooltip(event, direction);
        });

        target.addEventListener('mousemove', function (event) {
            const direction = target.getAttribute('data-tooltip-dir') || 'bottom';
            moveTooltip(event, direction);
        });

        target.addEventListener('mouseleave', function () {
            if (!tooltip) return;

            tooltip.classList.remove('show');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | FORM INVALID / VALID HELPER
    |--------------------------------------------------------------------------
    | Kalau input punya error Laravel, biasanya class is-invalid sudah dari Blade.
    | Ini hanya bantu hilangkan status invalid saat user mulai mengetik.
    */
    const formControls = document.querySelectorAll('input, select, textarea');

    formControls.forEach(function (control) {
        control.addEventListener('input', function () {
            if (control.classList.contains('is-invalid')) {
                control.classList.remove('is-invalid');
            }
        });

        control.addEventListener('change', function () {
            if (control.classList.contains('is-invalid')) {
                control.classList.remove('is-invalid');
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | BUTTON LOADING SAAT SUBMIT FORM
    |--------------------------------------------------------------------------
    | Tambahkan data-loading-text pada button submit kalau mau.
    |
    | Contoh:
    | <button type="submit" data-loading-text="Menyimpan...">Simpan</button>
    */
    const forms = document.querySelectorAll('form');

    forms.forEach(function (form) {
        form.addEventListener('submit', function () {
            const submitButton = form.querySelector('button[type="submit"][data-loading-text]');

            if (!submitButton) return;

            submitButton.dataset.originalText = submitButton.innerHTML;
            submitButton.innerHTML = submitButton.getAttribute('data-loading-text');
            submitButton.disabled = true;
        });
    });

    /*
    |--------------------------------------------------------------------------
    | TABLE RESPONSIVE AUTO WRAPPER
    |--------------------------------------------------------------------------
    | Kalau ada table yang belum dibungkus .table-wrapper, JS ini bisa membungkusnya.
    | Tidak wajib, tapi membantu supaya table tidak merusak layout mobile.
    */
    const tables = document.querySelectorAll('table');

    tables.forEach(function (table) {
        const parent = table.parentElement;

        if (!parent || parent.classList.contains('table-wrapper')) return;
        if (table.closest('.table-wrapper')) return;
        if (table.classList.contains('no-auto-wrapper')) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'table-wrapper';

        parent.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });

    /*
    |--------------------------------------------------------------------------
    | AUTO CLOSE ALERT
    |--------------------------------------------------------------------------
    | Untuk alert Bootstrap biasa.
    */
    const alerts = document.querySelectorAll('.alert[data-auto-close]');

    alerts.forEach(function (alert) {
        const delay = parseInt(alert.getAttribute('data-auto-close'), 10) || 3000;

        setTimeout(function () {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-6px)';

            setTimeout(function () {
                alert.remove();
            }, 300);
        }, delay);
    });

    /*
    |--------------------------------------------------------------------------
    | REFRESH LUCIDE SETELAH SEMUA DOM MANIPULATION
    |--------------------------------------------------------------------------
    */
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
