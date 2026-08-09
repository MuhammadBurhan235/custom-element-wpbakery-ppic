<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_action_buttons', 'ppic_prodi_action_buttons_render' );
function ppic_prodi_action_buttons_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'ig_text'     => 'Instagram TMB',
            'ig_link'     => 'url:https%3A%2F%2Finstagram.com%2Fppicurug.official|target:_blank',
            'daftar_text' => 'Daftar Sekarang',
            'daftar_link' => 'url:%23|target:_blank',
            'el_class'    => '',
        ),
        $atts
    );

    $wrapper_class = 'ppic-action-buttons-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse Link Instagram
    $ig_link_data = vc_build_link( $atts['ig_link'] );
    $ig_href      = ! empty( $ig_link_data['url'] ) ? $ig_link_data['url'] : '#';
    $ig_target    = ! empty( $ig_link_data['target'] ) ? ' target="' . esc_attr( trim( $ig_link_data['target'] ) ) . '"' : ' target="_blank"';

    // Parse Link Pendaftaran
    $daftar_link_data = vc_build_link( $atts['daftar_link'] );
    $daftar_href      = ! empty( $daftar_link_data['url'] ) ? $daftar_link_data['url'] : '#';
    $daftar_target    = ! empty( $daftar_link_data['target'] ) ? ' target="' . esc_attr( trim( $daftar_link_data['target'] ) ) . '"' : ' target="_blank"';

    ob_start(); ?>
    <section class="<?php echo $wrapper_class; ?>">
        <div class="ppic-action-container">
            
            <a href="<?php echo esc_url( $ig_href ); ?>"<?php echo $ig_target; ?> class="ppic-btn-action btn-instagram">
                <i class="fab fa-instagram fa-lg" aria-hidden="true"></i> 
                <?php echo esc_html( $atts['ig_text'] ); ?>
            </a>
            
            <a href="<?php echo esc_url( $daftar_href ); ?>"<?php echo $daftar_target; ?> class="ppic-btn-action btn-daftar">
                <i class="fas fa-user-plus" aria-hidden="true"></i> 
                <?php echo esc_html( $atts['daftar_text'] ); ?>
            </a>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_action_buttons_element' );
function ppic_register_prodi_action_buttons_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Tombol Aksi', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_action_buttons',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-external',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol Instagram', 'ppic-custom-element' ),
                    'param_name'  => 'ig_text',
                    'value'       => 'Instagram TMB',
                    'admin_label' => true,
                    'group'       => __( 'Instagram', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'vc_link',
                    'heading'     => __( 'Tautan / URL Instagram', 'ppic-custom-element' ),
                    'param_name'  => 'ig_link',
                    'group'       => __( 'Instagram', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol Pendaftaran', 'ppic-custom-element' ),
                    'param_name'  => 'daftar_text',
                    'value'       => 'Daftar Sekarang',
                    'admin_label' => true,
                    'group'       => __( 'Pendaftaran', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'vc_link',
                    'heading'     => __( 'Tautan / URL Pendaftaran', 'ppic-custom-element' ),
                    'param_name'  => 'daftar_link',
                    'group'       => __( 'Pendaftaran', 'ppic-custom-element' ),
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