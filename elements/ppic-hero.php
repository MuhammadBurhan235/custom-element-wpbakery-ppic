<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// 1. Fungsi untuk me-render HTML di Frontend (Shortcode)
add_shortcode('ppic_hero_section', 'ppic_hero_section_render');
function ppic_hero_section_render($atts) {
    // Parameter default
    $atts = shortcode_atts(array(
        'title'       => 'Tempat Pemimpin Penerbangan Dilahirkan.',
        'description' => 'Di Politeknik Penerbangan Indonesia Curug, kami tidak hanya mendidik...',
        'btn_text'    => 'Jelajahi Program Kami',
        'btn_link'    => '',
        'images'      => '',
        'el_id'       => '',
        'el_class'    => '',
        'image'       => ''
    ), $atts);

    // Parsing parameter Link bawaan WPBakery
    $link = vc_build_link($atts['btn_link']);
    $a_href = !empty($link['url']) ? $link['url'] : '#';
    $a_target = !empty($link['target']) ? ' target="'.trim($link['target']).'"' : '';

    // Parsing kumpulan gambar dari Media Library dengan fallback ke field lama.
    $default_img_url = 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg';
    $image_urls = array();
    $image_ids = array_filter(array_map('absint', explode(',', $atts['images'])));

    if (empty($image_ids) && !empty($atts['image'])) {
        $image_ids[] = absint($atts['image']);
    }

    foreach ($image_ids as $image_id) {
        $img_data = wp_get_attachment_image_src($image_id, 'full');
        if ($img_data && !empty($img_data[0])) {
            $image_urls[] = $img_data[0];
        }
    }

    if (empty($image_urls)) {
        $image_urls[] = $default_img_url;
    }

    $slider_id = function_exists('wp_unique_id') ? wp_unique_id('ppic-hero-slider-') : uniqid('ppic-hero-slider-');
    $has_multiple_slides = count($image_urls) > 1;
    $wrapper_id = !empty($atts['el_id']) ? ' id="' . esc_attr($atts['el_id']) . '"' : '';
    $wrapper_class = 'ppic-hero-section' . ( !empty($atts['el_class']) ? ' ' . esc_attr(trim($atts['el_class'])) : '' );

    static $script_printed = false;

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-hero-container">
            <div class="ppic-hero-text">
                <h2><?php echo esc_html($atts['title']); ?></h2>
                <p><?php echo esc_html($atts['description']); ?></p>
                <div class="ppic-hero-buttons">
                    <a href="<?php echo esc_url($a_href); ?>" <?php echo $a_target; ?> class="ppic-btn-primary">
                        <?php echo esc_html($atts['btn_text']); ?>
                    </a>
                </div>
            </div>
            <div class="ppic-hero-image">
                <div
                    id="<?php echo esc_attr($slider_id); ?>"
                    class="ppic-hero-slider<?php echo $has_multiple_slides ? ' is-ready' : ' is-single'; ?>"
                    data-slider
                    data-autoplay="<?php echo $has_multiple_slides ? 'true' : 'false'; ?>"
                >
                    <div class="ppic-hero-slider-viewport">
                        <div class="ppic-hero-slider-track">
                            <?php foreach ($image_urls as $index => $image_url) : ?>
                                <div class="ppic-hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>">
                                    <img
                                        src="<?php echo esc_url($image_url); ?>"
                                        alt="<?php echo esc_attr(sprintf('Hero Image PPI Curug %d', $index + 1)); ?>"
                                    >
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ($has_multiple_slides) : ?>
                        <button type="button" class="ppic-hero-slider-arrow is-prev" data-slide="prev" aria-label="Slide sebelumnya">
                            <span aria-hidden="true">&#10094;</span>
                        </button>
                        <button type="button" class="ppic-hero-slider-arrow is-next" data-slide="next" aria-label="Slide berikutnya">
                            <span aria-hidden="true">&#10095;</span>
                        </button>

                        <div class="ppic-hero-slider-dots" aria-label="Navigasi slide hero">
                            <?php foreach ($image_urls as $index => $image_url) : ?>
                                <button
                                    type="button"
                                    class="ppic-hero-slider-dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
                                    data-slide-to="<?php echo esc_attr($index); ?>"
                                    aria-label="Tampilkan slide <?php echo esc_attr($index + 1); ?>"
                                ></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php

    if (!$script_printed) {
        $script_printed = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var sliders = document.querySelectorAll('[data-slider]');

                sliders.forEach(function (slider) {
                    var track = slider.querySelector('.ppic-hero-slider-track');
                    var slides = slider.querySelectorAll('.ppic-hero-slide');
                    var dots = slider.querySelectorAll('[data-slide-to]');
                    var prevButton = slider.querySelector('[data-slide="prev"]');
                    var nextButton = slider.querySelector('[data-slide="next"]');
                    var currentIndex = 0;
                    var autoplayId = null;

                    if (!track || slides.length <= 1) {
                        return;
                    }

                    var setActiveSlide = function (index) {
                        currentIndex = (index + slides.length) % slides.length;
                        track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

                        slides.forEach(function (slide, slideIndex) {
                            slide.classList.toggle('is-active', slideIndex === currentIndex);
                        });

                        dots.forEach(function (dot, dotIndex) {
                            dot.classList.toggle('is-active', dotIndex === currentIndex);
                        });
                    };

                    var stopAutoplay = function () {
                        if (autoplayId) {
                            window.clearInterval(autoplayId);
                            autoplayId = null;
                        }
                    };

                    var startAutoplay = function () {
                        if (slider.dataset.autoplay !== 'true') {
                            return;
                        }

                        stopAutoplay();
                        autoplayId = window.setInterval(function () {
                            setActiveSlide(currentIndex + 1);
                        }, 4500);
                    };

                    if (prevButton) {
                        prevButton.addEventListener('click', function () {
                            setActiveSlide(currentIndex - 1);
                            startAutoplay();
                        });
                    }

                    if (nextButton) {
                        nextButton.addEventListener('click', function () {
                            setActiveSlide(currentIndex + 1);
                            startAutoplay();
                        });
                    }

                    dots.forEach(function (dot) {
                        dot.addEventListener('click', function () {
                            setActiveSlide(parseInt(dot.getAttribute('data-slide-to'), 10) || 0);
                            startAutoplay();
                        });
                    });

                    slider.addEventListener('mouseenter', stopAutoplay);
                    slider.addEventListener('mouseleave', startAutoplay);
                    slider.addEventListener('focusin', stopAutoplay);
                    slider.addEventListener('focusout', startAutoplay);

                    setActiveSlide(0);
                    startAutoplay();
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

// 2. Mendaftarkan Elemen ke dalam UI WPBakery
add_action('vc_before_init', 'ppic_hero_section_map');
function ppic_hero_section_map() {
    vc_map(array(
        "name" => __("PPIC Hero", "my-text-domain"),
        "base" => "ppic_hero_section",
        "category" => __("PPIC Elements", "my-text-domain"), // Akan membuat tab kategori baru di WPBakery
        "icon" => "dashicons dashicons-align-left", // Ikon yang muncul di WPBakery
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Judul Hero", "my-text-domain"),
                "param_name" => "title",
                "value" => "Tempat Pemimpin Penerbangan Dilahirkan.",
                "admin_label" => true,
            ),
            array(
                "type" => "textarea",
                "heading" => __("Deskripsi", "my-text-domain"),
                "param_name" => "description",
            ),
            array(
                "type" => "textfield",
                "heading" => __("Teks Tombol", "my-text-domain"),
                "param_name" => "btn_text",
                "value" => "Jelajahi Program Kami",
            ),
            array(
                "type" => "vc_link",
                "heading" => __("Link Tombol", "my-text-domain"),
                "param_name" => "btn_link",
                "description" => __("Pilih halaman tujuan saat tombol diklik.", "my-text-domain"),
            ),
            array(
                "type" => "attach_images",
                "heading" => __("Slide Hero", "my-text-domain"),
                "param_name" => "images",
                "description" => __("Upload beberapa gambar untuk slider di sisi kanan. Semua slide akan memakai ukuran yang sama.", "my-text-domain"),
            ),
            array(
                "type" => "el_id",
                "heading" => __("Element ID", "js_composer"),
                "param_name" => "el_id",
                "description" => __("Enter element ID (Note: make sure it is unique and valid according to w3c specification).", "js_composer"),
            ),
            array(
                "type" => "textfield",
                "heading" => __("Extra class name", "js_composer"),
                "param_name" => "el_class",
                "description" => __("Style particular content element differently by adding a class name and referring to it in custom CSS.", "js_composer"),
            ),
        )
    ));
}