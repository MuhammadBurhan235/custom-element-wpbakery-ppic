<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_layanan_informasi', 'ppic_ppid_layanan_informasi_render' );
function ppic_ppid_layanan_informasi_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'          => 'Layanan Informasi Publik',
            'subtitle'       => 'Kami menyediakan mekanisme pelayanan informasi yang mudah, sistematis, dan transparan. Proses pengajuan dirancang agar sederhana dan dapat diakses oleh seluruh masyarakat.',
            'card1_icon'     => 'fas fa-clock',
            'card1_title'    => 'Jadwal Pelayanan Informasi',
            'card1_content'  => "<p><strong>Senin - Kamis</strong><br />09.00 - 16.00 WIB<br />Istirahat 12.00 - 13.00 WIB</p><br /><p><strong>Jum’at</strong><br />09.00 - 16.00 WIB<br />Istirahat 11.30 - 13.00 WIB</p><br /><p><strong>Sabtu, Minggu, Hari Besar, Cuti Bersama & Libur Nasional</strong><br />Pelayanan Tutup</p>",
            'card2_icon'     => 'fas fa-link',
            'card2_title'    => 'Pranala Penting',
            'card2_links'    => '',
            'card2_btn_text' => 'Ajukan Permintaan Sekarang',
            'card2_btn_url'  => '#',
            'el_id'          => '',
            'el_class'       => '',
        ),
        $atts
    );

    // Proses daftar tautan Pranala Penting
    $links = vc_param_group_parse_atts( $atts['card2_links'] );

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-ppid-layanan-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-ppid-layanan-container">
            <div class="ppic-ppid-layanan-header">
                <h2 class="ppic-ppid-layanan-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-ppid-layanan-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="ppic-ppid-layanan-grid">
                <div class="ppic-ppid-layanan-card">
                    <div class="ppic-ppid-layanan-icon">
                        <i class="<?php echo esc_attr( $atts['card1_icon'] ); ?>" aria-hidden="true"></i>
                    </div>
                    <h3><?php echo esc_html( $atts['card1_title'] ); ?></h3>
                    <div class="ppic-ppid-layanan-content">
                        <?php 
                        // Menggunakan wp_kses_post agar tag HTML dasar seperti <p>, <br>, <strong> tetap ter-render
                        echo wp_kses_post( $atts['card1_content'] ); 
                        ?>
                    </div>
                </div>

                <div class="ppic-ppid-layanan-card">
                    <div class="ppic-ppid-layanan-icon">
                        <i class="<?php echo esc_attr( $atts['card2_icon'] ); ?>" aria-hidden="true"></i>
                    </div>
                    <h3><?php echo esc_html( $atts['card2_title'] ); ?></h3>
                    
                    <?php if ( ! empty( $links ) && is_array( $links ) ) : ?>
                        <ul class="ppic-ppid-layanan-links">
                            <?php foreach ( $links as $link ) : 
                                $label = isset( $link['label'] ) ? trim( $link['label'] ) : '';
                                $url   = isset( $link['url'] ) && ! empty( $link['url'] ) ? trim( $link['url'] ) : '#';
                                
                                if ( empty( $label ) ) continue;
                                ?>
                                <li>
                                    <a href="<?php echo esc_url( $url ); ?>">
                                        <i class="fas fa-angle-right" aria-hidden="true"></i> <?php echo esc_html( $label ); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ( ! empty( $atts['card2_btn_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $atts['card2_btn_url'] ); ?>" class="ppic-ppid-layanan-btn">
                            <?php echo esc_html( $atts['card2_btn_text'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_layanan_informasi_element' );
function ppic_register_ppid_layanan_informasi_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_links = array(
        array( 'label' => 'Standar Biaya Layanan', 'url' => '#' ),
        array( 'label' => 'Maklumat Pelayanan', 'url' => '#' ),
        array( 'label' => 'Alur Pelayanan', 'url' => '#' ),
        array( 'label' => 'Ajukan Informasi', 'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSc3DdIFtG2bNPwmAVHNUyucoZ5bhLhep9zcZiKpJptAjye5LA/viewform' ),
        array( 'label' => 'Ajukan Keberatan', 'url' => '#' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Layanan Info', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_layanan_informasi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-info',
            'params'   => array(
                // Header Section
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Layanan Informasi Publik',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Kami menyediakan mekanisme pelayanan informasi yang mudah, sistematis, dan transparan. Proses pengajuan dirancang agar sederhana dan dapat diakses oleh seluruh masyarakat.',
                ),
                // Card 1
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Card 1', 'ppic-custom-element' ),
                    'param_name' => 'card1_icon',
                    'value'      => 'fas fa-clock',
                    'group'      => __( 'Card 1 (Jadwal)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Card 1', 'ppic-custom-element' ),
                    'param_name' => 'card1_title',
                    'value'      => 'Jadwal Pelayanan Informasi',
                    'group'      => __( 'Card 1 (Jadwal)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Isi Konten Card 1 (Dukung HTML dasar)', 'ppic-custom-element' ),
                    'param_name' => 'card1_content',
                    'value'      => "<p><strong>Senin - Kamis</strong><br />09.00 - 16.00 WIB<br />Istirahat 12.00 - 13.00 WIB</p><br /><p><strong>Jum’at</strong><br />09.00 - 16.00 WIB<br />Istirahat 11.30 - 13.00 WIB</p><br /><p><strong>Sabtu, Minggu, Hari Besar, Cuti Bersama & Libur Nasional</strong><br />Pelayanan Tutup</p>",
                    'group'      => __( 'Card 1 (Jadwal)', 'ppic-custom-element' ),
                ),
                // Card 2
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Card 2', 'ppic-custom-element' ),
                    'param_name' => 'card2_icon',
                    'value'      => 'fas fa-link',
                    'group'      => __( 'Card 2 (Pranala)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Card 2', 'ppic-custom-element' ),
                    'param_name' => 'card2_title',
                    'value'      => 'Pranala Penting',
                    'group'      => __( 'Card 2 (Pranala)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Tautan (Quick Links)', 'ppic-custom-element' ),
                    'param_name' => 'card2_links',
                    'value'      => urlencode( wp_json_encode( $dummy_links ) ),
                    'group'      => __( 'Card 2 (Pranala)', 'ppic-custom-element' ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Label Tautan', 'ppic-custom-element' ),
                            'param_name'  => 'label',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Tautan', 'ppic-custom-element' ),
                            'param_name' => 'url',
                            'value'      => '#',
                        ),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'card2_btn_text',
                    'value'      => 'Ajukan Permintaan Sekarang',
                    'group'      => __( 'Card 2 (Pranala)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'URL Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'card2_btn_url',
                    'value'      => 'https://docs.google.com/forms/d/e/1FAIpQLSc3DdIFtG2bNPwmAVHNUyucoZ5bhLhep9zcZiKpJptAjye5LA/viewform',
                    'group'      => __( 'Card 2 (Pranala)', 'ppic-custom-element' ),
                ),
                // General Settings
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}