<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_register_news_grid_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => __( "PPIC News Grid", "ppic" ),
            "base" => "ppic_news_grid",
            "category" => __( "PPIC Elements", "ppic" ),
            "icon" => "dashicons dashicons-grid-view", 
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __( "Judul Section", "ppic" ),
                    "param_name" => "title",
                    "value" => "Kabar Dari Langit Curug",
                    "admin_label" => true
                ),
                array(
                    "type" => "textarea",
                    "heading" => __( "Deskripsi / Sub-Judul", "ppic" ),
                    "param_name" => "subtitle",
                    "value" => "Ikuti perkembangan terbaru, inovasi, dan kontribusi PPI Curug dalam membangun ekosistem penerbangan yang aman, profesional, dan berdaya saing internasional.",
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Jumlah Berita", "ppic" ),
                    "param_name" => "posts_count",
                    "value" => "3"
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __( "Autoplay Slider", "ppic" ),
                    "param_name" => "autoplay",
                    "value" => array(
                        __( "Aktif", "ppic" ) => "true",
                        __( "Nonaktif", "ppic" ) => "false",
                    ),
                    "std" => "true"
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __( "Auto Loop", "ppic" ),
                    "param_name" => "autoloop",
                    "value" => array(
                        __( "Aktif", "ppic" ) => "true",
                        __( "Nonaktif", "ppic" ) => "false",
                    ),
                    "std" => "true"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Durasi Autoplay (ms)", "ppic" ),
                    "param_name" => "autoplay_interval",
                    "value" => "4500"
                ),
                array(
                    "type" => "el_id",
                    "heading" => __( "Element ID", "js_composer" ),
                    "param_name" => "el_id",
                    "description" => __( "Enter element ID (Note: make sure it is unique and valid according to w3c specification).", "js_composer" )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Extra class name", "js_composer" ),
                    "param_name" => "el_class",
                    "description" => __( "Style particular content element differently by adding a class name and referring to it in custom CSS.", "js_composer" )
                ),
            )
        ) );
    }
}
add_action( 'vc_before_init', 'ppic_register_news_grid_element' );

