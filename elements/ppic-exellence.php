<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Daftarkan Elemen Excellence ke WPBakery
function ppic_register_excellence_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => __( "PPIC Excellence Values", "ppic" ),
            "base" => "ppic_excellence_section",
            "category" => __( "PPIC Elements", "ppic" ),
            "icon" => "dashicons dashicons-star-filled", 
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __( "Judul Utama", "ppic" ),
                    "param_name" => "title",
                    "value" => 'Nilai-Nilai Luhur <span class="text-yellow">EXCELLENCE</span>',
                    "admin_label" => true
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Sub-judul", "ppic" ),
                    "param_name" => "subtitle",
                    "value" => '"Cewama Eka Tayai" — Mengabdi Untuk Kesatuan',
                ),
                // Param Group untuk kotak-kotak nilai
                array(
                    "type" => "param_group",
                    "heading" => __( "Daftar Nilai (EXCELLENCE)", "ppic" ),
                    "param_name" => "excellence_items",
                    "value" => urlencode( json_encode( array(
                        array( "letter" => "E", "item_title" => "Knowledge", "item_desc" => "Pengetahuan untuk sistem penerbangan aman, selamat." ),
                        array( "letter" => "X", "item_title" => "Experience", "item_desc" => "Pengalaman menjawab tantangan global." ),
                        array( "letter" => "C", "item_title" => "Service", "item_desc" => "Pelayanan prima." ),
                        array( "letter" => "E", "item_title" => "Leadership", "item_desc" => "Menggerakkan organisasi dengan visi." ),
                        array( "letter" => "L", "item_title" => "Collaborative", "item_desc" => "Jejaring kuat." ),
                        array( "letter" => "L", "item_title" => "Loyalty", "item_desc" => "Kesetiaan pada institusi." ),
                        array( "letter" => "E", "item_title" => "Creative", "item_desc" => "Terobosan pemecahan masalah." ),
                        array( "letter" => "N", "item_title" => "Innovative", "item_desc" => "Ide dan gagasan unggul." ),
                        array( "letter" => "C", "item_title" => "Communicative", "item_desc" => "Komunikasi aktif & konstruktif." ),
                        array( "letter" => "E", "item_title" => "Adaptive", "item_desc" => "Mampu menyesuaikan diri dengan perubahan." ) // Saya lengkapi kalimatnya
                    ) ) ),
                    "params" => array(
                        array(
                            "type" => "textfield",
                            "heading" => __( "Huruf Ikon", "ppic" ),
                            "param_name" => "letter",
                            "admin_label" => true,
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => __( "Judul Nilai", "ppic" ),
                            "param_name" => "item_title",
                            "admin_label" => true,
                        ),
                        array(
                            "type" => "textarea",
                            "heading" => __( "Deskripsi", "ppic" ),
                            "param_name" => "item_desc",
                        )
                    )
                )
            )
        ) );
    }
}
add_action( 'vc_before_init', 'ppic_register_excellence_element' );

// 2. Render Output ke Frontend
function ppic_excellence_section_render( $atts ) {
    $atts = shortcode_atts( array(
        'title'            => 'Nilai-Nilai Luhur <span class="text-yellow">EXCELLENCE</span>',
        'subtitle'         => '"Cewama Eka Tayai" — Mengabdi Untuk Kesatuan',
        'excellence_items' => '',
    ), $atts );

    $excellence_items = vc_param_group_parse_atts( $atts['excellence_items'] );

    ob_start();
    ?>
    <div class="ppic-exc-wrapper">
        <div class="ppic-exc-header">
            <h2 class="ppic-exc-title"><?php echo wp_kses_post( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-exc-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>
        </div>

        <div class="ppic-exc-grid">
            <?php 
            if ( ! empty( $excellence_items ) && is_array( $excellence_items ) ) {
                foreach ( $excellence_items as $item ) {
                    $letter = isset( $item['letter'] ) ? $item['letter'] : '';
                    $item_title = isset( $item['item_title'] ) ? $item['item_title'] : '';
                    $item_desc = isset( $item['item_desc'] ) ? $item['item_desc'] : '';
                    ?>
                    <div class="exc-card">
                        <div class="exc-icon-box">
                            <?php echo esc_html( $letter ); ?>
                        </div>
                        <div class="exc-content">
                            <h4 class="exc-item-title"><?php echo esc_html( $item_title ); ?></h4>
                            <p class="exc-item-desc"><?php echo esc_html( $item_desc ); ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'ppic_excellence_section', 'ppic_excellence_section_render' );