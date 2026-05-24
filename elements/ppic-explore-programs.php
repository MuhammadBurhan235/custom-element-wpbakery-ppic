<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Daftarkan Elemen ke WPBakery
function ppic_register_explore_programs_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => __( "PPIC Explore Programs", "ppic" ),
            "base" => "ppic_explore_programs",
            "category" => __( "PPIC Elements", "ppic" ),
            "icon" => "dashicons dashicons-search", 
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __( "Judul Utama", "ppic" ),
                    "param_name" => "title",
                    "value" => "Explore Our Programs",
                    "admin_label" => true
                ),
                array(
                    "type" => "textarea",
                    "heading" => __( "Deskripsi Program", "ppic" ),
                    "param_name" => "description",
                    "value" => "Kurikulum berbasis kompetensi dan standar internasional ICAO, didukung fasilitas mutakhir, instruktur ahli, serta sertifikasi Kementerian Perhubungan. Lulusan memperoleh Ijazah + Sertifikat Kompetensi (Serkom) + Lisensi Profesi.",
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
add_action( 'vc_before_init', 'ppic_register_explore_programs_element' );

// 2. Render Output ke Frontend
function ppic_explore_programs_render( $atts ) {
    $atts = shortcode_atts( array(
        'title'       => 'Explore Our Programs',
        'description' => 'Kurikulum berbasis kompetensi dan standar internasional ICAO, didukung fasilitas mutakhir, instruktur ahli, serta sertifikasi Kementerian Perhubungan. Lulusan memperoleh Ijazah + Sertifikat Kompetensi (Serkom) + Lisensi Profesi.',
        'btn_text'    => 'Kembali ke Beranda',
        'btn_link'    => '',
    ), $atts );

    // Mengurai link dari WPBakery
    $link = vc_build_link( $atts['btn_link'] );
    $a_href = ! empty( $link['url'] ) ? $link['url'] : home_url(); 
    $a_target = ! empty( $link['target'] ) ? $link['target'] : '_self';

    ob_start();
    ?>
    <div class="ppic-explore-wrapper">
        <div class="ppic-explore-content">
            <h2 class="ppic-explore-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            
            <?php if ( ! empty( $atts['description'] ) ) : ?>
                <p class="ppic-explore-desc"><?php echo esc_html( $atts['description'] ); ?></p>
            <?php endif; ?>
            
            <a href="<?php echo esc_url( $a_href ); ?>" target="<?php echo esc_attr( $a_target ); ?>" class="ppic-explore-btn">
                &larr; <?php echo esc_html( $atts['btn_text'] ); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'ppic_explore_programs', 'ppic_explore_programs_render' );