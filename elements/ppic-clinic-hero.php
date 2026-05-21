<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_clinic_hero_default_badges() {
    return urlencode(
        wp_json_encode(
            array(
                array(
                    'icon_class' => 'fas fa-user-md',
                    'text' => '24 Tenaga Medis',
                ),
                array(
                    'icon_class' => 'fas fa-ambulance',
                    'text' => 'Ambulance 24/7',
                ),
                array(
                    'icon_class' => 'fas fa-building',
                    'text' => 'Terakreditasi',
                ),
            )
        )
    );
}

add_shortcode( 'ppic_clinic_hero', 'ppic_clinic_hero_render' );
function ppic_clinic_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_prefix' => 'Klinik',
            'title_highlight' => 'PPI Curug',
            'tagline' => 'Pelayanan Kesehatan Terpercaya di Kawasan Bandara Budiarto',
            'description' => 'Klinik PPIC adalah fasilitas kesehatan di bawah Kementerian Perhubungan, melayani taruna, pegawai, industri penerbangan, dan masyarakat umum. Didukung tenaga medis tersertifikasi, profesional, dan fasilitas modern untuk kesehatan kerja & perjalanan.',
            'primary_text' => 'Jadwal MCU & Vaksin',
            'primary_link' => '',
            'secondary_text' => 'Layanan Darurat',
            'secondary_link' => '',
            'image_url' => 'https://placehold.co/600x400/003153/white?text=Klinik+PPIC+Modern',
            'image_alt' => 'Klinik PPIC Curug Fasilitas Terbaru',
            'badges' => ppic_clinic_hero_default_badges(),
        ),
        $atts
    );

    $primary_link = vc_build_link( $atts['primary_link'] );
    $primary_href = ! empty( $primary_link['url'] ) ? $primary_link['url'] : '#';
    $primary_title = ! empty( $primary_link['title'] ) ? $primary_link['title'] : $atts['primary_text'];
    $primary_target = ! empty( $primary_link['target'] ) ? ' target="' . trim( $primary_link['target'] ) . '"' : '';

    $secondary_link = vc_build_link( $atts['secondary_link'] );
    $secondary_href = ! empty( $secondary_link['url'] ) ? $secondary_link['url'] : '#';
    $secondary_title = ! empty( $secondary_link['title'] ) ? $secondary_link['title'] : $atts['secondary_text'];
    $secondary_target = ! empty( $secondary_link['target'] ) ? ' target="' . trim( $secondary_link['target'] ) . '"' : '';

    $badges = vc_param_group_parse_atts( $atts['badges'] );

    ob_start();
    ?>
    <section class="ppic-clinic-hero-section">
        <div class="ppic-clinic-hero-container">
            <div class="ppic-clinic-hero-content">
                <h1 class="ppic-clinic-hero-title">
                    <?php echo esc_html( $atts['title_prefix'] ); ?>
                    <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
                </h1>
                <div class="ppic-clinic-hero-tagline"><?php echo esc_html( $atts['tagline'] ); ?></div>
                <p class="ppic-clinic-hero-description"><?php echo esc_html( $atts['description'] ); ?></p>
                <div class="ppic-clinic-hero-actions">
                    <a href="<?php echo esc_url( $primary_href ); ?>"<?php echo $primary_target; ?> class="ppic-clinic-btn-primary">
                        <?php echo esc_html( $primary_title ); ?>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="<?php echo esc_url( $secondary_href ); ?>"<?php echo $secondary_target; ?> class="ppic-clinic-btn-outline">
                        <?php echo esc_html( $secondary_title ); ?>
                    </a>
                </div>
                <?php if ( ! empty( $badges ) && is_array( $badges ) ) : ?>
                    <div class="ppic-clinic-hero-badges">
                        <?php foreach ( $badges as $badge ) :
                            $icon_class = isset( $badge['icon_class'] ) ? trim( $badge['icon_class'] ) : '';
                            $text = isset( $badge['text'] ) ? trim( $badge['text'] ) : '';

                            if ( '' === $text ) {
                                continue;
                            }
                            ?>
                            <div class="ppic-clinic-stat-badge">
                                <?php if ( '' !== $icon_class ) : ?>
                                    <i class="<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></i>
                                <?php endif; ?>
                                <span><?php echo esc_html( $text ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="ppic-clinic-hero-image-wrap">
                <img src="<?php echo esc_url( $atts['image_url'] ); ?>" alt="<?php echo esc_attr( $atts['image_alt'] ); ?>">
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_clinic_hero_map' );
function ppic_clinic_hero_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Clinic Hero', 'ppic-custom-element' ),
            'base' => 'ppic_clinic_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-heart',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Awal', 'ppic-custom-element' ),
                    'param_name' => 'title_prefix',
                    'value' => 'Klinik',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Highlight', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value' => 'PPI Curug',
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Tagline', 'ppic-custom-element' ),
                    'param_name' => 'tagline',
                    'value' => 'Pelayanan Kesehatan Terpercaya di Kawasan Bandara Budiarto',
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value' => 'Klinik PPIC adalah fasilitas kesehatan di bawah Kementerian Perhubungan, melayani taruna, pegawai, industri penerbangan, dan masyarakat umum. Didukung tenaga medis tersertifikasi, profesional, dan fasilitas modern untuk kesehatan kerja & perjalanan.',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks Tombol Utama', 'ppic-custom-element' ),
                    'param_name' => 'primary_text',
                    'value' => 'Jadwal MCU & Vaksin',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link Tombol Utama', 'ppic-custom-element' ),
                    'param_name' => 'primary_link',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks Tombol Outline', 'ppic-custom-element' ),
                    'param_name' => 'secondary_text',
                    'value' => 'Layanan Darurat',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link Tombol Outline', 'ppic-custom-element' ),
                    'param_name' => 'secondary_link',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'URL Gambar', 'ppic-custom-element' ),
                    'param_name' => 'image_url',
                    'value' => 'https://placehold.co/600x400/003153/white?text=Klinik+PPIC+Modern',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Alt Text Gambar', 'ppic-custom-element' ),
                    'param_name' => 'image_alt',
                    'value' => 'Klinik PPIC Curug Fasilitas Terbaru',
                ),
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Badge Statistik Kecil', 'ppic-custom-element' ),
                    'param_name' => 'badges',
                    'value' => ppic_clinic_hero_default_badges(),
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value' => 'fas fa-user-md',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Teks Badge', 'ppic-custom-element' ),
                            'param_name' => 'text',
                            'admin_label' => true,
                        ),
                    ),
                ),
            ),
        )
    );
}
