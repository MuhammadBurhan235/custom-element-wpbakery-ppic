<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_training_catalog_hero', 'ppic_training_catalog_hero_render' );
function ppic_training_catalog_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => 'Katalog Pelatihan Penerbangan',
            'description' => 'Pelatihan bersertifikasi Kementerian Perhubungan dengan deskripsi spesifik. Gunakan filter kategori dan pencarian di sidebar kiri.',
            'el_id' => '',
            'el_class' => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-training-hero-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-training-hero__container">
            <div class="ppic-training-hero__content">
                <h1 class="ppic-training-hero__title"><?php echo esc_html( $atts['title'] ); ?></h1>
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <p class="ppic-training-hero__description"><?php echo esc_html( $atts['description'] ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_training_catalog_hero_map' );
function ppic_training_catalog_hero_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Training Catalog Hero', 'ppic-custom-element' ),
            'base' => 'ppic_training_catalog_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-welcome-learn-more',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value' => 'Katalog Pelatihan Penerbangan',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value' => 'Pelatihan bersertifikasi Kementerian Perhubungan dengan deskripsi spesifik. Gunakan filter kategori dan pencarian di sidebar kiri.',
                ),
                array(
                    'type' => 'el_id',
                    'heading' => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}