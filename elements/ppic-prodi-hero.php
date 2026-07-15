<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_hero', 'ppic_prodi_hero_render' );
function ppic_prodi_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'bg_image'     => '',
            'title_white'  => 'Teknik Mekanikal',
            'title_yellow' => 'Bandar Udara',
            'subtitle'     => 'Program studi vokasi unggulan di Politeknik Penerbangan Indonesia Curug — mencetak ahli mekanikal bandar udara yang profesional, kompeten, dan siap bersaing di tingkat nasional maupun internasional.',
            'badges'       => '',
            'el_id'        => '',
            'el_class'     => '',
        ),
        $atts
    );

    // Persiapan Wrapper ID & Class
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-prodi-hero-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Ambil URL Background Image jika admin mengunggah gambar
    $bg_style = '';
    if ( ! empty( $atts['bg_image'] ) ) {
        $img_url = wp_get_attachment_image_url( $atts['bg_image'], 'full' );
        if ( $img_url ) {
            // Tambahkan linear-gradient gelap agar teks putih tetap terbaca di atas gambar apapun
            $bg_style = ' style="background-image: linear-gradient(rgba(10, 25, 40, 0.85), rgba(10, 25, 40, 0.85)), url(' . esc_url( $img_url ) . '); background-size: cover; background-position: center;"';
            $wrapper_class .= ' has-bg-image';
        }
    }

    // Parse daftar badge (Akreditasi, SKS, dll)
    $badge_items = vc_param_group_parse_atts( $atts['badges'] );
    if ( ! is_array( $badge_items ) ) {
        $badge_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>"<?php echo $bg_style; ?>>
        <!-- Elemen dekorasi lingkaran (hanya muncul jika tidak memakai gambar background) -->
        <div class="prodi-hero-decoration"></div>
        
        <div class="ppic-prodi-hero-container">
            <h1>
                <?php echo esc_html( $atts['title_white'] ); ?> 
                <?php if ( ! empty( $atts['title_yellow'] ) ) : ?>
                    <br /><span><?php echo esc_html( $atts['title_yellow'] ); ?></span>
                <?php endif; ?>
            </h1>
            
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="prodi-subtitle">
                    <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                </p>
            <?php endif; ?>

            <?php if ( ! empty( $badge_items ) ) : ?>
                <div class="prodi-badge-wrapper">
                    <?php foreach ( $badge_items as $badge ) : 
                        $icon = isset( $badge['icon'] ) ? trim( $badge['icon'] ) : 'fas fa-check';
                        $text = isset( $badge['text'] ) ? trim( $badge['text'] ) : '';
                        
                        if ( empty( $text ) ) continue;
                    ?>
                        <span class="prodi-badge">
                            <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i> 
                            <?php echo esc_html( $text ); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_hero_element' );
function ppic_register_prodi_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default Badges persis seperti referensi HTML
    $dummy_badges = array(
        array( 'icon' => 'fas fa-certificate', 'text' => 'Terakreditasi UNGGUL' ),
        array( 'icon' => 'fas fa-graduation-cap', 'text' => '109 SKS' ),
        array( 'icon' => 'fas fa-clock', 'text' => '6 Semester' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-learn-more',
            'params'   => array(
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Background (Opsional)', 'ppic-custom-element' ),
                    'param_name'  => 'bg_image',
                    'description' => __( 'Biarkan kosong untuk menggunakan background biru dengan dekorasi lengkung bawaan. Jika diisi, gambar akan otomatis digelapkan agar teks tetap terbaca.', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Baris 1 (Putih)', 'ppic-custom-element' ),
                    'param_name'  => 'title_white',
                    'value'       => 'Teknik Mekanikal',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Baris 2 (Kuning)', 'ppic-custom-element' ),
                    'param_name'  => 'title_yellow',
                    'value'       => 'Bandar Udara',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Deskripsi / Subtitle', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Program studi vokasi unggulan di Politeknik Penerbangan Indonesia Curug — mencetak ahli mekanikal bandar udara yang profesional, kompeten, dan siap bersaing di tingkat nasional maupun internasional.',
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Badge (Info Singkat)', 'ppic-custom-element' ),
                    'param_name'  => 'badges',
                    'value'       => urlencode( wp_json_encode( $dummy_badges ) ),
                    'description' => __( 'Gunakan ini untuk menambah info seperti SKS, Semester, atau Akreditasi.', 'ppic-custom-element' ),
                    'params'      => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Ikon (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'value'       => 'fas fa-check',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Teks Badge', 'ppic-custom-element' ),
                            'param_name'  => 'text',
                            'admin_label' => true,
                        ),
                    ),
                ),
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