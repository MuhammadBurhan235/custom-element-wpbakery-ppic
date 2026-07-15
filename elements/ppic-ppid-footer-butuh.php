<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_footer_cta', 'ppic_ppid_footer_cta_render' );
function ppic_ppid_footer_cta_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'      => 'Butuh Informasi Lebih Detail?',
            'desc'       => 'Hubungi petugas PPID PPI Curug atau ajukan permintaan informasi secara langsung',
            'btn1_text'  => 'Ajukan Informasi',
            'btn1_url'   => 'url:https%3A%2F%2Fdocs.google.com%2Fforms|target:_blank',
            'btn2_text'  => 'Kontak PPID',
            'btn2_url'   => 'url:%23',
            'btn3_text'  => 'Email Resmi',
            'btn3_url'   => 'url:%23',
            'el_id'      => 'kontak-footer',
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-footer-cta ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse Link 1 (Primary)
    $link1 = ( '||' !== $atts['btn1_url'] ) ? vc_build_link( $atts['btn1_url'] ) : '';
    $url1  = ! empty( $link1['url'] ) ? $link1['url'] : '';
    $tgt1  = ! empty( $link1['target'] ) ? ' target="' . esc_attr( trim( $link1['target'] ) ) . '"' : '';

    // Parse Link 2 (Secondary)
    $link2 = ( '||' !== $atts['btn2_url'] ) ? vc_build_link( $atts['btn2_url'] ) : '';
    $url2  = ! empty( $link2['url'] ) ? $link2['url'] : '';
    $tgt2  = ! empty( $link2['target'] ) ? ' target="' . esc_attr( trim( $link2['target'] ) ) . '"' : '';

    // Parse Link 3 (Secondary)
    $link3 = ( '||' !== $atts['btn3_url'] ) ? vc_build_link( $atts['btn3_url'] ) : '';
    $url3  = ! empty( $link3['url'] ) ? $link3['url'] : '';
    $tgt3  = ! empty( $link3['target'] ) ? ' target="' . esc_attr( trim( $link3['target'] ) ) . '"' : '';

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-footer-cta-container">
            <h2><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['desc'] ) ) : ?>
                <p><?php echo esc_html( $atts['desc'] ); ?></p>
            <?php endif; ?>
            
            <div class="cta-buttons">
                <?php if ( ! empty( $atts['btn1_text'] ) && ! empty( $url1 ) ) : ?>
                    <a href="<?php echo esc_url( $url1 ); ?>" class="btn-cta-primary"<?php echo $tgt1; ?>>
                        <?php echo esc_html( $atts['btn1_text'] ); ?>
                    </a>
                <?php endif; ?>

                <?php if ( ! empty( $atts['btn2_text'] ) && ! empty( $url2 ) ) : ?>
                    <a href="<?php echo esc_url( $url2 ); ?>" class="btn-cta-link"<?php echo $tgt2; ?>>
                        <?php echo esc_html( $atts['btn2_text'] ); ?>
                    </a>
                <?php endif; ?>

                <?php if ( ! empty( $atts['btn3_text'] ) && ! empty( $url3 ) ) : ?>
                    <a href="<?php echo esc_url( $url3 ); ?>" class="btn-cta-link"<?php echo $tgt3; ?>>
                        <?php echo esc_html( $atts['btn3_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_footer_cta_element' );
function ppic_register_ppid_footer_cta_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Footer CTA "Butuh"', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_footer_cta',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-megaphone',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Butuh Informasi Lebih Detail?',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Hubungi petugas PPID PPI Curug atau ajukan permintaan informasi secara langsung',
                ),
                
                // TOMBOL 1
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol 1 (Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'btn1_text',
                    'value'      => 'Ajukan Informasi',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tombol 1', 'ppic-custom-element' ),
                    'param_name' => 'btn1_url',
                    'value'      => 'url:https%3A%2F%2Fdocs.google.com%2Fforms|target:_blank',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),

                // TOMBOL 2
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tautan 2 (Putih)', 'ppic-custom-element' ),
                    'param_name' => 'btn2_text',
                    'value'      => 'Kontak PPID',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tautan 2', 'ppic-custom-element' ),
                    'param_name' => 'btn2_url',
                    'value'      => 'url:%23',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),

                // TOMBOL 3
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tautan 3 (Putih)', 'ppic-custom-element' ),
                    'param_name' => 'btn3_text',
                    'value'      => 'Email Resmi',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tautan 3', 'ppic-custom-element' ),
                    'param_name' => 'btn3_url',
                    'value'      => 'url:%23',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'kontak-footer',
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