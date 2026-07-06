{{-- Sidebar Menu State Persistence untuk Himpunan KP --}}
<script>
$(document).ready(function() {
    // Key untuk localStorage
    const STORAGE_KEY = 'ekapta_sidebar_himpunan_kp_state';
    
    // Fungsi untuk load state dari localStorage
    function loadMenuState() {
        try {
            const savedState = localStorage.getItem(STORAGE_KEY);
            if (savedState) {
                const state = JSON.parse(savedState);
                
                // Restore state untuk setiap menu
                Object.keys(state).forEach(menuId => {
                    const $menu = $(`[data-menu-id="${menuId}"]`);
                    if ($menu.length) {
                        if (state[menuId] === 'open') {
                            $menu.addClass('menu-open');
                            $menu.children('.nav-link').addClass('active');
                        } else {
                            $menu.removeClass('menu-open');
                            // Jangan remove active dari nav-link jika ada submenu yang active
                            if (!$menu.find('.nav-treeview .nav-link.active').length) {
                                $menu.children('.nav-link').removeClass('active');
                            }
                        }
                    }
                });
            }
        } catch (e) {
            console.error('Error loading menu state:', e);
        }
    }
    
    // Fungsi untuk save state ke localStorage
    function saveMenuState() {
        try {
            const state = {};
            
            // Simpan state semua menu yang punya treeview
            $('.nav-item.has-treeview[data-menu-id]').each(function() {
                const $menu = $(this);
                const menuId = $menu.attr('data-menu-id');
                state[menuId] = $menu.hasClass('menu-open') ? 'open' : 'closed';
            });
            
            localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        } catch (e) {
            console.error('Error saving menu state:', e);
        }
    }
    
    // Load state saat halaman dimuat
    loadMenuState();
    
    // Save state saat menu diklik
    $('.nav-item.has-treeview[data-menu-id] > .nav-link').on('click', function(e) {
        // Tunggu sebentar agar AdminLTE selesai toggle class
        setTimeout(saveMenuState, 100);
    });
    
    // Save state saat submenu diklik (navigasi ke halaman lain)
    $('.nav-item.has-treeview[data-menu-id] .nav-treeview .nav-link').on('click', function() {
        saveMenuState();
    });
});
</script>
