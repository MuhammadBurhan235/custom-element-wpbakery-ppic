<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_featured_programs', 'ppic_featured_programs_render' );
function ppic_featured_programs_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => 'Program Unggulan',
            'subtitle' => 'Program studi pilihan dengan kurikulum terintegrasi industri dan standar internasional.',
            'programs' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'icon_class' => 'fas fa-graduation-cap',
                            'title' => 'Diploma III Penerbangan',
                            'description' => 'Ahli madya siap kerja',
                        ),
                        array(
                            'icon_class' => 'fas fa-plane',
                            'title' => 'Sarjana Terapan Penerbangan',
                            'description' => 'Strata 1 terapan',
                        ),
                        array(
                            'icon_class' => 'fas fa-file-alt',
                            'title' => 'Program RPL',
                            'description' => 'Rekognisi pembelajaran lampau',
                        ),
                        array(
                            'icon_class' => 'fas fa-headset',
                            'title' => 'Full Flight Simulator',
                            'description' => 'Pelatihan pilot profesional',
                        ),
                        array(
                            'icon_class' => 'fas fa-chalkboard-teacher',
                            'title' => 'Pelatihan',
                            'description' => 'Program sertifikasi & ICAO',
                        ),
                    )
                )
            ),
        ),
        $atts
    );

    $programs = vc_param_group_parse_atts( $atts['programs'] );

    if ( empty( $programs ) || ! is_array( $programs ) ) {
        return '';
    }

    ob_start();
    ?>
    <section class="ppic-featured-programs-section">
        <div class="ppic-featured-programs-container">
            <h2 class="ppic-featured-programs-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <p class="ppic-featured-programs-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <div class="ppic-featured-programs-grid">
                <?php foreach ( $programs as $program ) :
                    $icon_class  = isset( $program['icon_class'] ) ? trim( $program['icon_class'] ) : '';
                    $title       = isset( $program['title'] ) ? trim( $program['title'] ) : '';
                    $description = isset( $program['description'] ) ? trim( $program['description'] ) : '';

                    if ( '' === $title && '' === $description ) {
                        continue;
                    }
                    ?>
                    <div class="ppic-featured-program-card">
                        <?php if ( '' !== $icon_class ) : ?>
                            <div class="ppic-featured-program-icon">
                                <i class="<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>
                        <h4><?php echo esc_html( $title ); ?></h4>
                        <p><?php echo esc_html( $description ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_featured_programs_map' );
function ppic_featured_programs_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Featured Programs', 'ppic-custom-element' ),
            'base' => 'ppic_featured_programs',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-welcome-learn-more',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value' => 'Program Unggulan',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value' => 'Program studi pilihan dengan kurikulum terintegrasi industri dan standar internasional.',
                ),
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Daftar Program', 'ppic-custom-element' ),
                    'param_name' => 'programs',
                    'value' => urlencode(
                        wp_json_encode(
                            array(
                                array(
                                    'icon_class' => 'fas fa-graduation-cap',
                                    'title' => 'Diploma III Penerbangan',
                                    'description' => 'Ahli madya siap kerja',
                                ),
                                array(
                                    'icon_class' => 'fas fa-plane',
                                    'title' => 'Sarjana Terapan Penerbangan',
                                    'description' => 'Strata 1 terapan',
                                ),
                                array(
                                    'icon_class' => 'fas fa-file-alt',
                                    'title' => 'Program RPL',
                                    'description' => 'Rekognisi pembelajaran lampau',
                                ),
                                array(
                                    'icon_class' => 'fas fa-headset',
                                    'title' => 'Full Flight Simulator',
                                    'description' => 'Pelatihan pilot profesional',
                                ),
                                array(
                                    'icon_class' => 'fas fa-chalkboard-teacher',
                                    'title' => 'Pelatihan',
                                    'description' => 'Program sertifikasi & ICAO',
                                ),
                            )
                        )
                    ),
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value' => 'fas fa-graduation-cap',
                            'description' => __( 'Contoh: fas fa-plane', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Nama Program', 'ppic-custom-element' ),
                            'param_name' => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Deskripsi Pendek', 'ppic-custom-element' ),
                            'param_name' => 'description',
                        ),
                    ),
                ),
            ),
        )
    );
}