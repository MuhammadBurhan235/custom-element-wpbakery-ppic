<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_klinik_hero', 'ppic_klinik_hero_render' );
function ppic_klinik_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'            => 'Klinik',
            'highlight_title'  => 'PPI Curug',
            'subtitle'         => 'Kesehatan Prima, Siap Mengudara.',
            'desc'             => 'Layanan kesehatan terpercaya bagi taruna, sivitas akademika, dan masyarakat umum. Didukung tenaga medis profesional, fasilitas yang nyaman, serta melayani pasien umum dan BPJS Kesehatan. Kami hadir untuk memberikan pelayanan kesehatan yang cepat, berkualitas, dan berorientasi pada kebutuhan pasien.',
            
            // Button 1 (Kontak)
            'btn1_text'        => 'Kontak',
            'btn1_icon'        => 'fas fa-phone-alt',
            'btn1_link'        => 'url:%23kontak|||',
            
            // Button 2 (Layanan)
            'btn2_text'        => 'Layanan',
            'btn2_icon'        => 'fas fa-calendar-check',
            'btn2_link'        => 'url:%23layanan|||',
            
            // Slider Images
            'slider_images'    => '',
            
            'el_class'         => '',
        ),
        $atts
    );

    $wrapper_class = 'ppic-clinic-hero' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    $unique_id = 'clinicSlider_' . uniqid();

    // Parse Links
    $btn1_link = vc_build_link( $atts['btn1_link'] );
    $btn1_url  = ! empty( $btn1_link['url'] ) ? $btn1_link['url'] : '#kontak';
    $btn1_tgt  = ! empty( $btn1_link['target'] ) ? ' target="' . esc_attr( $btn1_link['target'] ) . '"' : '';

    $btn2_link = vc_build_link( $atts['btn2_link'] );
    $btn2_url  = ! empty( $btn2_link['url'] ) ? $btn2_link['url'] : '#layanan';
    $btn2_tgt  = ! empty( $btn2_link['target'] ) ? ' target="' . esc_attr( $btn2_link['target'] ) . '"' : '';

    // Parse Images
    $images_arr = array();
    if ( ! empty( $atts['slider_images'] ) ) {
        $image_ids = explode( ',', $atts['slider_images'] );
        foreach ( $image_ids as $img_id ) {
            $img_url = wp_get_attachment_image_url( $img_id, 'large' );
            if ( $img_url ) {
                $images_arr[] = $img_url;
            }
        }
    }
    
    // Fallback Dummy Images[cite: 27]
    if ( empty( $images_arr ) ) {
        $images_arr = array(
            'https://lh3.googleusercontent.com/d/14ZMeSYCLTRXFDfenNnI4nYBGAKYzC6KD',
            'https://lh3.googleusercontent.com/d/1qZG13kjmNYVXXlgDpcY1BN0-k26qJ5jb',
            'https://ppicurug.ac.id/wp-content/uploads/2024/08/klinik-ppic.jpg'
        );
    }

    ob_start(); ?>
    
    <section class="<?php echo $wrapper_class; ?>">
        <div class="ppic-clinic-container">
            
            <div class="clinic-hero-text">
                <h1><?php echo esc_html( $atts['title'] ); ?> <span><?php echo esc_html( $atts['highlight_title'] ); ?></span></h1>
                
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <?php endif; ?>
                
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                <?php endif; ?>

                <div class="hero-quick-access">
                    <?php if ( ! empty( $atts['btn1_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $btn1_url ); ?>" <?php echo $btn1_tgt; ?> class="btn-quick">
                            <?php if ( ! empty( $atts['btn1_icon'] ) ) : ?><i class="<?php echo esc_attr( $atts['btn1_icon'] ); ?>"></i><?php endif; ?>
                            <?php echo esc_html( $atts['btn1_text'] ); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $atts['btn2_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $btn2_url ); ?>" <?php echo $btn2_tgt; ?> class="btn-quick primary">
                            <?php if ( ! empty( $atts['btn2_icon'] ) ) : ?><i class="<?php echo esc_attr( $atts['btn2_icon'] ); ?>"></i><?php endif; ?>
                            <?php echo esc_html( $atts['btn2_text'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="clinic-hero-image" id="<?php echo esc_attr( $unique_id ); ?>">
                <?php foreach ( $images_arr as $index => $img_src ) : ?>
                    <img class="slide <?php echo ( $index === 0 ) ? 'active' : ''; ?>" src="<?php echo esc_url( $img_src ); ?>" alt="Klinik Image <?php echo ( $index + 1 ); ?>" />
                <?php endforeach; ?>
                
                <?php if ( count( $images_arr ) > 1 ) : ?>
                    <div class="hero-slider-controls">
                        <?php foreach ( $images_arr as $index => $img_src ) : ?>
                            <button class="dot <?php echo ( $index === 0 ) ? 'active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>" aria-label="Slide <?php echo ( $index + 1 ); ?>"></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <?php if ( count( $images_arr ) > 1 ) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliderWrapper = document.getElementById('<?php echo esc_js( $unique_id ); ?>');
            if ( !sliderWrapper ) return;

            const slides = sliderWrapper.querySelectorAll('.slide');
            const dots = sliderWrapper.querySelectorAll('.dot');
            let currentIndex = 0;
            let slideInterval;

            function goToSlide(index) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentIndex = index;
            }

            function nextSlide() {
                let next = (currentIndex + 1) % slides.length;
                goToSlide(next);
            }

            // Click dots
            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    clearInterval(slideInterval);
                    const idx = parseInt(this.getAttribute('data-index'));
                    goToSlide(idx);
                    startAutoSlide();
                });
            });

            function startAutoSlide() {
                slideInterval = setInterval(nextSlide, 4000); // Ganti gambar tiap 4 detik
            }

            startAutoSlide();
        });
    </script>
    <?php endif; ?>

    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_klinik_hero_element' );
