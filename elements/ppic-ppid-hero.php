<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_hero', 'ppic_ppid_hero_render' );
function ppic_ppid_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'slides'   => '',
            'el_id'    => '',
            'el_class' => '',
        ),
        $atts
    );

    $slides = vc_param_group_parse_atts( $atts['slides'] );

    if ( empty( $slides ) || ! is_array( $slides ) ) {
        return '';
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-ppid-hero ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    
    // Generate unique ID untuk JavaScript
    $carousel_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-ppid-hero-' ) : uniqid( 'ppic-ppid-hero-' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" data-carousel-id="<?php echo esc_attr( $carousel_id ); ?>">
        <div class="ppic-ppid-hero-slides" id="<?php echo esc_attr( $carousel_id ); ?>-slides">
            <?php foreach ( $slides as $index => $slide ) : 
                $title       = isset( $slide['title'] ) ? trim( $slide['title'] ) : '';
                $desc        = isset( $slide['desc'] ) ? trim( $slide['desc'] ) : '';
                $btn1_label  = isset( $slide['btn1_label'] ) ? trim( $slide['btn1_label'] ) : '';
                $btn1_url    = isset( $slide['btn1_url'] ) ? trim( $slide['btn1_url'] ) : '';
                $btn2_label  = isset( $slide['btn2_label'] ) ? trim( $slide['btn2_label'] ) : '';
                $btn2_url    = isset( $slide['btn2_url'] ) ? trim( $slide['btn2_url'] ) : '';
                
                // Logika Background Image (Mengutamakan Media Library, jika kosong pakai fallback URL)
                $bg_image_id = isset( $slide['bg_image'] ) ? trim( $slide['bg_image'] ) : '';
                $bg_fallback = isset( $slide['bg_image_url'] ) ? trim( $slide['bg_image_url'] ) : '';
                $bg_url = '';
                
                if ( ! empty( $bg_image_id ) ) {
                    $img_src = wp_get_attachment_image_src( $bg_image_id, 'full' );
                    if ( $img_src ) {
                        $bg_url = $img_src[0];
                    }
                }
                
                if ( empty( $bg_url ) && ! empty( $bg_fallback ) ) {
                    $bg_url = $bg_fallback;
                }
                ?>
                <div class="ppic-ppid-hero-slide" style="background-image: url('<?php echo esc_url( $bg_url ); ?>');">
                    <div class="ppic-ppid-hero-overlay"></div>
                    <div class="ppic-ppid-hero-container">
                        <h1><?php echo esc_html( $title ); ?></h1>
                        <p><?php echo esc_html( $desc ); ?></p>
                        <div class="ppic-ppid-hero-buttons">
                            <?php if ( ! empty( $btn1_label ) ) : ?>
                                <a href="<?php echo esc_url( ! empty( $btn1_url ) ? $btn1_url : '#' ); ?>" class="ppic-ppid-hero-btn ppic-ppid-hero-btn-primary">
                                    <?php echo esc_html( $btn1_label ); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $btn2_label ) ) : ?>
                                <a href="<?php echo esc_url( ! empty( $btn2_url ) ? $btn2_url : '#' ); ?>" class="ppic-ppid-hero-btn ppic-ppid-hero-btn-secondary">
                                    <?php echo esc_html( $btn2_label ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ( count( $slides ) > 1 ) : ?>
            <button class="ppic-ppid-hero-arrow ppic-ppid-hero-arrow-left" aria-label="Previous Slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="ppic-ppid-hero-arrow ppic-ppid-hero-arrow-right" aria-label="Next Slide">
                <i class="fas fa-chevron-right"></i>
            </button>

            <div class="ppic-ppid-hero-controls">
                <?php foreach ( $slides as $index => $slide ) : ?>
                    <button class="ppic-ppid-hero-dot <?php echo $index === 0 ? 'is-active' : ''; ?>" data-slide="<?php echo esc_attr( $index ); ?>" aria-label="Go to slide <?php echo esc_attr( $index + 1 ); ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php
    // Inline JS khusus untuk menjalankan slider, hanya diprint sekali
    static $ppic_hero_js_printed = false;
    if ( ! $ppic_hero_js_printed ) {
        $ppic_hero_js_printed = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var carousels = document.querySelectorAll('.ppic-ppid-hero');
                
                carousels.forEach(function(carousel) {
                    var slidesContainer = carousel.querySelector('.ppic-ppid-hero-slides');
                    var slides = carousel.querySelectorAll('.ppic-ppid-hero-slide');
                    var dots = carousel.querySelectorAll('.ppic-ppid-hero-dot');
                    var prevBtn = carousel.querySelector('.ppic-ppid-hero-arrow-left');
                    var nextBtn = carousel.querySelector('.ppic-ppid-hero-arrow-right');
                    
                    var currentIndex = 0;
                    var totalSlides = slides.length;
                    
                    if (totalSlides <= 1) return;

                    function updateSlide(index) {
                        slidesContainer.style.transform = 'translateX(-' + (index * 100) + '%)';
                        dots.forEach(function(dot, i) {
                            dot.classList.toggle('is-active', i === index);
                        });
                        currentIndex = index;
                    }

                    function nextSlide() {
                        var nextIndex = (currentIndex + 1) % totalSlides;
                        updateSlide(nextIndex);
                    }

                    function prevSlide() {
                        var prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                        updateSlide(prevIndex);
                    }

                    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
                    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

                    dots.forEach(function(dot) {
                        dot.addEventListener('click', function() {
                            var targetIndex = parseInt(this.getAttribute('data-slide'));
                            updateSlide(targetIndex);
                        });
                    });

                    // Autoplay Opsional (Ganti 5000 menjadi waktu dalam ms yang Anda inginkan)
                    var autoplayInterval = setInterval(nextSlide, 6000);
                    
                    // Pause saat hover
                    carousel.addEventListener('mouseenter', function() {
                        clearInterval(autoplayInterval);
                    });
                    
                    carousel.addEventListener('mouseleave', function() {
                        autoplayInterval = setInterval(nextSlide, 6000);
                    });
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_hero_element' );
function ppic_register_ppid_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_data = array(
        array(
            'bg_image_url' => 'https://images.unsplash.com/photo-1573164713988-9665fc9c2c6e?w=1200&q=80',
            'title'        => 'Transparansi Adalah Hak Publik',
            'desc'         => 'PPID memastikan setiap masyarakat memperoleh akses informasi publik secara cepat, tepat, dan akuntabel sesuai peraturan perundang-undangan.',
            'btn1_label'   => 'Ajukan Permintaan Informasi',
            'btn1_url'     => 'https://docs.google.com/forms/d/e/1FAIpQLSc3DdIFtG2bNPwmAVHNUyucoZ5bhLhep9zcZiKpJptAjye5LA/viewform',
            'btn2_label'   => 'Lihat Daftar Informasi Publik',
            'btn2_url'     => '#'
        ),
        array(
            'bg_image_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200&q=80',
            'title'        => 'Sampaikan Pengaduan Anda melalui SP4N-LAPOR!',
            'desc'         => 'Laporkan pelayanan publik yang tidak sesuai melalui sistem pengaduan nasional yang terintegrasi dan terpercaya.',
            'btn1_label'   => 'Akses SP4N-LAPOR!',
            'btn1_url'     => '#',
            'btn2_label'   => '',
            'btn2_url'     => ''
        ),
        array(
            'bg_image_url' => 'https://images.unsplash.com/photo-1508780709618-5e8c17f71b8c?w=1200&q=80',
            'title'        => 'Tolak Gratifikasi. Jaga Integritas.',
            'desc'         => 'Laporkan penerimaan gratifikasi untuk menjaga integritas dan mencegah praktik korupsi di lingkungan instansi.',
            'btn1_label'   => 'Laporkan Gratifikasi',
            'btn1_url'     => '#',
            'btn2_label'   => 'Pelajari Mekanisme Pelaporan',
            'btn2_url'     => '#'
        ),
        array(
            'bg_image_url' => 'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?w=1200&q=80',
            'title'        => 'SIMADU Kemenhub: Layanan Pengaduan Terintegrasi',
            'desc'         => 'Sistem Informasi Manajemen Pengaduan Kementerian Perhubungan. Sampaikan pengaduan, aspirasi, dan permintaan informasi Anda melalui SIMADU.',
            'btn1_label'   => 'Akses SIMADU',
            'btn1_url'     => '#',
            'btn2_label'   => 'Pelajari Lebih Lanjut',
            'btn2_url'     => '#'
        )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Hero Carousel', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-images-alt2',
            'params'   => array(
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Slide', 'ppic-custom-element' ),
                    'param_name' => 'slides',
                    'value'      => urlencode( wp_json_encode( $dummy_data ) ),
                    'params'     => array(
                        array(
                            'type'        => 'attach_image',
                            'heading'     => __( 'Background Image', 'ppic-custom-element' ),
                            'param_name'  => 'bg_image',
                            'description' => 'Pilih gambar dari Media Library.',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Fallback Image URL (Opsional)', 'ppic-custom-element' ),
                            'param_name'  => 'bg_image_url',
                            'description' => 'Gunakan ini jika ingin memakai URL luar (contoh untuk dummy data). Jika Background Image di atas diisi, kolom ini diabaikan.',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Judul Utama', 'ppic-custom-element' ),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                            'param_name' => 'desc',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Teks Tombol 1 (Kuning)', 'ppic-custom-element' ),
                            'param_name' => 'btn1_label',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Tombol 1', 'ppic-custom-element' ),
                            'param_name' => 'btn1_url',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Teks Tombol 2 (Outline)', 'ppic-custom-element' ),
                            'param_name' => 'btn2_label',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Tombol 2', 'ppic-custom-element' ),
                            'param_name' => 'btn2_url',
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