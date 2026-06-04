<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_gallery_hero', 'ppic_gallery_hero_render' );
function ppic_gallery_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_prefix'    => 'Galeri Langit',
            'title_highlight' => 'PPI Curug',
            'description'     => 'Jelajahi momen kebanggaan, pesawat, taruna, dan fasilitas terbaik pusat pendidikan aviasi nasional.',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-gallery-hero-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-gallery-hero__container">
            <div class="ppic-gallery-hero__content">
                <h1 class="ppic-gallery-hero__title">
                    <?php echo esc_html( $atts['title_prefix'] ); ?>
                    <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
                </h1>
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <p class="ppic-gallery-hero__description"><?php echo esc_html( $atts['description'] ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_gallery_hero_map' );
function ppic_gallery_hero_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Gallery Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_gallery_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-format-gallery', // Menggunakan icon gallery bawaan dashicons
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Awal', 'ppic-custom-element' ),
                    'param_name'  => 'title_prefix',
                    'value'       => 'Galeri Langit',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Highlight', 'ppic-custom-element' ),
                    'param_name'  => 'title_highlight',
                    'value'       => 'PPI Curug',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name'  => 'description',
                    'value'       => 'Jelajahi momen kebanggaan, pesawat, taruna, dan fasilitas terbaik pusat pendidikan aviasi nasional.',
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