function ppic_register_klinik_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Klinik Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_klinik_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-heart',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Klinik',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul Tersorot (Kuning)', 'ppic-custom-element' ),
                    'param_name'  => 'highlight_title',
                    'value'       => 'PPI Curug',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Subtitle', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Kesehatan Prima, Siap Mengudara.',
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name'  => 'desc',
                    'value'       => 'Layanan kesehatan terpercaya bagi taruna, sivitas akademika, dan masyarakat umum. Didukung tenaga medis profesional, fasilitas yang nyaman, serta melayani pasien umum dan BPJS Kesehatan. Kami hadir untuk memberikan pelayanan kesehatan yang cepat, berkualitas, dan berorientasi pada kebutuhan pasien.',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol 1', 'ppic-custom-element' ),
                    'param_name'  => 'btn1_text',
                    'value'       => 'Kontak',
                    'group'       => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Tombol 1', 'ppic-custom-element' ),
                    'param_name'  => 'btn1_icon',
                    'value'       => 'fas fa-phone-alt',
                    'group'       => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'vc_link',
                    'heading'     => __( 'Tautan Tombol 1', 'ppic-custom-element' ),
                    'param_name'  => 'btn1_link',
                    'group'       => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol 2 (Primary)', 'ppic-custom-element' ),
                    'param_name'  => 'btn2_text',
                    'value'       => 'Layanan',
                    'group'       => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Tombol 2', 'ppic-custom-element' ),
                    'param_name'  => 'btn2_icon',
                    'value'       => 'fas fa-calendar-check',
                    'group'       => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'vc_link',
                    'heading'     => __( 'Tautan Tombol 2', 'ppic-custom-element' ),
                    'param_name'  => 'btn2_link',
                    'group'       => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'attach_images',
                    'heading'     => __( 'Gambar Slider (Pilih beberapa)', 'ppic-custom-element' ),
                    'param_name'  => 'slider_images',
                    'description' => __( 'Pilih atau unggah beberapa gambar untuk slider sisi kanan.', 'ppic-custom-element' ),
                    'group'       => __( 'Media', 'ppic-custom-element' ),
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