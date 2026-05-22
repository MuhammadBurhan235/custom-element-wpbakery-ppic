<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Daftarkan Elemen Timeline ke WPBakery
function ppic_register_timeline_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => __( "PPIC Timeline", "ppic" ),
            "base" => "ppic_timeline_section",
            "category" => __( "PPIC Elements", "ppic" ),
            "icon" => "icon-wpbakery", 
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __( "Judul Utama", "ppic" ),
                    "param_name" => "title",
                    "value" => 'Jejak Perjalanan <span class="text-yellow">PPI Curug</span>',
                    "admin_label" => true
                ),
                array(
                    "type" => "textarea",
                    "heading" => __( "Sub-judul / Deskripsi", "ppic" ),
                    "param_name" => "subtitle",
                    "value" => "Lebih dari 7 dekade mengukir prestasi dan melahirkan pemimpin aviasi Indonesia. Berikut adalah tonggak sejarah yang membentuk institusi kebanggaan kita.",
                ),
                // Param Group untuk item timeline (bisa diulang-ulang)
                array(
                    "type" => "param_group",
                    "heading" => __( "Daftar Sejarah (Timeline)", "ppic" ),
                    "param_name" => "timeline_items",
                    // TAMBAHKAN DI SINI (Di dalam array value)
                    "value" => urlencode( json_encode( array(
                        array(
                            "year" => "1952",
                            "item_title" => "Akademi Penerbangan Indonesia (API)",
                            "item_desc" => "Didirikan di Gempol-Kemayoran, Jakarta. Cikal bakal pendidikan penerbangan sipil tertua di Asia Tenggara."
                        ),
                        array(
                            "year" => "1954",
                            "item_title" => "Pindah ke Kampus \"Curug\"",
                            "item_desc" => "Dipindahkan ke Legok, Tangerang. Nama \"Curug\" melekat hingga kini."
                        ),
                        // --- Tambahan Baru Mulai Dari Sini ---
                        array(
                            "year" => "1969",
                            "item_title" => "Lembaga Pendidikan dan Pelatihan Udara (LPPU)",
                            "item_desc" => "" // Kosongkan jika tidak ada deskripsi
                        ),
                        array(
                            "year" => "1978",
                            "item_title" => "Pendidikan & Latihan Penerbangan (PLP)",
                            "item_desc" => ""
                        ),
                        array(
                            "year" => "2000",
                            "item_title" => "Sekolah Tinggi Penerbangan Indonesia (STPI)",
                            "item_desc" => ""
                        ),
                        array(
                            "year" => "2019 - sekarang",
                            "item_title" => "Politeknik Penerbangan Indonesia Curug",
                            "item_desc" => "10 program studi berstandar ICAO dan Kementerian Perhubungan."
                        )
                        
                    ) ) ),
                    "params" => array(
                        array(
                            "type" => "textfield",
                            "heading" => __( "Tahun", "ppic" ),
                            "param_name" => "year",
                            "admin_label" => true,
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => __( "Judul Peristiwa", "ppic" ),
                            "param_name" => "item_title",
                            "admin_label" => true,
                        ),
                        array(
                            "type" => "textarea",
                            "heading" => __( "Deskripsi Peristiwa", "ppic" ),
                            "param_name" => "item_desc",
                        )
                    )
                )
            )
        ) );
    }
}
add_action( 'vc_before_init', 'ppic_register_timeline_element' );

// 2. Render Output ke Frontend
function ppic_timeline_section_render( $atts ) {
    $atts = shortcode_atts( array(
        'title'          => 'Jejak Perjalanan <span class="text-yellow">PPI Curug</span>',
        'subtitle'       => 'Lebih dari 7 dekade mengukir prestasi dan melahirkan pemimpin aviasi Indonesia. Berikut adalah tonggak sejarah yang membentuk institusi kebanggaan kita.',
        'timeline_items' => '',
    ), $atts );

    // Decode param_group
    $timeline_items = vc_param_group_parse_atts( $atts['timeline_items'] );

    ob_start();
    ?>
    <div class="ppic-timeline-wrapper">
        <div class="ppic-timeline-header">
            <h2 class="ppic-timeline-title"><?php echo wp_kses_post( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-timeline-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>
        </div>

        <div class="ppic-timeline-container">
            <?php 
            if ( ! empty( $timeline_items ) && is_array( $timeline_items ) ) {
                foreach ( $timeline_items as $index => $item ) {
                    // Cek jika field kosong
                    $year = isset( $item['year'] ) ? $item['year'] : '';
                    $item_title = isset( $item['item_title'] ) ? $item['item_title'] : '';
                    $item_desc = isset( $item['item_desc'] ) ? $item['item_desc'] : '';
                    
                    ?>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h3 class="timeline-year"><?php echo esc_html( $year ); ?></h3>
                            <h4 class="timeline-item-title"><?php echo esc_html( $item_title ); ?></h4>
                            <p class="timeline-item-desc"><?php echo esc_html( $item_desc ); ?></p>
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
add_shortcode( 'ppic_timeline_section', 'ppic_timeline_section_render' );