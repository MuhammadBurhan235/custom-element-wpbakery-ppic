<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_rental_hero', 'ppic_rental_hero_render' );
function ppic_rental_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_main'      => 'Sewa Fasilitas & Aset',
            'title_highlight' => 'PPI Curug',
            'desc'            => 'Dari full-flight simulator Boeing & Airbus, gedung serbaguna, pesawat latih, hingga laboratorium dan klinik — semua aset kami dapat disewa untuk kebutuhan pelatihan, event, riset, dan operasional Anda.',
            'btn_text'        => 'Lihat Kategori Layanan →',
            'btn_url'         => 'url:%23kategori',
            'hero_image'      => '',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Parsing URL Tombol
    $link = ( '||' !== $atts['btn_url'] ) ? vc_build_link( $atts['btn_url'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#kategori';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';

    // Parsing Gambar
    // Jika tidak ada gambar yang diunggah via WPBakery, gunakan placeholder abu-abu
    $img_url = 'https://placehold.co/800x500/e2e8f0/64748b?text=Upload+Gambar+di+Settings'; 
    if ( ! empty( $atts['hero_image'] ) ) {
        $img_src = wp_get_attachment_image_url( $atts['hero_image'], 'full' );
        if ( $img_src ) {
            $img_url = $img_src;
        }
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'hero-rental ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container hero-rental-container">
            
            <!-- Kolom Kiri: Konten Teks -->
            <div class="hero-rental-text">
                <h1>
                    <?php echo esc_html( $atts['title_main'] ); ?> 
                    <?php if ( ! empty( $atts['title_highlight'] ) ) : ?>
                        <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
                    <?php endif; ?>
                </h1>
                
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                <?php endif; ?>
                
                <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $a_href ); ?>" class="btn-rental-cta"<?php echo $a_target; ?>>
                        <?php echo esc_html( $atts['btn_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Kolom Kanan: Gambar -->
            <div class="hero-rental-image">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="Fasilitas PPI Curug" loading="lazy" />
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_rental_hero_element' );
function ppic_register_rental_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Rental - Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_rental_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-building',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Putih)', 'ppic-custom-element' ),
                    'param_name' => 'title_main',
                    'value'      => 'Sewa Fasilitas & Aset',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Aksen (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'PPI Curug',
                    'description'=> __( 'Teks ini akan otomatis memiliki warna kuning dan berada di baris baru.', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Dari full-flight simulator Boeing & Airbus, gedung serbaguna, pesawat latih, hingga laboratorium dan klinik — semua aset kami dapat disewa untuk kebutuhan pelatihan, event, riset, dan operasional Anda.',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Lihat Kategori Layanan →',
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_url',
                    'value'      => 'url:%23kategori',
                ),
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Hero', 'ppic-custom-element' ),
                    'param_name'  => 'hero_image',
                    'description' => __( 'Pilih gambar fasilitas dari Media Library.', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
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