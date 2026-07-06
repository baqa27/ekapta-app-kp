<script>
$(function() {
    // ============================================
    // 1. SIDEBAR COLLAPSE STATE (Minimize/Expand)
    // ============================================
    var sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
    if (sidebarCollapsed === 'true') {
        $('body').addClass('sidebar-collapse');
    } else if (sidebarCollapsed === 'false') {
        $('body').removeClass('sidebar-collapse');
    }

    // Save sidebar collapse state when toggled
    $(document).on('click', '[data-widget="pushmenu"]', function() {
        setTimeout(function() {
            var isCollapsed = $('body').hasClass('sidebar-collapse');
            localStorage.setItem('sidebarCollapsed', isCollapsed ? 'true' : 'false');
        }, 300);
    });

    // ============================================
    // 2. CLEAN UP OLD KEYS
    // ============================================
    var keysToRemove = [];
    for (var i = 0; i < localStorage.length; i++) {
        var key = localStorage.key(i);
        if (key && key.startsWith('sidebar_') && key !== 'sidebarCollapsed' && key !== 'sidebarMenuState') {
            keysToRemove.push(key);
        }
    }
    keysToRemove.forEach(function(key) {
        localStorage.removeItem(key);
    });

    // ============================================
    // 3. OVERRIDE ADMINLTE TREEVIEW (no accordion)
    // ============================================
    var originalExpand = $.fn.Treeview ? $.fn.Treeview.Constructor.prototype.expand : null;

    if (originalExpand) {
        $.fn.Treeview.Constructor.prototype.expand = function(treeviewMenu, parentLi) {
            // Skip accordion behavior - don't close siblings
            var expandedEvent = $.Event('expanded.lte.treeview');
            parentLi.addClass('menu-is-opening');
            treeviewMenu.stop().slideDown(this._config.animationSpeed, function() {
                parentLi.addClass('menu-open');
                parentLi.removeClass('menu-is-opening');
            });
        };
    }

    // ============================================
    // 4. MENU DROPDOWN STATE
    // ============================================
    var menuStateKey = 'sidebarMenuState';
    var menuState = {};
    var pendingMenuState = {};

    try {
        menuState = JSON.parse(localStorage.getItem(menuStateKey) || '{}') || {};
    } catch (e) {
        menuState = {};
    }

    function saveMenuState() {
        localStorage.setItem(menuStateKey, JSON.stringify(menuState));
    }

    function getMenuId($item) {
        var $label = $item.find('> .nav-link p').first().clone();
        $label.children().remove();

        return $label.text().trim();
    }

    function applyMenuState($item, isOpen) {
        var $treeview = $item.find('> .nav-treeview');

        $item.toggleClass('menu-open', isOpen);
        $treeview.stop(true, true).css('display', isOpen ? 'block' : 'none');
    }

    $('.nav-sidebar .nav-item.has-treeview').each(function() {
        var $item = $(this);
        var $treeview = $item.find('> .nav-treeview');
        if ($treeview.length > 0) {
            var menuId = getMenuId($item);

            // Seed state once from the rendered sidebar. After that, route changes
            // should not force dropdowns open/closed.
            if (!menuState.hasOwnProperty(menuId)) {
                menuState[menuId] = $item.hasClass('menu-open');
            }

            applyMenuState($item, menuState[menuId] === true);
        }
    });
    saveMenuState();

    // ============================================
    // 5. SAVE MENU STATE ON CLICK
    // ============================================
    $(document).on('pointerdown keydown', '.nav-sidebar .nav-item.has-treeview > .nav-link', function(e) {
        var key = e.key || e.which;
        if (e.type === 'keydown' && key !== 'Enter' && key !== ' ' && key !== 13 && key !== 32) {
            return;
        }

        var $parent = $(this).parent();
        var menuId = getMenuId($parent);

        pendingMenuState[menuId] = !$parent.hasClass('menu-open');
    });

    $(document).on('click', '.nav-sidebar .nav-item.has-treeview > .nav-link', function(e) {
        var $parent = $(this).parent();
        var $treeview = $parent.find('> .nav-treeview');

        if ($treeview.length > 0) {
            var menuId = getMenuId($parent);
            var willOpen = pendingMenuState.hasOwnProperty(menuId)
                ? pendingMenuState[menuId]
                : !$parent.hasClass('menu-open');

            menuState[menuId] = willOpen;
            saveMenuState();
            delete pendingMenuState[menuId];

            setTimeout(function() {
                menuState[menuId] = $parent.hasClass('menu-open');
                saveMenuState();
            }, 400);
        }
    });
});
</script>
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/partials/sidebar-menu-state.blade.php ENDPATH**/ ?>