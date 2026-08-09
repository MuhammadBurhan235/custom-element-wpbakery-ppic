<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_klinik_fasilitas', 'ppic_klinik_fasilitas_render' );
function ppic_klinik_fasilitas_render( $atts ) {
    
    // 1. DATA DUMMY SEBAGAI FALLBACK
    $dummy_facilities = array(
        array( 
            'fallback_img' => 'https://placehold.co/600x300/003153/FFFFFF?text=Poli+Umum',
            'icon'         => 'fas fa-stethoscope', 
            'title'        => 'Poli Umum', 
            'desc'         => 'Ruang konsultasi dokter umum yang nyaman.' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/600x300/1a4a76/FFFFFF?text=Poli+Gigi',
            'icon'         => 'fas fa-tooth', 
            'title'        => 'Poli Gigi', 
            'desc'         => 'Ruang perawatan gigi dengan peralatan lengkap.' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/600x300/0b1a2f/FFFFFF?text=Ruang+Tindakan',
            'icon'         => 'fas fa-briefcase-medical', 
            'title'        => 'Ruang Tindakan', 
            'desc'         => 'Ruang steril untuk tindakan medis ringan.' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/600x300/f5b01b/003153?text=Instalasi+Farmasi',
            'icon'         => 'fas fa-prescription-bottle-alt', 
            'title'        => 'Instalasi Farmasi', 
            'desc'         => 'Ketersediaan obat-obatan yang lengkap dan terjamin.' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/600x300/003153/FFFFFF?text=Armada+Ambulans',
            'icon'         => 'fas fa-ambulance', 
            'title'        => 'Armada Ambulans', 
            'desc'         => 'Ambulans siaga 24 jam untuk darurat dan transportasi.' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/600x300/1a4a76/FFFFFF?text=Ruang+Tunggu',
            'icon'         => 'fas fa-chair', 
            'title'        => 'Ruang Tunggu', 
            'desc'         => 'Ruang tunggu yang nyaman dan ber-AC.' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/600x300/0b1a2f/FFFFFF?text=Ruang+Laktasi',
            'icon'         => 'fas fa-baby', 
            'title'        => 'Ruang Laktasi', 
            'desc'         => 'Fasilitas khusus untuk ibu menyusui.' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/600x300/f5b01b/003153?text=Ruang+Gym',
            'icon'         => 'fas fa-dumbbell', 
            'title'        => 'Ruang Gym', 
            'desc'         => 'Fasilitas olahraga untuk mendukung kesehatan fisik.' 
        ),
    );

    $atts = shortcode_atts(
        array(
            'section_id'   => 'fasilitas',
            'title_black'  => 'Fasilitas',
            'title_yellow' => 'Klinik',
            'subtitle'     => 'Fasilitas modern yang mendukung kenyamanan dan kualitas pelayanan kesehatan.',
            'items'        => urlencode( wp_json_encode( $dummy_facilities ) ),
            'el_class'     => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-facility-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater fasilitas
    $facility_items = vc_param_group_parse_atts( $atts['items'] );
    
    // Logika Fallback
    $has_valid_item = false;
    if ( is_array( $facility_items ) ) {
        foreach ( $facility_items as $item ) {
            if ( ! empty( $item['title'] ) ) {
                $has_valid_item = true;
                break;
            }
        }
    }
    if ( ! $has_valid_item ) {
        $facility_items = $dummy_facilities;
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-facility-container">
            
            <div class="ppic-facility-header">
                <h2 class="ppic-facility-title">
                    <?php echo esc_html( $atts['title_black'] ); ?> <span><?php echo esc_html( $atts['title_yellow'] ); ?></span>
                </h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-facility-subtitle">
                        <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="ppic-facility-grid">
                <?php foreach ( $facility_items as $item ) : 
                    $title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                    $desc  = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                    $icon  = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-building';
                    
                    if ( empty( $title ) ) continue;

                    // Pengaturan Gambar (Prioritas upload, fallback ke URL dummy)
                    $img_url = isset( $item['fallback_img'] ) ? $item['fallback_img'] : 'https://placehold.co/600x300/003153/FFFFFF?text=' . urlencode($title);
                    if ( ! empty( $item['image'] ) ) {
                        $uploaded_img = wp_get_attachment_image_url( $item['image'], 'medium_large' );
                        if ( $uploaded_img ) {
                            $img_url = $uploaded_img;
                        }
                    }
                ?>
                    <div class="ppic-facility-card">
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
                        <div class="ppic-facility-content">
                            <i class="<?php echo esc_attr( $icon ); ?>"></i>
                            <h4><?php echo esc_html( $title ); ?></h4>
                            <p><?php echo wp_kses_post( $desc ); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_klinik_fasilitas_element' );
function ppic_register_klinik_fasilitas_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_facilities = array(
        array( 'fallback_img' => 'https://placehold.co/600x300/003153/FFFFFF?text=Poli+Umum', 'icon' => 'fas fa-stethoscope', 'title' => 'Poli Umum', 'desc' => 'Ruang konsultasi dokter umum yang nyaman.' ),
        array( 'fallback_img' => 'https://placehold.co/600x300/1a4a76/FFFFFF?text=Poli+Gigi', 'icon' => 'fas fa-tooth', 'title' => 'Poli Gigi', 'desc' => 'Ruang perawatan gigi dengan peralatan lengkap.' ),
        array( 'fallback_img' => 'https://placehold.co/600x300/0b1a2f/FFFFFF?text=Ruang+Tindakan', 'icon' => 'fas fa-briefcase-medical', 'title' => 'Ruang Tindakan', 'desc' => 'Ruang steril untuk tindakan medis ringan.' ),
        array( 'fallback_img' => 'https://placehold.co/600x300/f5b01b/003153?text=Instalasi+Farmasi', 'icon' => 'fas fa-prescription-bottle-alt', 'title' => 'Instalasi Farmasi', 'desc' => 'Ketersediaan obat-obatan yang lengkap dan terjamin.' ),
        array( 'fallback_img' => 'https://placehold.co/600x300/003153/FFFFFF?text=Armada+Ambulans', 'icon' => 'fas fa-ambulance', 'title' => 'Armada Ambulans', 'desc' => 'Ambulans siaga 24 jam untuk darurat dan transportasi.' ),
        array( 'fallback_img' => 'https://placehold.co/600x300/1a4a76/FFFFFF?text=Ruang+Tunggu', 'icon' => 'fas fa-chair', 'title' => 'Ruang Tunggu', 'desc' => 'Ruang tunggu yang nyaman dan ber-AC.' ),
        array( 'fallback_img' => 'https://placehold.co/600x300/0b1a2f/FFFFFF?text=Ruang+Laktasi', 'icon' => 'fas fa-baby', 'title' => 'Ruang Laktasi', 'desc' => 'Fasilitas khusus untuk ibu menyusui.' ),
        array( 'fallback_img' => 'https://placehold.co/600x300/f5b01b/003153?text=Ruang+Gym', 'icon' => 'fas fa-dumbbell', 'title' => 'Ruang Gym', 'desc' => 'Fasilitas olahraga untuk mendukung kesehatan fisik.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Klinik Fasilitas', 'ppic-custom-element' ),
            'base'     => 'ppic_klinik_fasilitas',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-building',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'fasilitas',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul (Hitam)', 'ppic-custom-element' ),
                    'param_name'  => 'title_black',
                    'value'       => 'Fasilitas',
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
                    'value'       => 'Fasilitas modern yang mendukung kenyamanan dan kualitas pelayanan kesehatan.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Fasilitas', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_facilities ) ),
                    'group'       => __( 'Fasilitas', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'attach_image', 'heading' => 'Foto Fasilitas', 'param_name' => 'image'),
                        array('type' => 'textfield', 'heading' => 'Fallback Image URL (Abaikan jika upload foto)', 'param_name' => 'fallback_img'),
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon', 'value' => 'fas fa-stethoscope'),
                        array('type' => 'textfield', 'heading' => 'Nama Fasilitas', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textarea', 'heading' => 'Deskripsi Singkat', 'param_name' => 'desc'),
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