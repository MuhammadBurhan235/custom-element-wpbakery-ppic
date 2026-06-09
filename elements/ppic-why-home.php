<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_why_default_features() {
    return urlencode(
        wp_json_encode(
            array(
                array(
                    'bold_text' => 'Berdiri sejak 1952',
                    'normal_text' => 'Pengalaman lebih dari 7 dekade mencetak aviator.',
                ),
                array(
                    'bold_text' => 'Kedinasan di bawah Kementerian Perhubungan',
                    'normal_text' => 'Jaminan kualitas dan karier.',
                ),
                array(
                    'bold_text' => 'Standar internasional ICAO & TrainAir Plus',
                    'normal_text' => 'Kurikulum diakui global.',
                ),
                array(
                    'bold_text' => 'Fasilitas praktik berbasis industri',
                    'normal_text' => 'Dari simulator hingga hanggar.',
                ),
                array(
                    'bold_text' => 'Disiplin dan pembentukan karakter',
                    'normal_text' => 'Mental baja, integritas tinggi.',
                ),
                array(
                    'bold_text' => 'Jejaring luas dan peluang karier',
                    'normal_text' => 'Langsung diserap industri.',
                ),
            )
        )
    );
}

// 1. Mendaftarkan Elemen "Why PPIC" ke UI WPBakery
add_action('vc_before_init', 'ppic_why_section_map');
function ppic_why_section_map() {
    if ( function_exists('vc_map') ) {
        vc_map(array(
            "name" => __("PPIC Why Home Section", "ppic-custom-element"),
            "base" => "ppic_why_section",
            "category" => __("PPIC Elements", "ppic-custom-element"),
            "icon" => "dashicons dashicons-yes-alt", // Ikon checklist
            "params" => array(
                // Gambar Kiri
                array(
                    "type" => "attach_image",
                    "heading" => __("Gambar Sebelah Kiri", "ppic-custom-element"),
                    "param_name" => "image",
                ),
                // Judul
                array(
                    "type" => "textfield",
                    "heading" => __("Judul Utama", "ppic-custom-element"),
                    "param_name" => "title",
                    "value" => "Kenapa PPI Curug?",
                    "admin_label" => true,
                ),
                // Punchline (Teks Kuning)
                array(
                    "type" => "textarea",
                    "heading" => __("Punchline (Teks Kuning)", "ppic-custom-element"),
                    "param_name" => "punchline",
                    "value" => "Banyak yang ingin bekerja di industri penerbangan.\nSedikit yang siap dibentuk.",
                    "description" => __("Gunakan Enter untuk baris baru.", "ppic-custom-element"),
                ),
                // Deskripsi Pembuka
                array(
                    "type" => "textarea",
                    "heading" => __("Deskripsi Pendek", "ppic-custom-element"),
                    "param_name" => "lead_text",
                    "value" => "Di PPI Curug, kami tidak sekadar mencetak lulusan. Kami membentuk taruna menjadi profesional tangguh yang siap memimpin.",
                ),
                // Repeater untuk List Poin-poin
                array(
                    "type" => "param_group",
                    "heading" => __("Daftar Poin Keunggulan", "ppic-custom-element"),
                    "param_name" => "features",
                    "value" => ppic_why_default_features(),
                    "params" => array(
                        array(
                            "type" => "textfield",
                            "heading" => __("Teks Tebal (Bold)", "ppic-custom-element"),
                            "param_name" => "bold_text",
                            "admin_label" => true,
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => __("Teks Penjelasan", "ppic-custom-element"),
                            "param_name" => "normal_text",
                        ),
                    ),
                ),
                // CTA Bawah
                array(
                    "type" => "textfield",
                    "heading" => __("Teks CTA Bawah", "ppic-custom-element"),
                    "param_name" => "cta_text",
                    "value" => "Mereka yang serius tidak perlu berpikir panjang.",
                ),
                // Link CTA
                array(
                    "type" => "vc_link",
                    "heading" => __("Link & Teks Navigasi Bawah", "ppic-custom-element"),
                    "param_name" => "cta_link",
                ),
                array(
                    "type" => "el_id",
                    "heading" => __("Element ID", "js_composer"),
                    "param_name" => "el_id",
                    "description" => __("Enter element ID (Note: make sure it is unique and valid according to w3c specification).", "js_composer"),
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra class name", "js_composer"),
                    "param_name" => "el_class",
                    "description" => __("Style particular content element differently by adding a class name and referring to it in custom CSS.", "js_composer"),
                ),
            )
        ));
    }
}

// 2. Fungsi Render HTML untuk Frontend
add_shortcode('ppic_why_section', 'ppic_why_section_render');
function ppic_why_section_render($atts) {
    $atts = shortcode_atts(array(
        'image'       => '',
        'title'       => 'Kenapa PPI Curug?',
        'punchline'   => 'Banyak yang ingin bekerja di industri penerbangan.<br>Sedikit yang siap dibentuk.',
        'lead_text'   => 'Di PPI Curug, kami tidak sekadar mencetak lulusan. Kami membentuk taruna menjadi profesional tangguh yang siap memimpin.',
        'features'    => ppic_why_default_features(),
        'cta_text'    => 'Mereka yang serius tidak perlu berpikir panjang.',
        'cta_link'    => '',
        'el_id'       => '',
        'el_class'    => ''
    ), $atts);

    // Parsing Gambar
    $img_url = 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg'; // fallback
    if (!empty($atts['image'])) {
        $img_data = wp_get_attachment_image_src($atts['image'], 'full');
        if ($img_data) $img_url = $img_data[0];
    }

    // Parsing Link CTA
    $link = vc_build_link($atts['cta_link']);
    $a_href = !empty($link['url']) ? $link['url'] : '/kenapa-ppic';
    $a_title = !empty($link['title']) ? $link['title'] : 'Belum yakin? jelajahi kami lebih dalam.';
    $a_target = !empty($link['target']) ? ' target="'.trim($link['target']).'"' : '';

    // Parsing Punchline (ubah enter jadi <br>)
    $punchline_html = nl2br(esc_html($atts['punchline']));

    // Parsing Repeater (List Poin)
    $features = vc_param_group_parse_atts( $atts['features'] );
    $wrapper_id = !empty($atts['el_id']) ? ' id="' . esc_attr($atts['el_id']) . '"' : '';
    $wrapper_class = 'why-home-ppic' . ( !empty($atts['el_class']) ? ' ' . esc_attr(trim($atts['el_class'])) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-why-home-container">
            <div class="why-home-image">
                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($atts['title']); ?>">
            </div>
            <div class="why-home-content">
                <h2><?php echo esc_html($atts['title']); ?></h2>
                <div class="punchline"><?php echo $punchline_html; ?></div>
                <p class="lead"><?php echo esc_html($atts['lead_text']); ?></p>
                
                <?php if ( !empty($features) && is_array($features) ) : ?>
                <ul class="why-home-list">
                    <?php foreach ( $features as $feature ) : 
                        $bold = isset($feature['bold_text']) ? $feature['bold_text'] : '';
                        $normal = isset($feature['normal_text']) ? $feature['normal_text'] : '';
                    ?>
                    <li>
                        <i class="fas fa-check-circle"></i> 
                        <div><strong><?php echo esc_html($bold); ?></strong> – <span><?php echo esc_html($normal); ?></span></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <div class="micro-cta-home">
                    <?php echo esc_html($atts['cta_text']); ?><br> 
                    <a href="<?php echo esc_url($a_href); ?>" <?php echo $a_target; ?>><?php echo esc_html($a_title); ?></a>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}