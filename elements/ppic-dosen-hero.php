<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_dosen_hero', 'ppic_dosen_hero_render' );
function ppic_dosen_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_prefix' => 'Profil',
            'title_highlight' => 'Dosen & Instruktur',
            'description' => '68 akademisi dan praktisi penerbangan berdedikasi. Temukan informasi lengkap pengajaran, kepakaran, dan kontak profesional.',
            'button_text' => 'Kembali ke Beranda',
            'button_link' => 'url:/|title:Kembali ke Beranda',
            'el_id' => '',
            'el_class' => '',
        ),
        $atts
    );

    $button_link = vc_build_link( $atts['button_link'] );
    $button_href = ! empty( $button_link['url'] ) ? $button_link['url'] : '#';
    $button_target = ! empty( $button_link['target'] ) ? trim( $button_link['target'] ) : '';
    $button_rel = '_blank' === $button_target ? 'noopener noreferrer' : '';
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-dosen-hero-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-dosen-hero__container">
            <div class="ppic-dosen-hero__content">
                <h1 class="ppic-dosen-hero__title">
                    <?php echo esc_html( $atts['title_prefix'] ); ?>
                    <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
                </h1>
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <p class="ppic-dosen-hero__description"><?php echo esc_html( $atts['description'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $atts['button_text'] ) ) : ?>
                    <a
                        class="ppic-dosen-hero__button"
                        href="<?php echo esc_url( $button_href ); ?>"
                        <?php echo '' !== $button_target ? ' target="' . esc_attr( $button_target ) . '"' : ''; ?>
                        <?php echo '' !== $button_rel ? ' rel="' . esc_attr( $button_rel ) . '"' : ''; ?>
                    >
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        <span><?php echo esc_html( $atts['button_text'] ); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_dosen_hero_map' );
function ppic_dosen_hero_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Dosen Hero', 'ppic-custom-element' ),
            'base' => 'ppic_dosen_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-welcome-learn-more',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Awal', 'ppic-custom-element' ),
                    'param_name' => 'title_prefix',
                    'value' => 'Profil',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Highlight', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value' => 'Dosen & Instruktur',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value' => '68 akademisi dan praktisi penerbangan berdedikasi. Temukan informasi lengkap pengajaran, kepakaran, dan kontak profesional.',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks Tombol', 'ppic-custom-element' ),
                    'param_name' => 'button_text',
                    'value' => 'Kembali ke Beranda',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link Tombol (opsional, defaultnya mengarah ke home_url())', 'ppic-custom-element' ),
                    'param_name' => 'button_link',
                    'description' => __( 'Jika dikosongkan, tombol akan mengarah ke halaman beranda situs.', 'ppic-custom-element' ),
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