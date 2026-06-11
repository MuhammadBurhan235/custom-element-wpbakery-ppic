<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_hero', 'ppic_kenapa_hero_render' );
function ppic_kenapa_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_normal'    => 'Kenapa',
            'title_highlight' => 'PPI Curug?',
            'description'     => 'Kami dapat menyebutkan banyak alasan mengapa Anda layak memilih Politeknik Penerbangan Indonesia Curug sebagai tempat membangun masa depan di dunia penerbangan — dan hampir tidak ada alasan untuk tidak memilihnya. Berikut gambaran singkat tentang berbagai keunggulan yang kami tawarkan.',
            'btn_text'        => 'Kembali ke Beranda',
            'btn_url'         => '',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-kenapa-hero-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // LOGIKA PENENTUAN URL BERANDA (Home URL Fallback)
    // Jika btn_url diisi di WPBakery, gunakan itu. Jika dibiarkan kosong, otomatis gunakan home_url('/')
    $final_btn_url = ! empty( $atts['btn_url'] ) ? esc_url( $atts['btn_url'] ) : home_url( '/' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-kenapa-hero-container">
            <h1 class="ppic-kenapa-hero-title">
                <?php echo esc_html( $atts['title_normal'] ); ?> 
                <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
            </h1>
            
            <?php if ( ! empty( $atts['description'] ) ) : ?>
                <p class="ppic-kenapa-hero-desc"><?php echo esc_html( $atts['description'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                <a href="<?php echo $final_btn_url; ?>" class="ppic-kenapa-hero-btn">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> <?php echo esc_html( $atts['btn_text'] ); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_hero_element' );
function ppic_register_kenapa_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-learn-more',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Putih)', 'ppic-custom-element' ),
                    'param_name' => 'title_normal',
                    'value'      => 'Kenapa',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'PPI Curug?',
                    'description'=> __( 'Bagian teks ini akan diberi warna kuning khas.', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value'      => 'Kami dapat menyebutkan banyak alasan mengapa Anda layak memilih Politeknik Penerbangan Indonesia Curug sebagai tempat membangun masa depan di dunia penerbangan — dan hampir tidak ada alasan untuk tidak memilihnya. Berikut gambaran singkat tentang berbagai keunggulan yang kami tawarkan.',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Kembali', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Kembali ke Beranda',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'URL Tombol Kembali', 'ppic-custom-element' ),
                    'param_name' => 'btn_url',
                    'value'      => '',
                    'description'=> __( 'Biarkan kosong untuk otomatis kembali ke halaman utama (Home) website ini.', 'ppic-custom-element' ),
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