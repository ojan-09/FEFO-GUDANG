/**
 * Sidebar State Persistence
 * Handles LocalStorage for sidebar toggle and submenu state without affecting CI4 navigation architecture
 */

(function () {
    var KEY_SIDEBAR = 'wms-sidebar-collapsed';
    var KEY_SUBMENU = 'wms-sidebar-submenu-wilayah';
    
    // FOUC Prevention - runs immediately
    document.documentElement.classList.add('sb-preload');
    if (window.innerWidth > 768 && localStorage.getItem(KEY_SIDEBAR) === 'true') {
        document.documentElement.classList.add('sb-pre-collapsed');
    }

    // Expose utility for layout scripts
    window.WmsSidebarState = {
        init: function(isWilayahActive) {
            var sidebar = document.getElementById('sidebar');
            var body = document.body;
            var wilTrigger = document.getElementById('wilayahTrigger');
            var wilBody = document.getElementById('wilayahBody');

            // 1. Sidebar Collapse State (Desktop)
            if (window.innerWidth > 768) {
                if (localStorage.getItem(KEY_SIDEBAR) === 'true') {
                    sidebar.classList.add('collapsed');
                    body.classList.add('sb-collapsed');
                }
            }
            // Gunakan double rAF untuk memastikan CSS selesai dirender sebelum transisi aktif
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    document.documentElement.classList.remove('sb-pre-collapsed', 'sb-preload');
                });
            });

            // 2. Submenu State (Gudang Wilayah)
            // Priority: URL (isWilayahActive) > LocalStorage
            if (wilTrigger && wilBody) {
                if (!isWilayahActive) {
                    var submenuOpen = localStorage.getItem(KEY_SUBMENU) === 'true';
                    if (submenuOpen) {
                        wilTrigger.classList.add('open');
                        wilBody.classList.add('open');
                    }
                }
            }
        },
        toggleSidebar: function() {
            var sidebar = document.getElementById('sidebar');
            var body = document.body;
            var isCollapsed = sidebar.classList.toggle('collapsed');
            body.classList.toggle('sb-collapsed', isCollapsed);
            localStorage.setItem(KEY_SIDEBAR, isCollapsed);
        },
        toggleSubmenu: function() {
            var sidebar = document.getElementById('sidebar');
            var wilTrigger = document.getElementById('wilayahTrigger');
            var wilBody = document.getElementById('wilayahBody');
            
            if (!wilTrigger || !wilBody) return;
            
            if (sidebar && sidebar.classList.contains('collapsed')) {
                // If sidebar is collapsed, link directly to dashboard
                // This logic is typically handled by the click handler inline, but we can return false to indicate redirection
                return false;
            }
            
            var isOpen = wilTrigger.classList.toggle('open');
            wilBody.classList.toggle('open');
            localStorage.setItem(KEY_SUBMENU, isOpen);
            return true;
        },
        clearOnLogout: function() {
            localStorage.removeItem(KEY_SIDEBAR);
            localStorage.removeItem(KEY_SUBMENU);
            localStorage.removeItem('wms-sidebar-scroll'); // existing scroll tracking
        }
    };
})();
