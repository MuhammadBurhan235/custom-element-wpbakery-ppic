<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_berita_hero', 'ppic_berita_hero_render' );
function ppic_berita_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_normal'    => 'BERITA &',
            'title_highlight' => 'KEGIATAN',
            'desc'            => 'Update terkini seputar dunia penerbangan, inovasi kampus, siaran pers resmi, serta momen kebanggaan civitas PPI Curug.',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-berita-hero' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-berita-hero-container">
            <h1 class="ppic-berita-hero-title">
                <?php echo esc_html( $atts['title_normal'] ); ?> 
                <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
            </h1>
            
            <?php if ( ! empty( $atts['desc'] ) ) : ?>
                <p class="ppic-berita-hero-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_berita_hero_element' );
function ppic_register_berita_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Berita - Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_berita_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-megaphone', // Ikon pengumuman/berita
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Putih)', 'ppic-custom-element' ),
                    'param_name' => 'title_normal',
                    'value'      => 'BERITA &',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'KEGIATAN',
                    'description'=> __( 'Bagian teks ini akan diberi warna kuning.', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Update terkini seputar dunia penerbangan, inovasi kampus, siaran pers resmi, serta momen kebanggaan civitas PPI Curug.',
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