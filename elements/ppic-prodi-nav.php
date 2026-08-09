<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_nav', 'ppic_prodi_nav_render' );
function ppic_prodi_nav_render( $atts ) {
    $atts = shortcode_atts(
        array(
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
    
    ob_start(); ?>
    
    <nav<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-prodi-nav-container">
            <?php if ( ! empty( $menus ) ) : ?>
                <?php foreach ( $menus as $index => $menu ) : 
                    $icon = isset( $menu['icon'] ) ? trim( $menu['icon'] ) : 'fas fa-chevron-right';
                    $label = isset( $menu['label'] ) ? trim( $menu['label'] ) : '';
                    $target = isset( $menu['target_id'] ) ? trim( $menu['target_id'] ) : '';
                    
                    if ( empty( $label ) || empty( $target ) ) continue;
                    
                    if ( strpos( $target, '#' ) !== 0 ) {
                        $target = '#' . $target;
                    }
                    
                    $active_class = ( $index === 0 ) ? ' active' : '';
                ?>
                    <a href="<?php echo esc_attr( $target ); ?>" class="prodi-nav-link<?php echo $active_class; ?>">
                        <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i> <?php echo esc_html( $label ); ?>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <a href="#overview" class="prodi-nav-link active"><i class="fas fa-home"></i> Overview</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php
    static $ppic_prodi_nav_js_loaded = false;
    if ( ! $ppic_prodi_nav_js_loaded ) {
        $ppic_prodi_nav_js_loaded = true;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.prodi-nav-link');
            const navBar = document.querySelector('.ppic-prodi-nav');
            
            if (!navBar || navLinks.length === 0) return;

            // FUNGSI INTI: Kalkulasi Jarak Sticky yang Tepat dan Kuat
            function calculateAutoStickyOffset() {
                let currentOffset = 0;
                
                // 1. Deteksi WP Admin Bar (Hitam di atas)
                const wpAdminBar = document.getElementById('wpadminbar');
                if (wpAdminBar && window.getComputedStyle(wpAdminBar).position === 'fixed') {
                    currentOffset += wpAdminBar.offsetHeight;
                }

                // 2. Deteksi Header Utama 
                // Mengambil elemen header yang teridentifikasi sticky pada inspeksi elemen
                const mainHeader = document.querySelector('header.header');
                
                if (mainHeader) {
                    const rect = mainHeader.getBoundingClientRect();
                    const style = window.getComputedStyle(mainHeader);
                    
                    // Verifikasi apakah header benar-benar menempel (fixed/sticky) dan berada di area atas layar
                    if ((style.position === 'fixed' || style.position === 'sticky') && rect.top <= (currentOffset + 5)) {
                        // Langsung ambil batas bawah dari header
                        currentOffset = Math.max(currentOffset, rect.bottom);
                    } else if (mainHeader.classList.contains('sticky')) {
                        // Fallback jika CSS telat ter-render: ambil tingginya langsung saat state 'sticky' aktif
                        currentOffset += mainHeader.offsetHeight;
                    }
                }
                
                // Aplikasikan nilai yang telah dihitung ke navigasi prodi
                navBar.style.setProperty('--dynamic-sticky-top', currentOffset + 'px');
                navBar.style.top = currentOffset + 'px';
                
                return currentOffset;
            }

            // Jalankan kalkulasi awal
            let dynamicStickyOffset = calculateAutoStickyOffset();

            // Dengarkan perubahan layar dan scroll (menggunakan requestAnimationFrame untuk performa)
            let isScrolling = false;
            window.addEventListener('scroll', function() {
                if (!isScrolling) {
                    window.requestAnimationFrame(function() {
                        dynamicStickyOffset = calculateAutoStickyOffset();
                        updateScrollSpy();
                        isScrolling = false;
                    });
                    isScrolling = true;
                }
            }, {passive: true});

            window.addEventListener('resize', calculateAutoStickyOffset);

            // Fitur Mutation Observer: Otomatis deteksi saat class .sticky ditambahkan/dihapus oleh tema
            const headerToWatch = document.querySelector('header.header');
            if (headerToWatch) {
                const observer = new MutationObserver(function(mutations) {
                    calculateAutoStickyOffset();
                });
                observer.observe(headerToWatch, { attributes: true, attributeFilter: ['class', 'style'] });
            }

            // Fitur Smooth Scroll
            navLinks.forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetSection = document.querySelector(targetId);
                    if (targetSection) {
                        const finalOffset = calculateAutoStickyOffset();
                        const navHeight = navBar.offsetHeight;
                        
                        // Jarak napas (breathing room)
                        const totalOffset = finalOffset + navHeight + 20; 
                        
                        const elementPosition = targetSection.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - totalOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                });
            });

            // Fitur ScrollSpy terpisah untuk dipanggil efisien saat scroll
            function updateScrollSpy() {
                let current = '';
                const scrollPosition = window.pageYOffset;
                const totalOffset = dynamicStickyOffset + navBar.offsetHeight + 50; 

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
            }
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