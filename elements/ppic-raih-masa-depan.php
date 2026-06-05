<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Render Shortcode
add_shortcode( 'ppic_raih_masa_depan', 'ppic_raih_masa_depan_render' );
function ppic_raih_masa_depan_render( $atts ) {
    
    // Data Default 6 Keunggulan
    $default_items = urlencode( wp_json_encode( array(
        array( 'icon_class' => 'fas fa-globe-asia', 'item_title' => 'Standar Internasional ICAO', 'item_desc' => 'Kurikulum & sertifikasi diakui global, lulusan siap bersaing di pasar regional dan dunia.' ),
        array( 'icon_class' => 'fas fa-laptop-code', 'item_title' => 'Fasilitas Simulator Canggih', 'item_desc' => 'Full-flight simulator, ATC simulator, laboratorium avionik terdepan untuk pengalaman nyata.' ),
        array( 'icon_class' => 'fas fa-chart-line', 'item_title' => 'Serapan Alumni Tinggi', 'item_desc' => '95% alumni bekerja di maskapai, otoritas bandara, dan industri strategis dalam 1 tahun.' ),
        array( 'icon_class' => 'fas fa-award', 'item_title' => 'Akreditasi Unggul', 'item_desc' => 'Program studi terakreditasi BAN-PT dan memenuhi standar Kementerian Perhubungan.' ),
        array( 'icon_class' => 'fas fa-handshake', 'item_title' => 'Kemitraan Strategis', 'item_desc' => 'Kerja sama dengan maskapai internasional, ICAO, dan otoritas penerbangan global.' ),
        array( 'icon_class' => 'fas fa-chalkboard-teacher', 'item_title' => 'Dosen Profesional & Praktisi', 'item_desc' => 'Pengajar berpengalaman dari industri penerbangan, berlisensi & bersertifikasi tinggi.' ),
    ) ) );

    $atts = shortcode_atts(
        array(
            'title'           => 'Raih Masa Depan Cemerlang di',
            'title_highlight' => 'PPI Curug',
            'subtitle'        => 'Bukan sekadar kampus kedinasan, melainkan wadah pembentuk profesional aviasi kelas dunia. Temukan 8+ alasan mengapa ratusan calon penerbang, teknisi, dan pakar bandara memilih kami setiap tahun.',
            'reasons_list'    => $default_items,
            'btn_text'        => 'Jelajahi Keunggulan PPIC Curug',
            'btn_link'        => '',
            'info_text'       => 'Temukan informasi lengkap mengenai kehidupan kampus, biaya, beasiswa, prestasi, dan peluang karir di halaman berikutnya.',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Parse Data
    $items = array();
    if ( ! empty( $atts['reasons_list'] ) ) {
        $items = vc_param_group_parse_atts( $atts['reasons_list'] );
    }

    // Parse Link
    $link = function_exists('vc_build_link') ? vc_build_link( $atts['btn_link'] ) : array( 'url' => '', 'title' => '', 'target' => '' );
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_target = ! empty( $link['target'] ) ? $link['target'] : '_self';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'raih-masa-depan-section ' . esc_attr( $atts['el_class'] );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container">
            <div class="rmd-inner-box">
                <!-- Quote Icon Top -->
                <div class="rmd-quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                
                <!-- Header -->
                <div class="rmd-header">
                    <h2><?php echo esc_html( $atts['title'] ); ?> <span><?php echo esc_html( $atts['title_highlight'] ); ?></span></h2>
                    <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                        <p class="rmd-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Keunggulan Grid -->
                <?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
                    <div class="rmd-grid">
                        <?php foreach ( $items as $item ) : ?>
                            <?php 
                            $icon_class = ! empty( $item['icon_class'] ) ? $item['icon_class'] : 'fas fa-check';
                            $title = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
                            $desc  = ! empty( $item['item_desc'] ) ? $item['item_desc'] : '';
                            ?>
                            <div class="rmd-card">
                                <div class="rmd-icon">
                                    <i class="<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></i>
                                </div>
                                <div class="rmd-text">
                                    <h4><?php echo esc_html( $title ); ?></h4>
                                    <?php if ( ! empty( $desc ) ) : ?>
                                        <p><?php echo esc_html( $desc ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Call To Action Bottom -->
                <div class="rmd-cta-wrapper">
                    <a href="<?php echo esc_url( $a_href ); ?>" target="<?php echo esc_attr( $a_target ); ?>" class="rmd-cta-btn">
                        <i class="fas fa-rocket"></i> <?php echo esc_html( $atts['btn_text'] ); ?> <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    </a>
                    <?php if ( ! empty( $atts['info_text'] ) ) : ?>
                        <p class="rmd-info-text"><i class="fas fa-info-circle"></i> <?php echo esc_html( $atts['info_text'] ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// 2. Mapping ke WPBakery
add_action( 'vc_before_init', 'ppic_raih_masa_depan_map' );
function ppic_raih_masa_depan_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $default_items = urlencode( wp_json_encode( array(
        array( 'icon_class' => 'fas fa-globe-asia', 'item_title' => 'Standar Internasional ICAO', 'item_desc' => 'Kurikulum & sertifikasi diakui global, lulusan siap bersaing di pasar regional dan dunia.' ),
        array( 'icon_class' => 'fas fa-laptop-code', 'item_title' => 'Fasilitas Simulator Canggih', 'item_desc' => 'Full-flight simulator, ATC simulator, laboratorium avionik terdepan untuk pengalaman nyata.' ),
        array( 'icon_class' => 'fas fa-chart-line', 'item_title' => 'Serapan Alumni Tinggi', 'item_desc' => '95% alumni bekerja di maskapai, otoritas bandara, dan industri strategis dalam 1 tahun.' ),
        array( 'icon_class' => 'fas fa-award', 'item_title' => 'Akreditasi Unggul', 'item_desc' => 'Program studi terakreditasi BAN-PT dan memenuhi standar Kementerian Perhubungan.' ),
        array( 'icon_class' => 'fas fa-handshake', 'item_title' => 'Kemitraan Strategis', 'item_desc' => 'Kerja sama dengan maskapai internasional, ICAO, dan otoritas penerbangan global.' ),
        array( 'icon_class' => 'fas fa-chalkboard-teacher', 'item_title' => 'Dosen Profesional & Praktisi', 'item_desc' => 'Pengajar berpengalaman dari industri penerbangan, berlisensi & bersertifikasi tinggi.' ),
    ) ) );

    vc_map(
        array(
            'name'     => __( 'PPIC Raih Masa Depan', 'ppic-custom-element' ),
            'base'     => 'ppic_raih_masa_depan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-star-filled',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Hitam)', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Raih Masa Depan Cemerlang di',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'PPI Curug',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Bukan sekadar kampus kedinasan, melainkan wadah pembentuk profesional aviasi kelas dunia. Temukan 8+ alasan mengapa ratusan calon penerbang, teknisi, dan pakar bandara memilih kami setiap tahun.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Keunggulan', 'ppic-custom-element' ),
                    'param_name' => 'reasons_list',
                    'value'      => $default_items,
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value'      => 'fas fa-check',
                            'description'=> __( 'Contoh: fas fa-globe-asia', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Judul Keunggulan', 'ppic-custom-element' ),
                            'param_name'  => 'item_title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                            'param_name' => 'item_desc',
                        ),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Jelajahi Keunggulan PPIC Curug',
                    'group'      => 'Tombol & Info',
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Tautan Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                    'group'      => 'Tombol & Info',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Teks Info Bawah', 'ppic-custom-element' ),
                    'param_name' => 'info_text',
                    'value'      => 'Temukan informasi lengkap mengenai kehidupan kampus, biaya, beasiswa, prestasi, dan peluang karir di halaman berikutnya.',
                    'group'      => 'Tombol & Info',
                ),
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