<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_maklumat_standar', 'ppic_ppid_maklumat_standar_render' );
function ppic_ppid_maklumat_standar_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Maklumat Pelayanan & Standar Biaya',
            'desc'        => 'Dokumen resmi Maklumat Pelayanan dan Standar Biaya Layanan Informasi PPID PPI Curug',
            
            // KARTU 1: MAKLUMAT
            'c1_image'    => '',
            'c1_title'    => 'Maklumat Pelayanan',
            'c1_desc'     => 'Komitmen pelayanan informasi publik PPID PPI Curug',
            
            // KARTU 2: STANDAR BIAYA
            'c2_image'    => '',
            'c2_title'    => 'Standar Biaya Layanan',
            'c2_desc'     => 'Rincian biaya permintaan informasi sesuai regulasi',
            
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-maklumat-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // URL Gambar Fallback (Mengambil dari HTML yang Anda berikan)
    $fallback_img_1 = 'https://lh3.googleusercontent.com/d/1mLcrYOO57uZ_9XE6quKDt2tkjnru4q-e';
    $fallback_img_2 = 'https://lh3.googleusercontent.com/d/1YbD4A2kUp0J7MBTaLDnBFO1L2ZZPYjo0';

    // Parse Gambar 1
    $img1_url = $fallback_img_1;
    if ( ! empty( $atts['c1_image'] ) ) {
        $img1_src = wp_get_attachment_image_url( $atts['c1_image'], 'large' );
        if ( $img1_src ) $img1_url = $img1_src;
    }

    // Parse Gambar 2
    $img2_url = $fallback_img_2;
    if ( ! empty( $atts['c2_image'] ) ) {
        $img2_src = wp_get_attachment_image_url( $atts['c2_image'], 'large' );
        if ( $img2_src ) $img2_url = $img2_src;
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-maklumat-container">
            
            <div class="ppic-maklumat-header">
                <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <div class="title-accent"></div>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="ppic-maklumat-grid">
                
                <!-- Kartu 1: Maklumat -->
                <div class="maklumat-card">
                    <div class="maklumat-image">
                        <img src="<?php echo esc_url( $img1_url ); ?>" alt="<?php echo esc_attr( $atts['c1_title'] ); ?>" loading="lazy" />
                    </div>
                    <div class="maklumat-content">
                        <h3><?php echo esc_html( $atts['c1_title'] ); ?></h3>
                        <p><?php echo esc_html( $atts['c1_desc'] ); ?></p>
                    </div>
                </div>

                <!-- Kartu 2: Standar Biaya -->
                <div class="maklumat-card">
                    <div class="maklumat-image">
                        <img src="<?php echo esc_url( $img2_url ); ?>" alt="<?php echo esc_attr( $atts['c2_title'] ); ?>" loading="lazy" />
                    </div>
                    <div class="maklumat-content">
                        <h3><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                        <p><?php echo esc_html( $atts['c2_desc'] ); ?></p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_maklumat_standar_element' );
function ppic_register_ppid_maklumat_standar_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Layanan - Maklumat & Biaya', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_maklumat_standar',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-format-image',
            'params'   => array(
                // HEADER TEKS
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Maklumat Pelayanan & Standar Biaya',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi/Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Dokumen resmi Maklumat Pelayanan dan Standar Biaya Layanan Informasi PPID PPI Curug',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),

                // KARTU 1
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Maklumat', 'ppic-custom-element' ),
                    'param_name'  => 'c1_image',
                    'description' => __( 'Kosongkan jika ingin menggunakan gambar default dari Google Drive.', 'ppic-custom-element' ),
                    'group'       => __( 'Kartu 1 (Maklumat)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Maklumat Pelayanan',
                    'group'      => __( 'Kartu 1 (Maklumat)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_desc',
                    'value'      => 'Komitmen pelayanan informasi publik PPID PPI Curug',
                    'group'      => __( 'Kartu 1 (Maklumat)', 'ppic-custom-element' ),
                ),

                // KARTU 2
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Standar Biaya', 'ppic-custom-element' ),
                    'param_name'  => 'c2_image',
                    'description' => __( 'Kosongkan jika ingin menggunakan gambar default dari Google Drive.', 'ppic-custom-element' ),
                    'group'       => __( 'Kartu 2 (Biaya)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Standar Biaya Layanan',
                    'group'      => __( 'Kartu 2 (Biaya)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_desc',
                    'value'      => 'Rincian biaya permintaan informasi sesuai regulasi',
                    'group'      => __( 'Kartu 2 (Biaya)', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
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