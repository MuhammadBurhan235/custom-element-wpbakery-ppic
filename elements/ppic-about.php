<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Daftarkan Elemen ke WPBakery
function ppic_register_about_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => __( "PPIC About", "ppic" ),
            "base" => "ppic_about_section",
            "category" => __( "PPIC Elements", "ppic" ),
            "icon" => "dashicons dashicons-info-outline", 
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __( "Judul Utama", "ppic" ),
                    "param_name" => "title",
                    "value" => 'Tentang <span class="text-yellow">PPI Curug</span>',
                    "admin_label" => true,
                    "description" => __( "Biarkan tag <span> agar teks tetap berwarna kuning.", "ppic" )
                ),
                array(
                    "type" => "textarea",
                    "heading" => __( "Deskripsi", "ppic" ),
                    "param_name" => "description",
                    "value" => "Politeknik Penerbangan Indonesia Curug adalah perguruan tinggi kedinasan di bawah Kementerian Perhubungan yang berkomitmen mencetak sumber daya manusia unggul, profesional, dan berdaya saing global di bidang penerbangan.",
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Teks Tombol", "ppic" ),
                    "param_name" => "btn_text",
                    "value" => "Kembali ke Beranda"
                ),
                array(
                    "type" => "vc_link",
                    "heading" => __( "Link Tombol", "ppic" ),
                    "param_name" => "btn_link",
                    "description" => __( "Atur URL tujuan untuk tombol.", "ppic" )
                ),
            )
        ) );
    }
}
add_action( 'vc_before_init', 'ppic_register_about_element' );

// 2. Render Output ke Frontend
function ppic_about_section_render( $atts ) {
    $atts = shortcode_atts( array(
        'title'       => 'Tentang <span class="text-yellow">PPI Curug</span>',
        'description' => 'Politeknik Penerbangan Indonesia Curug adalah perguruan tinggi kedinasan di bawah Kementerian Perhubungan yang berkomitmen mencetak sumber daya manusia unggul, profesional, dan berdaya saing global di bidang penerbangan.',
        'btn_text'    => 'Kembali ke Beranda',
        'btn_link'    => '',
    ), $atts );

    // Mengurai link dari WPBakery
    $link = vc_build_link( $atts['btn_link'] );
    $a_href = ! empty( $link['url'] ) ? $link['url'] : home_url(); 
    $a_target = ! empty( $link['target'] ) ? $link['target'] : '_self';

    ob_start();
    ?>
    <div class="ppic-about-wrapper">
        <div class="ppic-about-content">
            <h2 class="ppic-about-title"><?php echo wp_kses_post( $atts['title'] ); ?></h2>
            
            <?php if ( ! empty( $atts['description'] ) ) : ?>
                <p class="ppic-about-desc"><?php echo esc_html( $atts['description'] ); ?></p>
            <?php endif; ?>
            
            <a href="<?php echo esc_url( $a_href ); ?>" target="<?php echo esc_attr( $a_target ); ?>" class="ppic-about-btn">
                <span class="btn-icon">&larr;</span> <?php echo esc_html( $atts['btn_text'] ); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'ppic_about_section', 'ppic_about_section_render' );