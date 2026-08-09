<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_sisfo_hero', 'ppic_sisfo_hero_render' );
function ppic_sisfo_hero_render( $atts ) {
    
    $atts = shortcode_atts(
        array(
            'title_pre'   => 'Himpunan',
            'title_hl'    => 'Sistem Informasi',
            'title_post'  => 'PPI Curug',
            'desc'        => 'Kumpulan lengkap platform digital dan aplikasi yang digunakan oleh civitas akademika PPI Curug untuk mendukung perkuliahan, administrasi, penelitian, dan layanan publik.',
            'el_class'    => '',
        ),
        $atts
    );

    $wrapper_class = 'ppic-sisfo-hero-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    
    <section class="<?php echo $wrapper_class; ?>">
        <!-- Elemen latar belakang lengkung (subtle curve) -->
        <div class="ppic-sisfo-bg-curve"></div>
        
        <div class="ppic-sisfo-hero-container">
            <h1 class="ppic-sisfo-title">
                <?php echo esc_html( $atts['title_pre'] ); ?> 
                <span><?php echo esc_html( $atts['title_hl'] ); ?></span> <br />
                <?php echo esc_html( $atts['title_post'] ); ?>
            </h1>
            
            <?php if ( ! empty( $atts['desc'] ) ) : ?>
                <p class="ppic-sisfo-desc">
                    <?php echo wp_kses_post( $atts['desc'] ); ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_sisfo_hero_element' );
function ppic_register_sisfo_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Sisfo Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_sisfo_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-desktop',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Awal (Hitam/Putih)', 'ppic-custom-element' ),
                    'param_name'  => 'title_pre',
                    'value'       => 'Himpunan',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Sorotan (Kuning)', 'ppic-custom-element' ),
                    'param_name'  => 'title_hl',
                    'value'       => 'Sistem Informasi',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Baris Bawah', 'ppic-custom-element' ),
                    'param_name'  => 'title_post',
                    'value'       => 'PPI Curug',
                    'description' => __( 'Teks ini akan otomatis turun ke baris baru.', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Deskripsi Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'desc',
                    'value'       => 'Kumpulan lengkap platform digital dan aplikasi yang digunakan oleh civitas akademika PPI Curug untuk mendukung perkuliahan, administrasi, penelitian, dan layanan publik.',
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