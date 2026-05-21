<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_accreditation_section', 'ppic_accreditation_section_render' );
function ppic_accreditation_section_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => 'Sertifikasi Lembaga',
            'subtitle' => 'Diakui oleh lembaga terkemuka nasional dan internasional.',
            'items' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1Wswq7k8gMK707Yw-LvkWtGLk03hzpTn9',
                            'label' => 'ICAO',
                            'alt_text' => 'ICAO logo',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1noOsUCBEVULz79NBCi7v3v69yQfh9NcY',
                            'label' => 'KEMENHUB',
                            'alt_text' => 'Kementerian Perhubungan logo',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1mJ0vemOg_2v5qDI818E0vvSsyy4YmkG8',
                            'label' => 'BLU SPEED',
                            'alt_text' => 'BLU Speed logo',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1A8yXrhyTk04EZGPnTaTgAuOanWeczRm1',
                            'label' => 'BAN-PT',
                            'alt_text' => 'BAN-PT logo',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1H0cZ05n6OqQNYTfKRvuvaMafWXKEwvpL',
                            'label' => 'LAM TEKNIK',
                            'alt_text' => 'LAM TEKNIK logo',
                        ),
                    )
                )
            ),
        ),
        $atts
    );

    $items = vc_param_group_parse_atts( $atts['items'] );

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '';
    }

    ob_start();
    ?>
    <section class="ppic-accreditation-section">
        <div class="ppic-accreditation-container">
            <h2 class="ppic-accreditation-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <p class="ppic-accreditation-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <div class="ppic-accreditation-grid">
                <?php foreach ( $items as $item ) :
                    $image_url = isset( $item['image_url'] ) ? trim( $item['image_url'] ) : '';
                    $label     = isset( $item['label'] ) ? trim( $item['label'] ) : '';
                    $alt_text  = isset( $item['alt_text'] ) ? trim( $item['alt_text'] ) : $label;

                    if ( '' === $label && '' === $image_url ) {
                        continue;
                    }
                    ?>
                    <div class="ppic-accreditation-item">
                        <?php if ( '' !== $image_url ) : ?>
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" class="ppic-accreditation-logo">
                        <?php endif; ?>
                        <span><?php echo esc_html( $label ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_accreditation_section_map' );
function ppic_accreditation_section_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Accreditation', 'ppic-custom-element' ),
            'base' => 'ppic_accreditation_section',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-awards',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value' => 'Sertifikasi Lembaga',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value' => 'Diakui oleh lembaga terkemuka nasional dan internasional.',
                ),
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Daftar Logo Sertifikasi', 'ppic-custom-element' ),
                    'param_name' => 'items',
                    'value' => urlencode(
                        wp_json_encode(
                            array(
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1Wswq7k8gMK707Yw-LvkWtGLk03hzpTn9',
                                    'label' => 'ICAO',
                                    'alt_text' => 'ICAO logo',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1noOsUCBEVULz79NBCi7v3v69yQfh9NcY',
                                    'label' => 'KEMENHUB',
                                    'alt_text' => 'Kementerian Perhubungan logo',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1mJ0vemOg_2v5qDI818E0vvSsyy4YmkG8',
                                    'label' => 'BLU SPEED',
                                    'alt_text' => 'BLU Speed logo',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1A8yXrhyTk04EZGPnTaTgAuOanWeczRm1',
                                    'label' => 'BAN-PT',
                                    'alt_text' => 'BAN-PT logo',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1H0cZ05n6OqQNYTfKRvuvaMafWXKEwvpL',
                                    'label' => 'LAM TEKNIK',
                                    'alt_text' => 'LAM TEKNIK logo',
                                ),
                            )
                        )
                    ),
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'URL Logo', 'ppic-custom-element' ),
                            'param_name' => 'image_url',
                            'description' => __( 'Gunakan URL gambar logo.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Nama Lembaga', 'ppic-custom-element' ),
                            'param_name' => 'label',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Alt Text Logo', 'ppic-custom-element' ),
                            'param_name' => 'alt_text',
                        ),
                    ),
                ),
            ),
        )
    );
}