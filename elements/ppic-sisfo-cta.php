<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_sisfo_cta', 'ppic_sisfo_cta_render' );
function ppic_sisfo_cta_render( $atts ) {
    
    $atts = shortcode_atts(
        array(
            'title'      => 'Butuh Bantuan?',
            'title_icon' => 'fas fa-life-ring',
            'desc'       => 'Hubungi tim IT Support PPI Curug untuk bantuan akses, laporan bug, atau pertanyaan seputar sistem informasi.',
            'btn_text'   => 'Bantuan Teknis',
            'btn_icon'   => 'fas fa-headset',
            'btn_link'   => 'url:%23|||',
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_class = 'ppic-sisfo-cta-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse Link URL
    $link_arr = vc_build_link( $atts['btn_link'] );
    $url      = !empty($link_arr['url']) ? esc_url($link_arr['url']) : '#';
    $target   = !empty($link_arr['target']) ? ' target="'.esc_attr($link_arr['target']).'"' : '';

    ob_start(); ?>
    <section class="<?php echo $wrapper_class; ?>">
        <div class="ppic-sisfo-cta-container">
            <div class="ppic-cta-banner">
                <div class="cta-text-wrap">
                    <h2>
                        <?php if ( ! empty( $atts['title_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['title_icon'] ); ?>"></i>
                        <?php endif; ?>
                        <?php echo esc_html( $atts['title'] ); ?>
                    </h2>
                    <?php if ( ! empty( $atts['desc'] ) ) : ?>
                        <p><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="cta-btn-wrap">
                    <a href="<?php echo $url; ?>" <?php echo $target; ?> class="btn-sisfo-cta">
                        <?php if ( ! empty( $atts['btn_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['btn_icon'] ); ?>"></i> 
                        <?php endif; ?>
                        <?php echo esc_html( $atts['btn_text'] ); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_sisfo_cta_element' );
function ppic_register_sisfo_cta_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Sisfo CTA', 'ppic-custom-element' ),
            'base'     => 'ppic_sisfo_cta',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-megaphone',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul CTA', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Butuh Bantuan?',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-life-ring',
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Deskripsi Bantuan', 'ppic-custom-element' ),
                    'param_name'  => 'desc',
                    'value'       => 'Hubungi tim IT Support PPI Curug untuk bantuan akses, laporan bug, atau pertanyaan seputar sistem informasi.',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol', 'ppic-custom-element' ),
                    'param_name'  => 'btn_text',
                    'value'       => 'Bantuan Teknis',
                    'group'       => __( 'Tombol & Link', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Tombol', 'ppic-custom-element' ),
                    'param_name'  => 'btn_icon',
                    'value'       => 'fas fa-headset',
                    'group'       => __( 'Tombol & Link', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'vc_link',
                    'heading'     => __( 'Tautan Tombol', 'ppic-custom-element' ),
                    'param_name'  => 'btn_link',
                    'group'       => __( 'Tombol & Link', 'ppic-custom-element' ),
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