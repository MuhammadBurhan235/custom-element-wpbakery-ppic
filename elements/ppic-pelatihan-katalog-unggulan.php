<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_pelatihan_katalog', 'ppic_pelatihan_katalog_render' );
function ppic_pelatihan_katalog_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'Katalog Unggulan',
            'desc'     => 'Pelatihan populer dari berbagai bidang penerbangan, diselenggarakan dengan standar global.',
            'courses'  => '',
            'btn_text' => 'Akses Katalog Lengkap',
            'btn_link' => 'url:katalog.html|title:Katalog%20Lengkap',
            'btn_icon' => 'fas fa-book-open',
            'el_id'    => 'katalog',
            'el_class' => '',
        ),
        $atts
    );

    // Proses data grid courses
    $courses = vc_param_group_parse_atts( $atts['courses'] );

    // Parse URL Tombol Bawah
    $link = ( '||' !== $atts['btn_link'] ) ? vc_build_link( $atts['btn_link'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_title  = ! empty( $link['title'] ) ? $link['title'] : '';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';
    $a_rel    = ! empty( $link['rel'] ) ? ' rel="' . esc_attr( trim( $link['rel'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-katalog-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-katalog-container">
            
            <div class="ppic-katalog-header">
                <h2 class="ppic-katalog-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="ppic-katalog-desc"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $courses ) && is_array( $courses ) ) : ?>
                <div class="ppic-katalog-grid">
                    <?php foreach ( $courses as $course ) : 
                        $badge_icon = isset( $course['badge_icon'] ) ? trim( $course['badge_icon'] ) : 'fas fa-star';
                        $badge_text = isset( $course['badge_text'] ) ? trim( $course['badge_text'] ) : 'Course';
                        $c_title    = isset( $course['title'] ) ? trim( $course['title'] ) : '';
                        $c_desc     = isset( $course['desc'] ) ? trim( $course['desc'] ) : '';
                        $wa_text    = isset( $course['wa_text'] ) ? trim( $course['wa_text'] ) : 'Tanya via WA';
                        $wa_link    = isset( $course['wa_link'] ) ? trim( $course['wa_link'] ) : '#';
                        
                        if ( empty( $c_title ) ) continue;
                        ?>
                        <div class="katalog-course-card">
                            <div class="katalog-course-badge">
                                <i class="<?php echo esc_attr( $badge_icon ); ?>" aria-hidden="true"></i> 
                                <?php echo esc_html( $badge_text ); ?>
                            </div>
                            <h4><?php echo esc_html( $c_title ); ?></h4>
                            <p><?php echo esc_html( $c_desc ); ?></p>
                            
                            <a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" class="katalog-course-link" rel="noopener noreferrer">
                                <span><?php echo esc_html( $wa_text ); ?></span>
                                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                <div class="ppic-katalog-cta">
                    <a href="<?php echo esc_url( $a_href ); ?>" class="btn-full-catalog" title="<?php echo esc_attr( $a_title ); ?>"<?php echo $a_target; ?><?php echo $a_rel; ?>>
                        <?php if ( ! empty( $atts['btn_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['btn_icon'] ); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                        <?php echo esc_html( $atts['btn_text'] ); ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_pelatihan_katalog_element' );
function ppic_register_pelatihan_katalog_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_courses = array(
        array( 
            'badge_icon' => 'fas fa-shield-alt', 
            'badge_text' => 'AVSEC', 
            'title'      => 'Basic & Junior Aviation Security', 
            'desc'       => 'Pelatihan dasar screening, X-ray, ETD, dan prosedur akses bandara. Sertifikasi Kemenhub + ICAO ASTC.',
            'wa_text'    => 'Tanya via WA',
            'wa_link'    => 'https://wa.me/6285156120178?text=Halo,%20saya%20tertarik%20dengan%20pelatihan%20Basic%20AVSEC'
        ),
        array( 
            'badge_icon' => 'fas fa-tools', 
            'badge_text' => 'CASR 147', 
            'title'      => 'Category A1.3 (Piston Engine)', 
            'desc'       => 'Perawatan pesawat bermesin piston, engine overhaul, airframe inspection, praktik di hanggar.',
            'wa_text'    => 'Tanya via WA',
            'wa_link'    => 'https://wa.me/6285156120178?text=Halo,%20info%20pelatihan%20A1.3'
        ),
        array( 
            'badge_icon' => 'fas fa-sim-card', 
            'badge_text' => 'Type Rating', 
            'title'      => 'A320 & B737NG (Initial)', 
            'desc'       => 'Full flight simulator Level D, sistem pesawat, prosedur normal/abnormal, LOFT, sertifikasi CASR 142.',
            'wa_text'    => 'Tanya via WA',
            'wa_link'    => 'https://wa.me/6285156120178?text=Halo,%20saya%20ingin%20type%20rating%20A320'
        ),
        array( 
            'badge_icon' => 'fas fa-tower-broadcast', 
            'badge_text' => 'ATC', 
            'title'      => 'Aerodrome Control & Radar Approach', 
            'desc'       => 'Pelatihan ATC berbasis simulator, phraseology, manajemen lalu lintas, sesuai CASR 143.',
            'wa_text'    => 'Tanya via WA',
            'wa_link'    => 'https://wa.me/6285156120178?text=Halo,%20saya%20tertarik%20ATC%20training'
        ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Pelatihan - Katalog', 'ppic-custom-element' ),
            'base'     => 'ppic_pelatihan_katalog',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-book',
            'params'   => array(
                // HEADER
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Katalog Unggulan',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Pelatihan populer dari berbagai bidang penerbangan, diselenggarakan dengan standar global.',
                ),

                // KARTU GRID
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Kursus Unggulan', 'ppic-custom-element' ),
                    'param_name' => 'courses',
                    'value'      => urlencode( wp_json_encode( $dummy_courses ) ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Ikon Badge (FontAwesome)', 'param_name' => 'badge_icon'),
                        array('type' => 'textfield', 'heading' => 'Teks Badge', 'param_name' => 'badge_text'),
                        array('type' => 'textfield', 'heading' => 'Judul Course', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textarea', 'heading' => 'Deskripsi Singkat', 'param_name' => 'desc'),
                        array('type' => 'textfield', 'heading' => 'Teks Link WA', 'param_name' => 'wa_text', 'value' => 'Tanya via WA'),
                        array('type' => 'textfield', 'heading' => 'URL Link WA', 'param_name' => 'wa_link', 'description' => 'Gunakan https://wa.me/...'),
                    ),
                ),

                // TOMBOL BAWAH
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Akses Katalog Lengkap',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_icon',
                    'value'      => 'fas fa-book-open',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                    'value'      => 'url:katalog.html|title:Katalog%20Lengkap',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'katalog',
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