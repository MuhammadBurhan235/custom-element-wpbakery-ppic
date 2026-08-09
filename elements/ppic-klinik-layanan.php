<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_klinik_layanan', 'ppic_klinik_layanan_render' );
function ppic_klinik_layanan_render( $atts ) {
    
    // 1. DATA DUMMY SEBAGAI FALLBACK
    $dummy_services = array(
        array( 
            'icon'  => 'fas fa-stethoscope', 
            'title' => 'Pelayanan Umum', 
            'desc'  => 'Konsultasi medis umum, pemeriksaan kesehatan rutin, penanganan keluhan kesehatan ringan, serta pemberian rujukan ke layanan spesialis sesuai kebutuhan.' 
        ),
        array( 
            'icon'  => 'fas fa-tooth', 
            'title' => 'Pelayanan Gigi', 
            'desc'  => 'Perawatan gigi meliputi pemeriksaan, tambal gigi, pencabutan, scaling, serta penanganan keluhan kesehatan gigi dan mulut sesuai kebutuhan.' 
        ),
        array( 
            'icon'  => 'fas fa-prescription-bottle-alt', 
            'title' => 'Pelayanan Farmasi', 
            'desc'  => 'Penyediaan obat-obatan sesuai resep dokter, konsultasi penggunaan obat, serta pemberian informasi mengenai dosis dan aturan pakai yang tepat.' 
        ),
        array( 
            'icon'  => 'fas fa-heartbeat', 
            'title' => 'Medical Check Up (MCU)', 
            'desc'  => 'Layanan Medical Check Up (MCU) dilakukan untuk menilai kondisi kesehatan melalui pemeriksaan sesuai kebutuhan, meliputi:<br />-Pemeriksaan fisik<br />-Tes buta warna<br />-Tes narkoba (6 parameter)' 
        ),
        array( 
            'icon'  => 'fas fa-syringe', 
            'title' => 'Vaksinasi Internasional', 
            'desc'  => 'Layanan vaksinasi Meningitis, Polio, dan Influenza untuk memenuhi persyaratan perjalanan luar negeri serta membantu memberikan perlindungan terhadap penyakit menular.' 
        ),
        array( 
            'icon'  => 'fas fa-search-plus', 
            'title' => 'Skrining Kesehatan', 
            'desc'  => 'Skrining kesehatan meliputi pemeriksaan tekanan darah, gula darah, kolesterol, serta parameter kesehatan lainnya untuk deteksi dini kondisi kesehatan.' 
        ),
        array( 
            'icon'  => 'fas fa-ambulance', 
            'title' => 'Sewa Ambulans', 
            'desc'  => 'Layanan ambulans untuk rujukan pasien, transportasi medis, serta penanganan kondisi darurat dengan mengutamakan keamanan dan kenyamanan pasien.' 
        ),
    );

    $atts = shortcode_atts(
        array(
            'section_id'   => 'layanan',
            'title_black'  => 'Layanan',
            'title_yellow' => 'Klinik',
            'subtitle'     => 'Kami menyediakan berbagai layanan kesehatan untuk memenuhi kebutuhan medis taruna dan masyarakat sekitar.',
            'items'        => urlencode( wp_json_encode( $dummy_services ) ),
            'el_class'     => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-services-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater layanan
    $service_items = vc_param_group_parse_atts( $atts['items'] );
    
    // Logika Fallback
    $has_valid_item = false;
    if ( is_array( $service_items ) ) {
        foreach ( $service_items as $item ) {
            if ( ! empty( $item['title'] ) ) {
                $has_valid_item = true;
                break;
            }
        }
    }
    if ( ! $has_valid_item ) {
        $service_items = $dummy_services;
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-services-container">
            
            <div class="ppic-services-header">
                <h2 class="ppic-services-title">
                    <?php echo esc_html( $atts['title_black'] ); ?> <span><?php echo esc_html( $atts['title_yellow'] ); ?></span>
                </h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-services-subtitle">
                        <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="ppic-services-grid">
                <?php foreach ( $service_items as $item ) : 
                    $icon  = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-stethoscope';
                    $title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                    $desc  = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                    
                    if ( empty( $title ) ) continue;
                ?>
                    <div class="ppic-service-card">
                        <div class="service-icon-wrap">
                            <i class="<?php echo esc_attr( $icon ); ?>"></i>
                        </div>
                        <h4><?php echo esc_html( $title ); ?></h4>
                        <p><?php echo wp_kses_post( $desc ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_klinik_layanan_element' );
function ppic_register_klinik_layanan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_services = array(
        array( 'icon' => 'fas fa-stethoscope', 'title' => 'Pelayanan Umum', 'desc' => 'Konsultasi medis umum, pemeriksaan kesehatan rutin, penanganan keluhan kesehatan ringan, serta pemberian rujukan ke layanan spesialis sesuai kebutuhan.' ),
        array( 'icon' => 'fas fa-tooth', 'title' => 'Pelayanan Gigi', 'desc' => 'Perawatan gigi meliputi pemeriksaan, tambal gigi, pencabutan, scaling, serta penanganan keluhan kesehatan gigi dan mulut sesuai kebutuhan.' ),
        array( 'icon' => 'fas fa-prescription-bottle-alt', 'title' => 'Pelayanan Farmasi', 'desc' => 'Penyediaan obat-obatan sesuai resep dokter, konsultasi penggunaan obat, serta pemberian informasi mengenai dosis dan aturan pakai yang tepat.' ),
        array( 'icon' => 'fas fa-heartbeat', 'title' => 'Medical Check Up (MCU)', 'desc' => 'Layanan Medical Check Up (MCU) dilakukan untuk menilai kondisi kesehatan melalui pemeriksaan sesuai kebutuhan, meliputi:<br />-Pemeriksaan fisik<br />-Tes buta warna<br />-Tes narkoba (6 parameter)' ),
        array( 'icon' => 'fas fa-syringe', 'title' => 'Vaksinasi Internasional', 'desc' => 'Layanan vaksinasi Meningitis, Polio, dan Influenza untuk memenuhi persyaratan perjalanan luar negeri serta membantu memberikan perlindungan terhadap penyakit menular.' ),
        array( 'icon' => 'fas fa-search-plus', 'title' => 'Skrining Kesehatan', 'desc' => 'Skrining kesehatan meliputi pemeriksaan tekanan darah, gula darah, kolesterol, serta parameter kesehatan lainnya untuk deteksi dini kondisi kesehatan.' ),
        array( 'icon' => 'fas fa-ambulance', 'title' => 'Sewa Ambulans', 'desc' => 'Layanan ambulans untuk rujukan pasien, transportasi medis, serta penanganan kondisi darurat dengan mengutamakan keamanan dan kenyamanan pasien.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Klinik Layanan', 'ppic-custom-element' ),
            'base'     => 'ppic_klinik_layanan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-plus-alt',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'layanan',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul (Hitam)', 'ppic-custom-element' ),
                    'param_name'  => 'title_black',
                    'value'       => 'Layanan',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul (Kuning)', 'ppic-custom-element' ),
                    'param_name'  => 'title_yellow',
                    'value'       => 'Klinik',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Subtitle', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Kami menyediakan berbagai layanan kesehatan untuk memenuhi kebutuhan medis taruna dan masyarakat sekitar.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Layanan', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_services ) ),
                    'group'       => __( 'Layanan', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textfield', 'heading' => 'Nama Layanan', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon', 'value' => 'fas fa-stethoscope'),
                        array('type' => 'textarea', 'heading' => 'Deskripsi Layanan', 'param_name' => 'desc', 'description' => 'Gunakan tag &lt;br /&gt; untuk enter/baris baru.'),
                    ),
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