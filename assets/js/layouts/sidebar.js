(function() {
    "use strict";
    console.log("Sidebar Logic Initialized!");

    function initSidebar() {
        const sidebarMenu = document.getElementById('sidebar-menu');
        if (!sidebarMenu) return;

        sidebarMenu.addEventListener('click', function(e) {
            const target = e.target.closest('.dropdown > a');
            if (!target) return;

            e.preventDefault();
            const parentLi = target.parentElement;
            const submenu = parentLi.querySelector('.sidebar-submenu');

            // 1. Close others (Accordion)
            document.querySelectorAll('.sidebar-menu .dropdown').forEach(item => {
                if (item !== parentLi) {
                    item.classList.remove('open');
                    const sub = item.querySelector('.sidebar-submenu');
                    if (sub) sub.style.display = 'none';
                }
            });

            // 2. Toggle Current
            const isOpen = parentLi.classList.toggle('open');
            if (submenu) {
                submenu.style.display = isOpen ? 'block' : 'none';
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
})();