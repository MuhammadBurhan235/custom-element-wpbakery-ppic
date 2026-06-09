<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_transparansi_responsif', 'ppic_ppid_transparansi_responsif_render' );
function ppic_ppid_transparansi_responsif_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Transparan. Responsif. Profesional.',
            'description' => 'PPID bukan sekadar penyedia informasi, tetapi mitra masyarakat dalam memastikan keterbukaan, akuntabilitas, dan pelayanan publik yang berkualitas. Kami percaya bahwa informasi yang terbuka adalah fondasi kepercayaan publik.',
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-ppid-transparansi-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-ppid-transparansi-container">
            <div class="ppic-ppid-transparansi-box">
                <?php if ( ! empty( $atts['title'] ) ) : ?>
                    <h3 class="ppic-ppid-transparansi-title"><?php echo esc_html( $atts['title'] ); ?></h3>
                <?php endif; ?>
                
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <p class="ppic-ppid-transparansi-desc"><?php echo esc_html( $atts['description'] ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_transparansi_responsif_element' );
function ppic_register_ppid_transparansi_responsif_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Transparansi CTA', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_transparansi_responsif',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-megaphone',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Transparan. Responsif. Profesional.',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value'      => 'PPID bukan sekadar penyedia informasi, tetapi mitra masyarakat dalam memastikan keterbukaan, akuntabilitas, dan pelayanan publik yang berkualitas. Kami percaya bahwa informasi yang terbuka adalah fondasi kepercayaan publik.',
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