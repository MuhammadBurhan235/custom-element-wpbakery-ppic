<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_akuntabilitas_pelaporan', 'ppic_ppid_akuntabilitas_pelaporan_render' );
function ppic_ppid_akuntabilitas_pelaporan_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'      => 'Akuntabilitas dan Pelaporan',
            'subtitle'   => 'Kami menyampaikan laporan layanan informasi publik secara transparan sebagai bentuk pertanggungjawaban kepada masyarakat.',
            'btn_text'   => 'Lihat Laporan Layanan Informasi Publik',
            'btn_url'    => '#',
            'card_icon'  => 'fas fa-chart-bar',
            'card_title' => 'Laporan Tahunan',
            'card_desc'  => 'Ringkasan layanan informasi, jumlah permintaan, waktu penyelesaian, dan tingkat kepuasan.',
            'el_id'      => '',
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-ppid-akun-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-ppid-akun-container">
            <div class="ppic-ppid-akun-content">
                <h2 class="ppic-ppid-akun-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-ppid-akun-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <?php endif; ?>
                
                <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $atts['btn_url'] ); ?>" class="ppic-ppid-akun-btn">
                        <?php echo esc_html( $atts['btn_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="ppic-ppid-akun-card-wrapper">
                <div class="ppic-ppid-akun-card">
                    <div class="ppic-ppid-akun-icon">
                        <i class="<?php echo esc_attr( $atts['card_icon'] ); ?>" aria-hidden="true"></i>
                    </div>
                    <h3><?php echo esc_html( $atts['card_title'] ); ?></h3>
                    <p><?php echo esc_html( $atts['card_desc'] ); ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_akuntabilitas_pelaporan_element' );
function ppic_register_ppid_akuntabilitas_pelaporan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Akuntabilitas dan Pelaporan', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_akuntabilitas_pelaporan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-chart-bar',
            'params'   => array(
                // Pengaturan Kolom Kiri
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Akuntabilitas dan Pelaporan',
                    'admin_label'=> true,
                    'group'      => __( 'Teks & Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Kami menyampaikan laporan layanan informasi publik secara transparan sebagai bentuk pertanggungjawaban kepada masyarakat.',
                    'group'      => __( 'Teks & Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Lihat Laporan Layanan Informasi Publik',
                    'group'      => __( 'Teks & Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'URL Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'btn_url',
                    'value'      => '#',
                    'group'      => __( 'Teks & Tombol', 'ppic-custom-element' ),
                ),
                // Pengaturan Kolom Kanan (Kartu)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_icon',
                    'value'      => 'fas fa-chart-bar',
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_title',
                    'value'      => 'Laporan Tahunan',
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_desc',
                    'value'      => 'Ringkasan layanan informasi, jumlah permintaan, waktu penyelesaian, dan tingkat kepuasan.',
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
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