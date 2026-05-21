<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// 1. Fungsi untuk me-render HTML di Frontend (Shortcode)
add_shortcode('ppic_hero_section', 'ppic_hero_section_render');
function ppic_hero_section_render($atts) {
    // Parameter default
    $atts = shortcode_atts(array(
        'title'       => 'Tempat Pemimpin Penerbangan Dilahirkan.',
        'description' => 'Di Politeknik Penerbangan Indonesia Curug, kami tidak hanya mendidik...',
        'btn_text'    => 'Jelajahi Program Kami',
        'btn_link'    => '',
        'image'       => ''
    ), $atts);

    // Parsing parameter Link bawaan WPBakery
    $link = vc_build_link($atts['btn_link']);
    $a_href = !empty($link['url']) ? $link['url'] : '#';
    $a_target = !empty($link['target']) ? ' target="'.trim($link['target']).'"' : '';

    // Parsing URL Gambar dari Media Library
    $img_url = 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg'; // Gambar default
    if (!empty($atts['image'])) {
        $img_data = wp_get_attachment_image_src($atts['image'], 'full');
        if ($img_data) {
            $img_url = $img_data[0];
        }
    }

    ob_start();
    ?>
    <section class="ppic-hero-section">
        <div class="ppic-hero-container">
            <div class="ppic-hero-text">
                <h2><?php echo esc_html($atts['title']); ?></h2>
                <p><?php echo esc_html($atts['description']); ?></p>
                <div class="ppic-hero-buttons">
                    <a href="<?php echo esc_url($a_href); ?>" <?php echo $a_target; ?> class="ppic-btn-primary">
                        <?php echo esc_html($atts['btn_text']); ?>
                    </a>
                </div>
            </div>
            <div class="ppic-hero-image">
                <img src="<?php echo esc_url($img_url); ?>" alt="Hero Image PPI Curug">
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// 2. Mendaftarkan Elemen ke dalam UI WPBakery
add_action('vc_before_init', 'ppic_hero_section_map');
function ppic_hero_section_map() {
    vc_map(array(
        "name" => __("PPIC Hero", "my-text-domain"),
        "base" => "ppic_hero_section",
        "category" => __("PPIC Elements", "my-text-domain"), // Akan membuat tab kategori baru di WPBakery
        "icon" => "dashicons dashicons-align-left", // Ikon yang muncul di WPBakery
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Judul Hero", "my-text-domain"),
                "param_name" => "title",
                "value" => "Tempat Pemimpin Penerbangan Dilahirkan.",
                "admin_label" => true,
            ),
            array(
                "type" => "textarea",
                "heading" => __("Deskripsi", "my-text-domain"),
                "param_name" => "description",
            ),
            array(
                "type" => "textfield",
                "heading" => __("Teks Tombol", "my-text-domain"),
                "param_name" => "btn_text",
                "value" => "Jelajahi Program Kami",
            ),
            array(
                "type" => "vc_link",
                "heading" => __("Link Tombol", "my-text-domain"),
                "param_name" => "btn_link",
                "description" => __("Pilih halaman tujuan saat tombol diklik.", "my-text-domain"),
            ),
            array(
                "type" => "attach_image",
                "heading" => __("Gambar Hero", "my-text-domain"),
                "param_name" => "image",
                "description" => __("Upload gambar untuk sisi kanan. Disarankan format landscape.", "my-text-domain"),
            ),
        )
    ));
}