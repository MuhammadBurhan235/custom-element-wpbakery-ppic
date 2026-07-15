<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_nav', 'ppic_prodi_nav_render' );
function ppic_prodi_nav_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'sticky_offset' => '80', // Jarak default dari atas (menyesuaikan tinggi navbar utama)
            'nav_items'     => '',
            'el_id'         => '',
            'el_class'      => '',
        ),
        $atts
    );

    // Ambil data menu dari repeater WPBakery
    $menus = vc_param_group_parse_atts( $atts['nav_items'] );
    if ( ! is_array( $menus ) ) {
        $menus = array();
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : ' id="prodiNav"';
    $wrapper_class = 'ppic-prodi-nav' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    
    // Inject custom sticky top offset
    $sticky_top = intval( $atts['sticky_offset'] );
    $inline_style = 'style="top: ' . $sticky_top . 'px;"';

    ob_start(); ?>
    
    <nav<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" <?php echo $inline_style; ?>>
        <div class="ppic-prodi-nav-container">
            <?php if ( ! empty( $menus ) ) : ?>
                <?php foreach ( $menus as $index => $menu ) : 
                    $icon = isset( $menu['icon'] ) ? trim( $menu['icon'] ) : 'fas fa-chevron-right';
                    $label = isset( $menu['label'] ) ? trim( $menu['label'] ) : '';
                    $target = isset( $menu['target_id'] ) ? trim( $menu['target_id'] ) : '';
                    
                    if ( empty( $label ) || empty( $target ) ) continue;
                    
                    // Pastikan target diawali dengan '#'
                    if ( strpos( $target, '#' ) !== 0 ) {
                        $target = '#' . $target;
                    }
                    
                    // Beri class active pada item pertama secara default
                    $active_class = ( $index === 0 ) ? ' active' : '';
                ?>
                    <a href="<?php echo esc_attr( $target ); ?>" class="prodi-nav-link<?php echo $active_class; ?>">
                        <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i> <?php echo esc_html( $label ); ?>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <!-- Fallback jika kosong -->
                <a href="#overview" class="prodi-nav-link active"><i class="fas fa-home"></i> Overview</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php
    // Memastikan JavaScript Scroll & ScrollSpy hanya di-load sekali
    static $ppic_prodi_nav_js_loaded = false;
    if ( ! $ppic_prodi_nav_js_loaded ) {
        $ppic_prodi_nav_js_loaded = true;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.prodi-nav-link');
            const navBar = document.querySelector('.ppic-prodi-nav');
            
            if (!navBar || navLinks.length === 0) return;

            // 1. Fitur Smooth Scroll
            navLinks.forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetSection = document.querySelector(targetId);
                    if (targetSection) {
                        // Kalkulasi posisi scroll: Posisi elemen dikurangi tinggi navbar utama (sticky offset) dikurangi tinggi navbar prodi ini sendiri
                        const stickyOffset = parseInt(window.getComputedStyle(navBar).top, 10) || 80;
                        const navHeight = navBar.offsetHeight;
                        const totalOffset = stickyOffset + navHeight + 20; // +20px breathing room
                        
                        const elementPosition = targetSection.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - totalOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                });
            });

            // 2. Fitur ScrollSpy (Auto update active link saat user scroll)
            window.addEventListener('scroll', function() {
                let current = '';
                const scrollPosition = window.pageYOffset;
                const stickyOffset = parseInt(window.getComputedStyle(navBar).top, 10) || 80;
                const totalOffset = stickyOffset + navBar.offsetHeight + 50; // Toleransi pembacaan 50px

                navLinks.forEach(link => {
                    const targetId = link.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const section = document.querySelector(targetId);
                    if (section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;
                        
                        if (scrollPosition >= (sectionTop - totalOffset) && scrollPosition < (sectionTop + sectionHeight - totalOffset)) {
                            current = targetId;
                        }
                    }
                });

                if (current !== '') {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === current) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_nav_element' );
function ppic_register_prodi_nav_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default data sesuai gambar dan HTML dari client
    $dummy_nav = array(
        array( 'icon' => 'fas fa-home', 'label' => 'Overview', 'target_id' => 'overview' ),
        array( 'icon' => 'fas fa-book-open', 'label' => 'Kurikulum', 'target_id' => 'kurikulum' ),
        array( 'icon' => 'fas fa-users', 'label' => 'Profil Lulusan', 'target_id' => 'profil-lulusan' ),
        array( 'icon' => 'fas fa-certificate', 'label' => 'Sertifikasi', 'target_id' => 'sertifikasi' ),
        array( 'icon' => 'fas fa-building', 'label' => 'Fasilitas', 'target_id' => 'fasilitas' ),
        array( 'icon' => 'fas fa-chart-line', 'label' => 'Prospek Karier', 'target_id' => 'prospek' ),
        array( 'icon' => 'fas fa-handshake', 'label' => 'Kerja Sama Industri', 'target_id' => 'kerjasama' ),
        array( 'icon' => 'fas fa-calendar-check', 'label' => 'Kegiatan', 'target_id' => 'kegiatan' ),
        array( 'icon' => 'fas fa-comment-dots', 'label' => 'Testimoni Alumni', 'target_id' => 'testimoni' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Sticky Nav', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_nav',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-menu-alt',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Jarak Sticky dari Atas (px)', 'ppic-custom-element' ),
                    'param_name'  => 'sticky_offset',
                    'value'       => '80',
                    'description' => __( 'Ubah angka ini jika navbar tertimpa oleh header utama website. 80 biasanya cocok untuk tinggi header standar.', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Menu Navigasi', 'ppic-custom-element' ),
                    'param_name'  => 'nav_items',
                    'value'       => urlencode( wp_json_encode( $dummy_nav ) ),
                    'description' => __( 'Pastikan Target ID Section sesuai dengan ID elemen (Row) WPBakery tujuan Anda (tanpa tanda #).', 'ppic-custom-element' ),
                    'params'      => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Label Menu', 'ppic-custom-element' ),
                            'param_name'  => 'label',
                            'admin_label' => true,
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Target ID Section', 'ppic-custom-element' ),
                            'param_name'  => 'target_id',
                            'description' => __( 'Contoh: overview (tanpa hashtag)', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Ikon (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'value'       => 'fas fa-home',
                        ),
                    ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                ),
            ),
        )
    );
}