function ppic_news_grid_render( $atts ) {
   $atts = shortcode_atts( array(
        'title'       => 'Kabar Dari Langit Curug',
        'subtitle'    => 'Ikuti perkembangan terbaru, inovasi, dan kontribusi PPI Curug dalam membangun ekosistem penerbangan yang aman, profesional, dan berdaya saing internasional.',
        'posts_count' => '3',
        'autoplay'    => 'true',
        'autoloop'    => 'true',
        'autoplay_interval' => '4500',
        'el_id'       => '',
        'el_class'    => '',
    ), $atts );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-news-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    $autoplay_enabled = 'true' === strtolower( trim( (string) $atts['autoplay'] ) );
    $autoloop_enabled = 'true' === strtolower( trim( (string) $atts['autoloop'] ) );
    $autoplay_interval = max( 1000, (int) $atts['autoplay_interval'] );
    $slider_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-news-slider-' ) : uniqid( 'ppic-news-slider-' );

    ob_start();
    ?>
    <div<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-news-header">
            <h2 class="ppic-news-main-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-news-sub-title"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>
        </div>

        <?php
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => intval( $atts['posts_count'] ),
            'post_status'    => 'publish',
        );
        $news_query = new WP_Query( $args );
        $news_items = array();

        if ( $news_query->have_posts() ) {
            while ( $news_query->have_posts() ) {
                $news_query->the_post();

                $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                if ( ! $thumb_url ) {
                    $thumb_url = 'https://via.placeholder.com/600x400?text=PPI+Curug';
                }

                $news_items[] = array(
                    'title' => get_the_title(),
                    'excerpt' => wp_trim_words( get_the_excerpt(), 18, '...' ),
                    'permalink' => get_permalink(),
                    'thumbnail' => $thumb_url,
                );
            }
            wp_reset_postdata();
        }
        ?>

        <?php if ( ! empty( $news_items ) ) : ?>
            <div
                id="<?php echo esc_attr( $slider_id ); ?>"
                class="ppic-news-slider<?php echo count( $news_items ) > 1 ? ' is-ready' : ' is-single'; ?>"
                data-news-slider
                data-autoplay="<?php echo count( $news_items ) > 1 && $autoplay_enabled ? 'true' : 'false'; ?>"
                data-loop="<?php echo $autoloop_enabled ? 'true' : 'false'; ?>"
                data-interval="<?php echo esc_attr( $autoplay_interval ); ?>"
            >
                <div class="ppic-news-slider__viewport">
                    <div class="ppic-news-slider__track">
                        <?php foreach ( $news_items as $index => $news_item ) : ?>
                            <article class="ppic-news-card<?php echo 0 === $index ? ' is-active' : ''; ?>">
                                <div class="ppic-news-img-wrap">
                                    <img src="<?php echo esc_url( $news_item['thumbnail'] ); ?>" alt="<?php echo esc_attr( $news_item['title'] ); ?>">
                                </div>
                                <div class="ppic-news-content">
                                    <h4><?php echo esc_html( $news_item['title'] ); ?></h4>
                                    <p><?php echo esc_html( $news_item['excerpt'] ); ?></p>
                                    <a href="<?php echo esc_url( $news_item['permalink'] ); ?>" class="ppic-news-readmore">Selengkapnya &rarr;</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ( count( $news_items ) > 1 ) : ?>
                    <button type="button" class="ppic-news-slider__arrow is-prev" data-slide="prev" aria-label="Slide berita sebelumnya">
                        <span aria-hidden="true">&#10094;</span>
                    </button>
                    <button type="button" class="ppic-news-slider__arrow is-next" data-slide="next" aria-label="Slide berita berikutnya">
                        <span aria-hidden="true">&#10095;</span>
                    </button>
                    <div class="ppic-news-slider__dots" aria-label="Navigasi slider berita"></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php

    static $script_printed = false;

    if ( ! $script_printed ) {
        $script_printed = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var sliders = document.querySelectorAll('[data-news-slider]');

                sliders.forEach(function (slider) {
                    var viewport = slider.querySelector('.ppic-news-slider__viewport');
                    var track = slider.querySelector('.ppic-news-slider__track');
                    var slides = slider.querySelectorAll('.ppic-news-card');
                    var dotsWrap = slider.querySelector('.ppic-news-slider__dots');
                    var prevButton = slider.querySelector('[data-slide="prev"]');
                    var nextButton = slider.querySelector('[data-slide="next"]');
                    var dots = [];
                    var currentIndex = 0;
                    var autoplayId = null;
                    var resizeFrame = null;
                    var slideOffsets = [];
                    var maxTranslate = 0;
                    var isLoop = slider.dataset.loop === 'true';
                    var autoplayInterval = parseInt(slider.dataset.interval, 10) || 4500;

                    if (!viewport || !track || slides.length <= 1) {
                        return;
                    }

                    var getVisibleSlides = function () {
                        if (window.innerWidth <= 768) {
                            return 1;
                        }

                        if (window.innerWidth <= 991) {
                            return 2;
                        }

                        return 3;
                    };

                    var getMaxIndex = function () {
                        return Math.max(0, slides.length - getVisibleSlides());
                    };

                    var measureSlides = function () {
                        slideOffsets = Array.prototype.map.call(slides, function (slide) {
                            return slide.offsetLeft;
                        });
                        maxTranslate = Math.max(0, track.scrollWidth - viewport.clientWidth);
                    };

                    var normalizeIndex = function (index) {
                        var maxIndex = getMaxIndex();

                        if (isLoop) {
                            if (maxIndex <= 0) {
                                return 0;
                            }

                            if (index < 0) {
                                return maxIndex;
                            }

                            if (index > maxIndex) {
                                return 0;
                            }

                            return index;
                        }

                        if (index < 0) {
                            return 0;
                        }

                        if (index > maxIndex) {
                            return maxIndex;
                        }

                        return index;
                    };

                    var renderDots = function () {
                        if (!dotsWrap) {
                            return;
                        }

                        var dotCount = getMaxIndex() + 1;
                        dotsWrap.innerHTML = '';
                        dots = [];

                        if (dotCount <= 1) {
                            dotsWrap.style.display = 'none';
                            return;
                        }

                        dotsWrap.style.display = '';

                        for (var dotIndex = 0; dotIndex < dotCount; dotIndex++) {
                            var dot = document.createElement('button');
                            dot.type = 'button';
                            dot.className = 'ppic-news-slider__dot';
                            dot.setAttribute('data-slide-to', String(dotIndex));
                            dot.setAttribute('aria-label', 'Tampilkan slide berita ' + (dotIndex + 1));
                            dot.addEventListener('click', function () {
                                setActiveSlide(parseInt(this.getAttribute('data-slide-to'), 10) || 0);
                                startAutoplay();
                            });
                            dotsWrap.appendChild(dot);
                            dots.push(dot);
                        }
                    };

                    var setActiveSlide = function (index) {
                        currentIndex = normalizeIndex(index);
                        var translateValue = slideOffsets[currentIndex] ? Math.min(slideOffsets[currentIndex], maxTranslate) : 0;
                        track.style.transform = 'translateX(-' + translateValue + 'px)';

                        dots.forEach(function (dot, dotIndex) {
                            dot.classList.toggle('is-active', dotIndex === currentIndex);
                        });

                        if (prevButton) {
                            prevButton.disabled = !isLoop && currentIndex <= 0;
                        }

                        if (nextButton) {
                            nextButton.disabled = !isLoop && currentIndex >= getMaxIndex();
                        }
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
                            if (!isLoop && currentIndex >= getMaxIndex()) {
                                stopAutoplay();
                                return;
                            }

                            setActiveSlide(currentIndex + 1);
                        }, autoplayInterval);
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

                    slider.addEventListener('mouseenter', stopAutoplay);
                    slider.addEventListener('mouseleave', startAutoplay);
                    slider.addEventListener('focusin', stopAutoplay);
                    slider.addEventListener('focusout', startAutoplay);

                    window.addEventListener('resize', function () {
                        if (resizeFrame) {
                            window.cancelAnimationFrame(resizeFrame);
                        }

                        resizeFrame = window.requestAnimationFrame(function () {
                            measureSlides();
                            renderDots();
                            setActiveSlide(currentIndex);
                        });
                    });

                    measureSlides();
                    renderDots();
                    setActiveSlide(0);
                    startAutoplay();
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}
add_shortcode( 'ppic_news_grid', 'ppic_news_grid_render' );