<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_penelitian_hero', 'ppic_penelitian_hero_render' );
function ppic_penelitian_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_normal'    => 'Penelitian &',
            'title_highlight' => 'Publikasi',
            'desc'            => 'PPI Curug berperan aktif dalam pengembangan ilmu pengetahuan dan teknologi di bidang penerbangan melalui penelitian terapan, publikasi ilmiah terakreditasi, serta pengabdian kepada masyarakat. Temukan pusat penelitian, jurnal, dan platform digital kami.',
            'btn_text'        => 'Kembali ke Beranda',
            'btn_url'         => '',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-penelitian-hero' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // LOGIKA PENENTUAN URL BERANDA (Home URL Fallback)
    $final_btn_url = ! empty( $atts['btn_url'] ) ? esc_url( $atts['btn_url'] ) : home_url( '/' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-penelitian-hero-container">
            <h1 class="ppic-penelitian-hero-title">
                <?php echo esc_html( $atts['title_normal'] ); ?> 
                <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
            </h1>
            
            <?php if ( ! empty( $atts['desc'] ) ) : ?>
                <p class="ppic-penelitian-hero-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                <a href="<?php echo $final_btn_url; ?>" class="ppic-penelitian-hero-btn">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> <?php echo esc_html( $atts['btn_text'] ); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_penelitian_hero_element' );
function ppic_register_penelitian_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Penelitian - Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_penelitian_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-search', // Ikon kaca pembesar (penelitian)
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Putih)', 'ppic-custom-element' ),
                    'param_name' => 'title_normal',
                    'value'      => 'Penelitian &',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'Publikasi',
                    'description'=> __( 'Bagian teks ini akan diberi warna kuning khas.', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'PPI Curug berperan aktif dalam pengembangan ilmu pengetahuan dan teknologi di bidang penerbangan melalui penelitian terapan, publikasi ilmiah terakreditasi, serta pengabdian kepada masyarakat. Temukan pusat penelitian, jurnal, dan platform digital kami.',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Kembali', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Kembali ke Beranda',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'URL Tombol Kembali', 'ppic-custom-element' ),
                    'param_name' => 'btn_url',
                    'value'      => '',
                    'description'=> __( 'Biarkan kosong untuk otomatis kembali ke halaman utama (Home) website ini.', 'ppic-custom-element' ),
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