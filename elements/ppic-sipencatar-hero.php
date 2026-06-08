<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Render Shortcode
add_shortcode( 'ppic_sipencatar_hero', 'ppic_sipencatar_hero_render' );
function ppic_sipencatar_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'bg_image'        => '',
            'title'           => 'Sipencatar',
            'title_highlight' => 'PPI Curug',
            'description'     => 'Sistem Penerimaan Calon Taruna (SIPENCATAR) Politeknik Penerbangan Indonesia Curug membuka kesempatan bagi putra-putri terbaik bangsa untuk mengikuti pendidikan vokasi penerbangan berstandar internasional. Pilih jalur yang sesuai dengan prestasi dan latar belakang Anda.',
            'btn1_text'       => 'Kembali ke Beranda',
            'btn1_link'       => '',
            'btn1_icon'       => 'fas fa-arrow-left',
            'btn2_text'       => 'Daftar Sekarang',
            'btn2_link'       => 'url:%23pendaftaran|||',
            'btn2_icon'       => 'fas fa-edit',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Parsing Link Tombol 1
    $link1   = function_exists( 'vc_build_link' ) ? vc_build_link( $atts['btn1_link'] ) : array( 'url' => home_url(), 'target' => '_self' );
    $href1   = ! empty( $link1['url'] ) ? $link1['url'] : home_url();
    $target1 = ! empty( $link1['target'] ) ? $link1['target'] : '_self';

    // Parsing Link Tombol 2
    $link2   = function_exists( 'vc_build_link' ) ? vc_build_link( $atts['btn2_link'] ) : array( 'url' => '#', 'target' => '_self' );
    $href2   = ! empty( $link2['url'] ) ? $link2['url'] : '#';
    $target2 = ! empty( $link2['target'] ) ? $link2['target'] : '_self';

    // Set Background Image dengan Overlay Gelap
    $bg_css = '';
    if ( ! empty( $atts['bg_image'] ) ) {
        $img_data = wp_get_attachment_image_src( $atts['bg_image'], 'full' );
        if ( $img_data ) {
            // Menambahkan linear gradient warna biru gelap PPI sebagai overlay agar teks putih tetap terbaca
            $bg_css = 'background-image: linear-gradient(rgba(11, 31, 56, 0.8), rgba(11, 31, 56, 0.8)), url(' . esc_url( $img_data[0] ) . ');';
        }
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-sipencatar-hero hero-page ' . esc_attr( $atts['el_class'] );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" style="<?php echo $bg_css; ?>">
        <div class="container">
            <h1><?php echo esc_html( $atts['title'] ); ?> <span><?php echo esc_html( $atts['title_highlight'] ); ?></span></h1>
            <p><?php echo esc_html( $atts['description'] ); ?></p>
            <div class="hero-buttons">
                <?php if ( ! empty( $atts['btn1_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $href1 ); ?>" target="<?php echo esc_attr( $target1 ); ?>" class="btn-back-home">
                        <?php if ( ! empty( $atts['btn1_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['btn1_icon'] ); ?>"></i> 
                        <?php endif; ?>
                        <?php echo esc_html( $atts['btn1_text'] ); ?>
                    </a>
                <?php endif; ?>

                <?php if ( ! empty( $atts['btn2_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $href2 ); ?>" target="<?php echo esc_attr( $target2 ); ?>" class="btn-primary-cta">
                        <?php if ( ! empty( $atts['btn2_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['btn2_icon'] ); ?>"></i> 
                        <?php endif; ?>
                        <?php echo esc_html( $atts['btn2_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// 2. Mapping ke WPBakery
add_action( 'vc_before_init', 'ppic_sipencatar_hero_map' );
function ppic_sipencatar_hero_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Sipencatar Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_sipencatar_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-cover-image',
            'params'   => array(
                array(
                    'type'       => 'attach_image',
                    'heading'    => __( 'Gambar Latar Belakang (Background)', 'ppic-custom-element' ),
                    'param_name' => 'bg_image',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Putih)', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Sipencatar',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'PPI Curug',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value'      => 'Sistem Penerimaan Calon Taruna (SIPENCATAR) Politeknik Penerbangan Indonesia Curug membuka kesempatan bagi putra-putri terbaik bangsa untuk mengikuti pendidikan vokasi penerbangan berstandar internasional. Pilih jalur yang sesuai dengan prestasi dan latar belakang Anda.',
                ),
                
                // Group: Tombol 1
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol 1 (Kiri/Transparan)', 'ppic-custom-element' ),
                    'param_name' => 'btn1_text',
                    'value'      => 'Kembali ke Beranda',
                    'group'      => 'Tombol 1',
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link Tombol 1 (opsional, defaultnya mengarah ke home_url())', 'ppic-custom-element' ),
                    'param_name' => 'btn1_link',
                    'description'=> __( 'Jika dikosongkan, tombol akan mengarah ke halaman beranda situs.', 'ppic-custom-element' ),
                    'group'      => 'Tombol 1',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tombol 1 (FontAwesome Class)', 'ppic-custom-element' ),
                    'param_name' => 'btn1_icon',
                    'value'      => 'fas fa-arrow-left',
                    'description'=> __( 'Kosongkan jika tidak ingin memakai ikon.', 'ppic-custom-element' ),
                    'group'      => 'Tombol 1',
                ),

                // Group: Tombol 2
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol 2 (Kanan/Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'btn2_text',
                    'value'      => 'Daftar Sekarang',
                    'group'      => 'Tombol 2',
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link Tombol 2', 'ppic-custom-element' ),
                    'param_name' => 'btn2_link',
                    'group'      => 'Tombol 2',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tombol 2 (FontAwesome Class)', 'ppic-custom-element' ),
                    'param_name' => 'btn2_icon',
                    'value'      => 'fas fa-edit',
                    'description'=> __( 'Kosongkan jika tidak ingin memakai ikon.', 'ppic-custom-element' ),
                    'group'      => 'Tombol 2',
                ),

                // Group: Extras
                array(
                    'type'       => 'el_id',
                    'heading'    => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                ),
            ),
        )
    );
}