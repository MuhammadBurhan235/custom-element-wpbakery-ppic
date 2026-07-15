<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_pelatihan_astc', 'ppic_pelatihan_astc_render' );
function ppic_pelatihan_astc_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'           => 'ICAO ASTC (Aviation Security Training Centre)',
            'desc'            => 'PPI Curug merupakan pusat pelatihan keamanan penerbangan resmi ICAO (ASTC). Kami menawarkan kursus dan workshop bersertifikat internasional untuk personel avsec.',
            
            'courses_title'   => 'ASTC Courses',
            'courses_icon'    => 'fas fa-graduation-cap',
            'courses'         => '',
            
            'workshops_title' => 'ASTC Workshops',
            'workshops_icon'  => 'fas fa-chalkboard',
            'workshops'       => '',
            
            'btn_text'        => 'Tanyakan tentang ASTC →',
            'btn_link'        => 'url:https%3A%2F%2Fwa.me%2F6285156120178|target:_blank',
            'btn_icon'        => 'fab fa-whatsapp',
            
            'el_id'           => 'astc',
            'el_class'        => '',
        ),
        $atts
    );

    // Proses data grid (Courses & Workshops)
    $courses   = vc_param_group_parse_atts( $atts['courses'] );
    $workshops = vc_param_group_parse_atts( $atts['workshops'] );

    // Parse URL Tombol CTA
    $link = ( '||' !== $atts['btn_link'] ) ? vc_build_link( $atts['btn_link'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_title  = ! empty( $link['title'] ) ? $link['title'] : '';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';
    $a_rel    = ! empty( $link['rel'] ) ? ' rel="' . esc_attr( trim( $link['rel'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-astc-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-astc-container">
            
            <h2 class="ppic-astc-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['desc'] ) ) : ?>
                <p class="ppic-astc-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $atts['courses_title'] ) ) : ?>
                <h3 class="ppic-astc-subtitle">
                    <?php if ( ! empty( $atts['courses_icon'] ) ) : ?>
                        <i class="<?php echo esc_attr( $atts['courses_icon'] ); ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                    <?php echo esc_html( $atts['courses_title'] ); ?>
                </h3>
            <?php endif; ?>

            <?php if ( ! empty( $courses ) && is_array( $courses ) ) : ?>
                <div class="ppic-astc-grid">
                    <?php foreach ( $courses as $course ) : 
                        $c_icon  = isset( $course['icon'] ) ? trim( $course['icon'] ) : 'fas fa-book';
                        $c_badge = isset( $course['badge'] ) ? trim( $course['badge'] ) : 'ASTC Course';
                        $c_title = isset( $course['title'] ) ? trim( $course['title'] ) : '';
                        $c_desc  = isset( $course['desc'] ) ? trim( $course['desc'] ) : '';
                        
                        if ( empty( $c_title ) ) continue;
                        ?>
                        <div class="astc-card">
                            <div class="astc-icon"><i class="<?php echo esc_attr( $c_icon ); ?>" aria-hidden="true"></i></div>
                            <?php if ( ! empty( $c_badge ) ) : ?>
                                <div class="badge-astc"><?php echo esc_html( $c_badge ); ?></div>
                            <?php endif; ?>
                            <h4><?php echo esc_html( $c_title ); ?></h4>
                            <?php if ( ! empty( $c_desc ) ) : ?>
                                <p><?php echo esc_html( $c_desc ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $atts['workshops_title'] ) ) : ?>
                <h3 class="ppic-astc-subtitle" style="margin-top: 50px;">
                    <?php if ( ! empty( $atts['workshops_icon'] ) ) : ?>
                        <i class="<?php echo esc_attr( $atts['workshops_icon'] ); ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                    <?php echo esc_html( $atts['workshops_title'] ); ?>
                </h3>
            <?php endif; ?>

            <?php if ( ! empty( $workshops ) && is_array( $workshops ) ) : ?>
                <div class="ppic-astc-grid">
                    <?php foreach ( $workshops as $workshop ) : 
                        $w_icon  = isset( $workshop['icon'] ) ? trim( $workshop['icon'] ) : 'fas fa-file-alt';
                        $w_badge = isset( $workshop['badge'] ) ? trim( $workshop['badge'] ) : 'Workshop';
                        $w_title = isset( $workshop['title'] ) ? trim( $workshop['title'] ) : '';
                        $w_desc  = isset( $workshop['desc'] ) ? trim( $workshop['desc'] ) : '';
                        
                        if ( empty( $w_title ) ) continue;
                        ?>
                        <div class="astc-card">
                            <div class="astc-icon"><i class="<?php echo esc_attr( $w_icon ); ?>" aria-hidden="true"></i></div>
                            <?php if ( ! empty( $w_badge ) ) : ?>
                                <div class="badge-astc"><?php echo esc_html( $w_badge ); ?></div>
                            <?php endif; ?>
                            <h4><?php echo esc_html( $w_title ); ?></h4>
                            <?php if ( ! empty( $w_desc ) ) : ?>
                                <p><?php echo esc_html( $w_desc ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                <div class="astc-cta-wrapper">
                    <a href="<?php echo esc_url( $a_href ); ?>" class="btn-astc-large" title="<?php echo esc_attr( $a_title ); ?>"<?php echo $a_target; ?><?php echo $a_rel; ?>>
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

add_action( 'vc_before_init', 'ppic_register_pelatihan_astc_element' );
function ppic_register_pelatihan_astc_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Penambahan Lengkap 6 Item Courses
    $dummy_courses = array(
        array( 'icon' => 'fas fa-chalkboard-user', 'badge' => 'ASTC Course', 'title' => 'AVIATION SECURITY NATIONAL INSTRUCTORS', 'desc' => 'Pelatihan instruktur avsec nasional: metodologi pengajaran, sertifikasi pengajar, dan evaluasi kompetensi sesuai ICAO.' ),
        array( 'icon' => 'fas fa-user-shield', 'badge' => 'ASTC Course', 'title' => 'AIRPORT SECURITY SUPERVISORS', 'desc' => 'Supervisi operasional keamanan bandara, manajemen tim screening, investigasi insiden dan kepatuhan regulasi.' ),
        array( 'icon' => 'fas fa-box', 'badge' => 'ASTC Course', 'title' => 'AIR CARGO & MAIL SECURITY', 'desc' => 'Keamanan kargo dan pos udara: prosedur penerimaan, screening, dokumentasi, dan rantai suplai aman.' ),
        array( 'icon' => 'fas fa-clipboard-list', 'badge' => 'ASTC Course', 'title' => 'AVIATION SECURITY NATIONAL INSPECTORS', 'desc' => 'Pelatihan inspektur keamanan penerbangan: teknik audit, inspeksi fasilitas, penegakan regulasi nasional & ICAO.' ),
        array( 'icon' => 'fas fa-briefcase', 'badge' => 'ASTC Course', 'title' => 'AVIATION SECURITY MANAGERS', 'desc' => 'Manajemen strategis keamanan penerbangan, penyusunan kebijakan, dan pengawasan standar mutu operasional.' ),
        array( 'icon' => 'fas fa-user-secret', 'badge' => 'ASTC Course', 'title' => 'BEHAVIOUR DETECTION', 'desc' => 'Deteksi perilaku mencurigakan, teknik observasi berbasis psikologi, dan profil untuk identifikasi ancaman.' ),
    );

    // Penambahan Lengkap 8 Item Workshops
    $dummy_workshops = array(
        array( 'icon' => 'fas fa-file-alt', 'badge' => 'Workshop', 'title' => 'National Civil Aviation Security Quality Control Programme', 'desc' => 'Program pengendalian mutu keamanan penerbangan nasional.' ),
        array( 'icon' => 'fas fa-certificate', 'badge' => 'Workshop', 'title' => 'Aviation Security National Certification', 'desc' => 'Sertifikasi personel dan entitas avsec tingkat nasional.' ),
        array( 'icon' => 'fas fa-user-shield', 'badge' => 'Workshop', 'title' => 'National Civil Aviation Security Programme', 'desc' => 'Implementasi NCASP sesuai standar ICAO Annex 17.' ),
        array( 'icon' => 'fas fa-landmark', 'badge' => 'Workshop', 'title' => 'Security Culture', 'desc' => 'Pengembangan budaya sadar keamanan di lingkungan bandara.' ),
        array( 'icon' => 'fas fa-plane', 'badge' => 'Workshop', 'title' => 'Airport Security Programme (ASP)', 'desc' => 'Penyusunan dan evaluasi program keamanan bandara individu.' ),
        array( 'icon' => 'fas fa-laptop', 'badge' => 'Workshop', 'title' => 'National Civil Aviation Security Training Programme', 'desc' => 'Program pelatihan keamanan nasional berbasis kompetensi.' ),
        array( 'icon' => 'fas fa-chart-line', 'badge' => 'Workshop', 'title' => 'Risk Management', 'desc' => 'Manajemen risiko keamanan & threat assessment.' ),
        array( 'icon' => 'fas fa-ambulance', 'badge' => 'Workshop', 'title' => 'Crisis Management', 'desc' => 'Penanganan krisis, command center, dan koordinasi darurat.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Pelatihan - ICAO ASTC', 'ppic-custom-element' ),
            'base'     => 'ppic_pelatihan_astc',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-shield',
            'params'   => array(
                // HEADER
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'ICAO ASTC (Aviation Security Training Centre)',
                    'admin_label'=> true,
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'PPI Curug merupakan pusat pelatihan keamanan penerbangan resmi ICAO (ASTC). Kami menawarkan kursus dan workshop bersertifikat internasional untuk personel avsec.',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),

                // COURSES
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Sub-Seksi Courses', 'ppic-custom-element' ),
                    'param_name' => 'courses_title',
                    'value'      => 'ASTC Courses',
                    'group'      => __( 'Courses', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Judul Courses (FontAwesome)', 'ppic-custom-element' ),
                    'param_name' => 'courses_icon',
                    'value'      => 'fas fa-graduation-cap',
                    'group'      => __( 'Courses', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Grid Courses', 'ppic-custom-element' ),
                    'param_name' => 'courses',
                    'value'      => urlencode( wp_json_encode( $dummy_courses ) ),
                    'group'      => __( 'Courses', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Ikon (FontAwesome)', 'param_name' => 'icon'),
                        array('type' => 'textfield', 'heading' => 'Teks Badge', 'param_name' => 'badge', 'value' => 'ASTC Course'),
                        array('type' => 'textfield', 'heading' => 'Judul Course', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'desc'),
                    ),
                ),

                // WORKSHOPS
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Sub-Seksi Workshops', 'ppic-custom-element' ),
                    'param_name' => 'workshops_title',
                    'value'      => 'ASTC Workshops',
                    'group'      => __( 'Workshops', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Judul Workshops (FontAwesome)', 'ppic-custom-element' ),
                    'param_name' => 'workshops_icon',
                    'value'      => 'fas fa-chalkboard',
                    'group'      => __( 'Workshops', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Grid Workshops', 'ppic-custom-element' ),
                    'param_name' => 'workshops',
                    'value'      => urlencode( wp_json_encode( $dummy_workshops ) ),
                    'group'      => __( 'Workshops', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Ikon (FontAwesome)', 'param_name' => 'icon'),
                        array('type' => 'textfield', 'heading' => 'Teks Badge', 'param_name' => 'badge', 'value' => 'Workshop'),
                        array('type' => 'textfield', 'heading' => 'Judul Workshop', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'desc'),
                    ),
                ),

                // TOMBOL CTA
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Tanyakan tentang ASTC →',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tombol (FontAwesome)', 'ppic-custom-element' ),
                    'param_name' => 'btn_icon',
                    'value'      => 'fab fa-whatsapp',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                    'value'      => 'url:https%3A%2F%2Fwa.me%2F6285156120178|target:_blank',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'astc',
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