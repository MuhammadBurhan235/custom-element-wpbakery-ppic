<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_laporan_hero', 'ppic_ppid_laporan_hero_render' );
function ppic_ppid_laporan_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'Laporan Layanan Informasi',
            'desc'     => 'Transparansi kinerja PPID PPI Curug melalui laporan tahunan dan statistik layanan yang akurat',
            'el_id'    => '',
            'el_class' => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-laporan-hero' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-laporan-hero-container">
            <h1 class="ppic-laporan-hero-title"><?php echo esc_html( $atts['title'] ); ?></h1>
            
            <?php if ( ! empty( $atts['desc'] ) ) : ?>
                <p class="ppic-laporan-hero-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_laporan_hero_element' );
function ppic_register_ppid_laporan_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Laporan - Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_laporan_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-text-page',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Laporan Layanan Informasi',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Transparansi kinerja PPID PPI Curug melalui laporan tahunan dan statistik layanan yang akurat',
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Masukkan element ID jika diperlukan.', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Masukkan class CSS tambahan.', 'js_composer' ),
                ),
            ),
        )
    );
}