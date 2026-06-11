<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_statistik', 'ppic_kenapa_statistik_render' );
function ppic_kenapa_statistik_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'stats'      => '',
            'btn_text'   => 'DAFTAR SEKARANG',
            'btn_link'   => 'url:explore.html|title:Daftar%20Sekarang',
            'btn_icon'   => 'fas fa-paper-plane',
            'el_id'      => '',
            'el_class'   => '',
        ),
        $atts
    );

    // Proses data statistik
    $stats = vc_param_group_parse_atts( $atts['stats'] );

    // Parse URL Tombol WPBakery
    $link = ( '||' !== $atts['btn_link'] ) ? vc_build_link( $atts['btn_link'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_title  = ! empty( $link['title'] ) ? $link['title'] : '';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';
    $a_rel    = ! empty( $link['rel'] ) ? ' rel="' . esc_attr( trim( $link['rel'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-stats-alumni-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-stats-alumni-container">
            
            <div class="ppic-stats-alumni-group">
                <?php if ( ! empty( $stats ) && is_array( $stats ) ) : ?>
                    <?php foreach ( $stats as $st ) : 
                        $icon   = isset( $st['icon'] ) ? trim( $st['icon'] ) : 'fas fa-check';
                        $number = isset( $st['number'] ) ? trim( $st['number'] ) : '';
                        $label  = isset( $st['label'] ) ? trim( $st['label'] ) : '';
                        
                        if ( empty( $number ) ) continue;
                        ?>
                        <div class="ppic-stats-alumni-item">
                            <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            <div class="stat-number"><?php echo esc_html( $number ); ?></div>
                            <p><?php echo esc_html( $label ); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="ppic-stats-alumni-cta">
                <a href="<?php echo esc_url( $a_href ); ?>" class="btn-daftar-sekarang" title="<?php echo esc_attr( $a_title ); ?>"<?php echo $a_target; ?><?php echo $a_rel; ?>>
                    <i class="<?php echo esc_attr( $atts['btn_icon'] ); ?>" aria-hidden="true"></i>
                    <?php echo esc_html( $atts['btn_text'] ); ?>
                </a>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_statistik_element' );
function ppic_register_kenapa_statistik_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_stats = array(
        array(
            'icon'   => 'fas fa-gem',
            'number' => '5000+',
            'label'  => 'Alumni Tersebar'
        ),
        array(
            'icon'   => 'fas fa-plane-departure',
            'number' => '80+',
            'label'  => 'Instruktur Ahli'
        ),
        array(
            'icon'   => 'fas fa-building',
            'number' => '50+',
            'label'  => 'Mitra Industri'
        ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Statistik Alumni', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_statistik',
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
                            'heading'     => __( 'Icon Class (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'description' => 'Contoh: fas fa-gem',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Angka Besar', 'ppic-custom-element' ),
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
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'DAFTAR SEKARANG',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_icon',
                    'value'      => 'fas fa-paper-plane',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                    'value'      => 'url:explore.html|title:Daftar%20Sekarang',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Enter element ID.', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently.', 'js_composer' ),
                ),
            ),
        )
    );
}