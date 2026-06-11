<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_stats', 'ppic_kenapa_stats_render' );
function ppic_kenapa_stats_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'stats'    => '',
            'el_id'    => '',
            'el_class' => '',
        ),
        $atts
    );

    // Proses data statistik
    $stats = vc_param_group_parse_atts( $atts['stats'] );

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-kenapa-stats-program-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    if ( empty( $stats ) || ! is_array( $stats ) ) {
        return '';
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-kenapa-stats-program-container">
            <?php foreach ( $stats as $stat ) : 
                $icon   = isset( $stat['icon'] ) ? trim( $stat['icon'] ) : 'fas fa-chart-bar';
                $number = isset( $stat['number'] ) ? trim( $stat['number'] ) : '';
                $label  = isset( $stat['label'] ) ? trim( $stat['label'] ) : '';
                
                if ( empty( $number ) && empty( $label ) ) {
                    continue;
                }
                ?>
                <div class="ppic-kenapa-stats-program-item">
                    <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                    <div class="ppic-kenapa-stats-program-number"><?php echo esc_html( $number ); ?></div>
                    <p class="ppic-kenapa-stats-program-label"><?php echo esc_html( $label ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_stats_element' );
function ppic_register_kenapa_stats_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_stats = array(
        array(
            'icon'   => 'fas fa-building',
            'number' => '10+',
            'label'  => 'Program Studi',
        ),
        array(
            'icon'   => 'fas fa-user-graduate',
            'number' => '700+',
            'label'  => 'Taruna Aktif',
        ),
        array(
            'icon'   => 'fas fa-plane',
            'number' => '47',
            'label'  => 'Pesawat Latih',
        )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Statistik Program', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_stats',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-chart-pie',
            'params'   => array(
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Statistik', 'ppic-custom-element' ),
                    'param_name' => 'stats',
                    'value'      => urlencode( wp_json_encode( $dummy_stats ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Ikon (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'description' => 'Contoh: fas fa-building',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Angka Statistik', 'ppic-custom-element' ),
                            'param_name'  => 'number',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Label Teks', 'ppic-custom-element' ),
                            'param_name' => 'label',
                            'admin_label'=> true,
                        ),
                    ),
                ),
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