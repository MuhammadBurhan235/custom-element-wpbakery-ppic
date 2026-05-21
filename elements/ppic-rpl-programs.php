<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function ppic_register_rpl_programs_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => "PPIC RPL Programs",
            "base" => "ppic_rpl_programs",
            "category" => "PPIC Elements",
            "icon" => "icon-wpbakery",
            "params" => array(
                array("type" => "textfield", "heading" => "Judul Utama", "param_name" => "title", "value" => "Program RPL"),
                array("type" => "textarea", "heading" => "Sub-Judul", "param_name" => "subtitle", "value" => "Rekognisi Pembelajaran Lampau bagi praktisi penerbangan..."),
                array("type" => "vc_link", "heading" => "Link Daftar", "param_name" => "btn_link"),
            )
        ));
    }
}
add_action( 'vc_before_init', 'ppic_register_rpl_programs_element' );

function ppic_rpl_programs_render( $atts ) {
    $atts = shortcode_atts( array(
        'title'    => 'Program RPL',
        'subtitle' => 'Rekognisi Pembelajaran Lampau bagi praktisi penerbangan yang ingin meningkatkan kualifikasi akademik ke jenjang Sarjana Terapan.',
        'btn_link' => '',
    ), $atts );

    $link = vc_build_link( $atts['btn_link'] );
    $items = [
        ['icon' => 'fa-broadcast-tower', 'title' => 'RPL LLU', 'desc' => 'Rekognisi untuk bidang Lalu Lintas Udara, percepatan studi jenjang DIV.'],
        ['icon' => 'fa-wrench', 'title' => 'RPL TPU', 'desc' => 'Konversi pengalaman teknisi pesawat ke dalam SKS program DIV Teknik Pesawat Udara.'],
        ['icon' => 'fa-satellite', 'title' => 'RPL TNU', 'desc' => 'Bagi personel navigasi dan teknisi avionik untuk mendapatkan gelar Sarjana Terapan.'],
        ['icon' => 'fa-bolt', 'title' => 'RPL TLB', 'desc' => 'Rekognisi bagi profesional kelistrikan bandara menuju DIV Teknik Listrik Bandara.']
    ];

    ob_start(); ?>
    <div class="ppic-rpl-section">
        <div class="ppic-rpl-header">
            <h2><?php echo esc_html($atts['title']); ?></h2>
            <p><?php echo esc_html($atts['subtitle']); ?></p>
        </div>
        <div class="ppic-rpl-grid">
            <?php foreach($items as $item): ?>
                <div class="ppic-rpl-card">
                    <i class="fas <?php echo $item['icon']; ?>"></i>
                    <h4><?php echo $item['title']; ?></h4>
                    <p><?php echo $item['desc']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="ppic-rpl-footer">
            <a href="<?php echo esc_url($link['url']); ?>" class="ppic-rpl-btn">📄 Daftar RPL Sekarang</a>
        </div>
    </div>
    <?php return ob_get_clean();
}
add_shortcode( 'ppic_rpl_programs', 'ppic_rpl_programs_render' );