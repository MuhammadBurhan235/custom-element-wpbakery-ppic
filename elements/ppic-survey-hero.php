<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_survey_hero', 'ppic_survey_hero_render' );
function ppic_survey_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_prefix'    => 'Satuan',
            'title_highlight' => 'Penjaminan',
            'title_suffix'    => 'Mutu',
            'subtitle'        => 'Mendengar. Mengevaluasi. Menyempurnakan. Bersama mewujudkan mutu pendidikan penerbangan berkelas dunia.',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Persiapan Wrapper ID & Class
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'survey-hero' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="survey-hero-container">
            <h1>
                <?php echo esc_html( $atts['title_prefix'] ); ?> 
                <?php if ( ! empty( $atts['title_highlight'] ) ) : ?>
                    <span><?php echo esc_html( $atts['title_highlight'] ); ?></span> 
                <?php endif; ?>
                <?php echo esc_html( $atts['title_suffix'] ); ?>
            </h1>
            
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="sub">
                    <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_survey_hero_element' );
function ppic_register_survey_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Survey Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_survey_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-megaphone',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul Awal (Putih)', 'ppic-custom-element' ),
                    'param_name'  => 'title_prefix',
                    'value'       => 'Satuan',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Sorotan (Kuning)', 'ppic-custom-element' ),
                    'param_name'  => 'title_highlight',
                    'value'       => 'Penjaminan',
                    'description' => __( 'Kata ini akan otomatis diberi warna kuning.', 'ppic-custom-element' ),
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul Akhir (Putih)', 'ppic-custom-element' ),
                    'param_name'  => 'title_suffix',
                    'value'       => 'Mutu',
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Mendengar. Mengevaluasi. Menyempurnakan. Bersama mewujudkan mutu pendidikan penerbangan berkelas dunia.',
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