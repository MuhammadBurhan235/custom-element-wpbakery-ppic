<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_pelatihan_hero', 'ppic_pelatihan_hero_render' );
function ppic_pelatihan_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'      => 'Global Training Center',
            'desc'       => 'Pusat pelatihan penerbangan bersertifikasi internasional ICAO TRAINAIR PLUS (TPP) & ICAO ASTC. Standar global untuk personel maskapai, bandara, dan ATC.',
            'el_id'      => '',
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-pelatihan-hero' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-pelatihan-hero-container">
            <h1 class="ppic-pelatihan-hero-title"><?php echo esc_html( $atts['title'] ); ?></h1>
            
            <?php if ( ! empty( $atts['desc'] ) ) : ?>
                <p class="ppic-pelatihan-hero-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_pelatihan_hero_element' );
function ppic_register_pelatihan_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Pelatihan - Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_pelatihan_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-megaphone', // Ikon Toa/Pengumuman
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Global Training Center',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Pusat pelatihan penerbangan bersertifikasi internasional ICAO TRAINAIR PLUS (TPP) & ICAO ASTC. Standar global untuk personel maskapai, bandara, dan ATC.',
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}