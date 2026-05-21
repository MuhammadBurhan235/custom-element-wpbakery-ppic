<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_stats_section', 'ppic_stats_section_render' );
function ppic_stats_section_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'stats' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'value' => '700+',
                            'label' => 'Mahasiswa Aktif',
                        ),
                        array(
                            'value' => '80+',
                            'label' => 'Dosen & Instruktur',
                        ),
                        array(
                            'value' => '47',
                            'label' => 'Pesawat Latih',
                        ),
                        array(
                            'value' => '10',
                            'label' => 'Program Studi',
                        ),
                    )
                )
            ),
        ),
        $atts
    );

    $stats = vc_param_group_parse_atts( $atts['stats'] );

    if ( empty( $stats ) || ! is_array( $stats ) ) {
        return '';
    }

    ob_start();
    ?>
    <section class="ppic-stats-section">
        <div class="ppic-stats-container">
            <?php foreach ( $stats as $stat ) :
                $value = isset( $stat['value'] ) ? trim( $stat['value'] ) : '';
                $label = isset( $stat['label'] ) ? trim( $stat['label'] ) : '';

                if ( '' === $value && '' === $label ) {
                    continue;
                }
                ?>
                <div class="ppic-stat-item">
                    <h3><?php echo esc_html( $value ); ?></h3>
                    <p><?php echo esc_html( $label ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_stats_section_map' );
function ppic_stats_section_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Stats', 'ppic-custom-element' ),
            'base' => 'ppic_stats_section',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-chart-bar',
            'params' => array(
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Daftar Statistik', 'ppic-custom-element' ),
                    'param_name' => 'stats',
                    'value' => urlencode(
                        wp_json_encode(
                            array(
                                array(
                                    'value' => '700+',
                                    'label' => 'Mahasiswa Aktif',
                                ),
                                array(
                                    'value' => '80+',
                                    'label' => 'Dosen & Instruktur',
                                ),
                                array(
                                    'value' => '47',
                                    'label' => 'Pesawat Latih',
                                ),
                                array(
                                    'value' => '10',
                                    'label' => 'Program Studi',
                                ),
                            )
                        )
                    ),
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Angka', 'ppic-custom-element' ),
                            'param_name' => 'value',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Label', 'ppic-custom-element' ),
                            'param_name' => 'label',
                        ),
                    ),
                ),
            ),
        )
    );
}