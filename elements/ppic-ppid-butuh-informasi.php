<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_butuh_informasi', 'ppic_ppid_butuh_informasi_render' );
function ppic_ppid_butuh_informasi_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'Butuh Informasi?',
            'subtitle' => 'Jangan ragu untuk menghubungi kami atau ajukan permintaan secara online.',
            'buttons'  => '',
            'el_id'    => '',
            'el_class' => '',
        ),
        $atts
    );

    // Proses daftar tombol
    $buttons = vc_param_group_parse_atts( $atts['buttons'] );

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-ppid-butuh-info-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-ppid-butuh-info-container">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 class="ppic-ppid-butuh-info-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-ppid-butuh-info-desc"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $buttons ) && is_array( $buttons ) ) : ?>
                <div class="ppic-ppid-butuh-info-buttons">
                    <?php foreach ( $buttons as $btn ) : 
                        $label = isset( $btn['label'] ) ? trim( $btn['label'] ) : '';
                        $url   = isset( $btn['url'] ) && ! empty( $btn['url'] ) ? trim( $btn['url'] ) : '#';
                        $style = isset( $btn['style'] ) ? trim( $btn['style'] ) : 'secondary';
                        
                        if ( empty( $label ) ) continue;

                        $btn_class = 'primary' === $style ? 'ppic-btn-primary' : 'ppic-btn-secondary';
                        ?>
                        <a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $btn_class ); ?>">
                            <?php echo esc_html( $label ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_butuh_informasi_element' );
function ppic_register_ppid_butuh_informasi_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_buttons = array(
        array(
            'label' => 'Ajukan Informasi',
            'url'   => 'https://docs.google.com/forms/d/e/1FAIpQLSc3DdIFtG2bNPwmAVHNUyucoZ5bhLhep9zcZiKpJptAjye5LA/viewform',
            'style' => 'primary'
        ),
        array(
            'label' => 'Kontak PPID',
            'url'   => '#',
            'style' => 'secondary'
        ),
        array(
            'label' => 'Email Resmi',
            'url'   => '#',
            'style' => 'secondary'
        ),
        array(
            'label' => 'Alamat & Jam Layanan',
            'url'   => '#',
            'style' => 'secondary'
        )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Butuh Informasi', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_butuh_informasi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-phone',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Butuh Informasi?',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Jangan ragu untuk menghubungi kami atau ajukan permintaan secara online.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Tombol', 'ppic-custom-element' ),
                    'param_name' => 'buttons',
                    'value'      => urlencode( wp_json_encode( $dummy_buttons ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Teks Tombol', 'ppic-custom-element' ),
                            'param_name'  => 'label',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Tombol', 'ppic-custom-element' ),
                            'param_name' => 'url',
                            'value'      => '#',
                        ),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => __( 'Gaya Tombol', 'ppic-custom-element' ),
                            'param_name' => 'style',
                            'value'      => array(
                                __( 'Secondary (Outline Putih)', 'ppic-custom-element' ) => 'secondary',
                                __( 'Primary (Warna Kuning)', 'ppic-custom-element' ) => 'primary',
                            ),
                            'std'        => 'secondary',
                            'description'=> __( 'Primary = Latar kuning. Secondary = Transparan dengan outline putih.', 'ppic-custom-element' ),
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