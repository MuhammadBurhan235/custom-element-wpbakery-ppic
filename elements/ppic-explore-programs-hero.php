<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Daftarkan Elemen ke WPBakery
function ppic_register_explore_programs_hero_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => __( "PPIC Explore Programs Hero", "ppic" ),
            "base" => "ppic_explore_programs_hero",
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
                array(
                    "type" => "el_id",
                    "heading" => __( "Element ID", "js_composer" ),
                    "param_name" => "el_id",
                    "description" => __( "Enter element ID (Note: make sure it is unique and valid according to w3c specification).", "js_composer" )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Extra class name", "js_composer" ),
                    "param_name" => "el_class",
                    "description" => __( "Style particular content element differently by adding a class name and referring to it in custom CSS.", "js_composer" )
                ),
            )
        ) );
    }
}
add_action( 'vc_before_init', 'ppic_register_explore_programs_hero_element' );

// 2. Render Output ke Frontend
function ppic_explore_programs_hero_render( $atts ) {
    $atts = shortcode_atts( array(
        'title'       => 'Explore Our Programs',
        'description' => 'Kurikulum berbasis kompetensi dan standar internasional ICAO, didukung fasilitas mutakhir, instruktur ahli, serta sertifikasi Kementerian Perhubungan. Lulusan memperoleh Ijazah + Sertifikat Kompetensi (Serkom) + Lisensi Profesi.',
        'btn_text'    => 'Kembali ke Beranda',
        'btn_link'    => '',
        'el_id'       => '',
        'el_class'    => '',
    ), $atts );

    // Mengurai link dari WPBakery
    $link = vc_build_link( $atts['btn_link'] );
    $a_href = ! empty( $link['url'] ) ? $link['url'] : home_url(); 
    $a_target = ! empty( $link['target'] ) ? $link['target'] : '_self';
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-explore-wrapper' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <div<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
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
add_shortcode( 'ppic_explore_programs_hero', 'ppic_explore_programs_hero_render' );