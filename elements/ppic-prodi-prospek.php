<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_prospek', 'ppic_prodi_prospek_render' );
function ppic_prodi_prospek_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'section_id' => 'prospek',
            'title'      => 'Prospek Karier',
            'title_icon' => 'fas fa-chart-line',
            'subtitle'   => 'Lulusan TMB memiliki peluang karier yang luas di industri penerbangan, konstruksi, manufaktur, dan sektor terkait lainnya.',
            'items'      => '',
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-prospek-section section-block alt' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater prospek karier
    $prospek_items = vc_param_group_parse_atts( $atts['items'] );
    if ( ! is_array( $prospek_items ) ) {
        $prospek_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-prospek-container">
            
            <h2 class="ppic-section-title">
                <?php if ( ! empty( $atts['title_icon'] ) ) : ?>
                    <i class="<?php echo esc_attr( $atts['title_icon'] ); ?>"></i> 
                <?php endif; ?>
                <?php echo esc_html( $atts['title'] ); ?>
            </h2>
            
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-section-sub">
                    <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                </p>
            <?php endif; ?>

            <div class="ppic-prospek-grid">
                <?php if ( ! empty( $prospek_items ) ) : ?>
                    <?php foreach ( $prospek_items as $item ) : 
                        $icon  = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-plane';
                        $title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                        $desc  = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                        
                        if ( empty( $title ) ) continue;
                    ?>
                        <div class="ppic-prospek-card">
                            <div class="prospek-icon">
                                <i class="<?php echo esc_attr( $icon ); ?>"></i>
                            </div>
                            <h4><?php echo esc_html( $title ); ?></h4>
                            <?php if ( ! empty( $desc ) ) : ?>
                                <p><?php echo wp_kses_post( $desc ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="grid-column: 1 / -1; text-align:center; color:#64748b;">Data prospek karier belum ditambahkan.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_prospek_element' );
function ppic_register_prodi_prospek_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default data dari HTML klien
    $dummy_prospek = array(
        array( 'icon' => 'fas fa-plane', 'title' => 'Personel Mekanikal Bandar Udara', 'desc' => 'Bekerja di bandara nasional maupun internasional sebagai tenaga ahli mekanikal peralatan bandar udara.' ),
        array( 'icon' => 'fas fa-building', 'title' => 'Teknisi MEP Gedung', 'desc' => 'Mengelola sistem mekanikal, elektrikal, dan pemipaan di gedung-gedung perkantoran, mal, dan fasilitas umum.' ),
        array( 'icon' => 'fas fa-truck', 'title' => 'Teknisi Alat Berat', 'desc' => 'Menangani pemeliharaan dan perbaikan alat-alat berat di berbagai sektor industri dan konstruksi.' ),
        array( 'icon' => 'fas fa-clipboard-list', 'title' => 'Auditor & Assessor', 'desc' => 'Melakukan audit sistem keselamatan dan sertifikasi di lingkungan bandar udara dan industri terkait.' ),
        array( 'icon' => 'fas fa-cogs', 'title' => 'Insinyur Pemeliharaan', 'desc' => 'Merencanakan dan mengawasi program pemeliharaan peralatan mekanikal di berbagai fasilitas industri.' ),
        array( 'icon' => 'fas fa-chalkboard-teacher', 'title' => 'Instruktur / Dosen Vokasi', 'desc' => 'Mengajar dan melatih generasi baru di bidang mekanikal bandar udara dan teknik vokasi.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Prospek Karier', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_prospek',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-chart-line',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'prospek',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Prospek Karier',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-chart-line',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Lulusan TMB memiliki peluang karier yang luas di industri penerbangan, konstruksi, manufaktur, dan sektor terkait lainnya.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                // REPEATER KARTU PROSPEK
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Prospek Karier', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_prospek ) ),
                    'group'       => __( 'Data Prospek', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textfield', 'heading' => 'Nama Karier / Profesi', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon', 'value' => 'fas fa-plane'),
                        array('type' => 'textarea', 'heading' => 'Deskripsi Karier', 'param_name' => 'desc'),
                    ),
                ),
                // UMUM
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                ),
            ),
        )
    );
}