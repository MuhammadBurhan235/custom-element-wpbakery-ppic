<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_penelitian_stats', 'ppic_penelitian_stats_render' );
function ppic_penelitian_stats_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'stats'    => '',
            'el_id'    => 'statistik-penelitian',
            'el_class' => '',
        ),
        $atts
    );

    // Proses data statistik
    $stats = vc_param_group_parse_atts( $atts['stats'] );

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-research-stats-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-research-stats-container">
            <?php if ( ! empty( $stats ) && is_array( $stats ) ) : ?>
                <?php foreach ( $stats as $st ) : 
                    $number = isset( $st['number'] ) ? trim( $st['number'] ) : '';
                    $label  = isset( $st['label'] ) ? trim( $st['label'] ) : '';
                    
                    if ( empty( $number ) && empty( $label ) ) continue;
                    ?>
                    <div class="research-stat-item">
                        <div class="research-stat-number"><?php echo esc_html( $number ); ?></div>
                        <div class="research-stat-label"><?php echo esc_html( $label ); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_penelitian_stats_element' );
function ppic_register_penelitian_stats_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_stats = array(
        array( 'number' => '5+', 'label' => 'Jurnal Ilmiah' ),
        array( 'number' => 'SINTA 2', 'label' => 'Akreditasi Tertinggi' ),
        array( 'number' => '2023', 'label' => 'Awal E-Repository' ),
        array( 'number' => 'Online', 'label' => 'Akses 24/7' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Penelitian - Statistik', 'ppic-custom-element' ),
            'base'     => 'ppic_penelitian_stats',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-chart-bar',
            'params'   => array(
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Angka Statistik', 'ppic-custom-element' ),
                    'param_name' => 'stats',
                    'value'      => urlencode( wp_json_encode( $dummy_stats ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Angka Besar / Teks Utama', 'ppic-custom-element' ),
                            'param_name'  => 'number',
                            'admin_label' => true,
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Label Keterangan', 'ppic-custom-element' ),
                            'param_name'  => 'label',
                        ),
                    ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'statistik-penelitian',
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