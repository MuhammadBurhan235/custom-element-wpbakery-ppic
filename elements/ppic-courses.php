<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'ppic_courses', 'ppic_courses_render' );
function ppic_courses_render( $atts ) {
	$atts = shortcode_atts(
		array(
			'title' => 'Our Short Courses',
			'subtitle' => 'Kegiatan Diklat Pendek di Politeknik Penerbangan Indonesia Curug',
			'button_text' => 'View All Courses',
			'button_link' => 'url:https%3A%2F%2Fppicurug.ac.id%2Fshortcourse%2F|title:View All Courses',
			'label_text' => 'Diklat Pendek',
			'image_size' => 'medium',
			'autoplay' => 'true',
			'autoloop' => 'true',
			'autoplay_interval' => '4500',
			'post_type' => 'course',
			'posts_count' => '6',
			'order' => 'DESC',
			'orderby' => 'date',
			'excerpt_length' => '12',
			'el_id' => '',
			'el_class' => '',
		),
		$atts
	);

	$button_link = vc_build_link( $atts['button_link'] );
	$button_href = ! empty( $button_link['url'] ) ? $button_link['url'] : '';
	$button_target = ! empty( $button_link['target'] ) ? trim( $button_link['target'] ) : '';
	$button_rel = '_blank' === $button_target ? 'noopener noreferrer' : '';
	$post_type = preg_replace( '/[^a-z0-9_-]/i', '', (string) $atts['post_type'] );
	$orderby = preg_replace( '/[^a-z_]/i', '', (string) $atts['orderby'] );
	$allowed_image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large', 'full' );
	$image_size = in_array( $atts['image_size'], $allowed_image_sizes, true ) ? $atts['image_size'] : 'medium';
	$autoplay_enabled = 'true' === strtolower( trim( (string) $atts['autoplay'] ) );
	$autoloop_enabled = 'true' === strtolower( trim( (string) $atts['autoloop'] ) );
	$autoplay_interval = max( 1000, (int) $atts['autoplay_interval'] );

	$wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
	$wrapper_class = 'ppic-courses-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

	$query = new WP_Query(
		array(
			'post_type' => '' !== $post_type ? $post_type : 'course',
			'posts_per_page' => max( 1, intval( $atts['posts_count'] ) ),
			'order' => in_array( strtoupper( $atts['order'] ), array( 'ASC', 'DESC' ), true ) ? strtoupper( $atts['order'] ) : 'DESC',
			'orderby' => '' !== $orderby ? $orderby : 'date',
			'post_status' => 'publish',
		)
	);

	$course_items = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$post_id = get_the_ID();
			$thumbnail = '';
			$thumbnail_id = get_post_thumbnail_id( $post_id );

			if ( $thumbnail_id ) {
				$thumbnail_data = wp_get_attachment_image_src( $thumbnail_id, $image_size );

				if ( ! empty( $thumbnail_data[0] ) ) {
					$thumbnail = $thumbnail_data[0];
				}
			}

			if ( empty( $thumbnail ) ) {
				$thumbnail = 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg';
			}

			$course_items[] = array(
				'permalink' => get_permalink( $post_id ),
				'thumbnail' => $thumbnail,
				'title' => get_the_title( $post_id ),
				'excerpt' => wp_trim_words( get_the_excerpt(), max( 6, intval( $atts['excerpt_length'] ) ), '...' ),
			);
		}
		wp_reset_postdata();
	}

	$slider_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-courses-slider-' ) : uniqid( 'ppic-courses-slider-' );
	$has_multiple_slides = count( $course_items ) > 1;

	ob_start();
	?>
	<section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
		<div class="ppic-courses-container">
			<div class="ppic-courses-header">
				<?php if ( ! empty( $atts['title'] ) ) : ?>
					<h2 class="ppic-courses-title"><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $atts['subtitle'] ) ) : ?>
					<p class="ppic-courses-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $button_href ) && ! empty( $atts['button_text'] ) ) : ?>
					<a
						class="ppic-courses-button"
						href="<?php echo esc_url( $button_href ); ?>"
						<?php echo '' !== $button_target ? ' target="' . esc_attr( $button_target ) . '"' : ''; ?>
						<?php echo '' !== $button_rel ? ' rel="' . esc_attr( $button_rel ) . '"' : ''; ?>
					>
						<?php echo esc_html( $atts['button_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $course_items ) ) : ?>
				<div
					id="<?php echo esc_attr( $slider_id ); ?>"
					class="ppic-courses-slider<?php echo $has_multiple_slides ? ' is-ready' : ' is-single'; ?>"
					data-courses-slider
					data-autoplay="<?php echo $has_multiple_slides && $autoplay_enabled ? 'true' : 'false'; ?>"
					data-loop="<?php echo $autoloop_enabled ? 'true' : 'false'; ?>"
					data-interval="<?php echo esc_attr( $autoplay_interval ); ?>"
				>
					<div class="ppic-courses-slider__viewport">
						<div class="ppic-courses-slider__track">
							<?php foreach ( $course_items as $index => $course_item ) : ?>
								<article class="ppic-course-card<?php echo 0 === $index ? ' is-active' : ''; ?>">
									<a class="ppic-course-card__link" href="<?php echo esc_url( $course_item['permalink'] ); ?>">
										<div class="ppic-course-card__image-wrap">
											<img
												class="ppic-course-card__image"
												src="<?php echo esc_url( $course_item['thumbnail'] ); ?>"
												alt="<?php echo esc_attr( $course_item['title'] ); ?>"
											>
										</div>
										<div class="ppic-course-card__body">
											<?php if ( ! empty( $atts['label_text'] ) ) : ?>
												<span class="ppic-course-card__label"><?php echo esc_html( $atts['label_text'] ); ?></span>
											<?php endif; ?>
											<h3 class="ppic-course-card__title"><?php echo esc_html( $course_item['title'] ); ?></h3>
											<p class="ppic-course-card__excerpt"><?php echo esc_html( $course_item['excerpt'] ); ?></p>
										</div>
									</a>
								</article>
							<?php endforeach; ?>
						</div>
					</div>

					<?php if ( $has_multiple_slides ) : ?>
						<button type="button" class="ppic-courses-slider__arrow is-prev" data-slide="prev" aria-label="Slide course sebelumnya">
							<span aria-hidden="true">&#10094;</span>
						</button>
						<button type="button" class="ppic-courses-slider__arrow is-next" data-slide="next" aria-label="Slide course berikutnya">
							<span aria-hidden="true">&#10095;</span>
						</button>
						<div class="ppic-courses-slider__dots" aria-label="Navigasi slider course"></div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div class="ppic-courses-empty">
					<p>Belum ada course yang bisa ditampilkan.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php

	static $script_printed = false;

	if ( ! $script_printed ) {
		$script_printed = true;
		?>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				var sliders = document.querySelectorAll('[data-courses-slider]');

				sliders.forEach(function (slider) {
					var viewport = slider.querySelector('.ppic-courses-slider__viewport');
					var track = slider.querySelector('.ppic-courses-slider__track');
					var slides = slider.querySelectorAll('.ppic-course-card');
					var dotsWrap = slider.querySelector('.ppic-courses-slider__dots');
					var dots = [];
					var prevButton = slider.querySelector('[data-slide="prev"]');
					var nextButton = slider.querySelector('[data-slide="next"]');
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
						if (window.innerWidth <= 575) {
							return 1;
						}

						if (window.innerWidth <= 991) {
							return 3;
						}

						return 5;
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
							dot.className = 'ppic-courses-slider__dot';
							dot.setAttribute('aria-label', 'Tampilkan slide course ' + (dotIndex + 1));
							dot.setAttribute('data-slide-to', String(dotIndex));
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

add_action( 'vc_before_init', 'ppic_courses_map' );
function ppic_courses_map() {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}

	vc_map(
		array(
			'name' => __( 'PPIC Courses', 'ppic-custom-element' ),
			'base' => 'ppic_courses',
			'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
			'icon' => 'dashicons dashicons-welcome-learn-more',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Title', 'ppic-custom-element' ),
					'param_name' => 'title',
					'value' => 'Our Short Courses',
					'admin_label' => true,
				),
				array(
					'type' => 'textarea',
					'heading' => __( 'Text subtitle', 'ppic-custom-element' ),
					'param_name' => 'subtitle',
					'value' => 'Kegiatan Diklat Pendek di Politeknik Penerbangan Indonesia Curug',
				),
				array(
					'type' => 'vc_link',
					'heading' => __( 'Link Btn', 'ppic-custom-element' ),
					'param_name' => 'button_link',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Text Btn', 'ppic-custom-element' ),
					'param_name' => 'button_text',
					'value' => 'View All Courses',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Card Label', 'ppic-custom-element' ),
					'param_name' => 'label_text',
					'value' => 'Diklat Pendek',
					'description' => __( 'Teks kecil di atas judul tiap card.', 'ppic-custom-element' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Ukuran Gambar Card', 'ppic-custom-element' ),
					'param_name' => 'image_size',
					'value' => array(
						__( 'Thumbnail (paling ringan)', 'ppic-custom-element' ) => 'thumbnail',
						__( 'Medium', 'ppic-custom-element' ) => 'medium',
						__( 'Medium Large', 'ppic-custom-element' ) => 'medium_large',
						__( 'Large', 'ppic-custom-element' ) => 'large',
						__( 'Full (paling berat)', 'ppic-custom-element' ) => 'full',
					),
					'std' => 'medium',
					'description' => __( 'Gunakan Medium atau Thumbnail agar slider lebih ringan saat memuat banyak card.', 'ppic-custom-element' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Autoplay Slider', 'ppic-custom-element' ),
					'param_name' => 'autoplay',
					'value' => array(
						__( 'Aktif', 'ppic-custom-element' ) => 'true',
						__( 'Nonaktif', 'ppic-custom-element' ) => 'false',
					),
					'std' => 'true',
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Auto Loop', 'ppic-custom-element' ),
					'param_name' => 'autoloop',
					'value' => array(
						__( 'Aktif', 'ppic-custom-element' ) => 'true',
						__( 'Nonaktif', 'ppic-custom-element' ) => 'false',
					),
					'std' => 'true',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Durasi Autoplay (ms)', 'ppic-custom-element' ),
					'param_name' => 'autoplay_interval',
					'value' => '4500',
					'description' => __( 'Contoh: 4500 = 4.5 detik.', 'ppic-custom-element' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Post Type', 'ppic-custom-element' ),
					'param_name' => 'post_type',
					'value' => 'course',
					'description' => __( 'Slug post type course. Ubah hanya jika website memakai slug berbeda.', 'ppic-custom-element' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Jumlah Course yang Ditampilkan', 'ppic-custom-element' ),
					'param_name' => 'posts_count',
					'value' => '10',
					'description' => __( 'Default 10 agar slider desktop bisa menampilkan 5 card per layar.', 'ppic-custom-element' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Sort Order', 'ppic-custom-element' ),
					'param_name' => 'order',
					'value' => array(
						__( 'DESC : highest to lowest', 'ppic-custom-element' ) => 'DESC',
						__( 'ASC : lowest to highest', 'ppic-custom-element' ) => 'ASC',
					),
					'std' => 'DESC',
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Order by', 'ppic-custom-element' ),
					'param_name' => 'orderby',
					'value' => array(
						__( 'Date', 'ppic-custom-element' ) => 'date',
						__( 'Title', 'ppic-custom-element' ) => 'title',
						__( 'Menu order', 'ppic-custom-element' ) => 'menu_order',
						__( 'Random', 'ppic-custom-element' ) => 'rand',
					),
					'std' => 'date',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Excerpt length', 'ppic-custom-element' ),
					'param_name' => 'excerpt_length',
					'value' => '12',
				),
				array(
					'type' => 'el_id',
					'heading' => __( 'Element ID', 'js_composer' ),
					'param_name' => 'el_id',
					'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'js_composer' ),
					'param_name' => 'el_class',
					'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
				),
			),
		)
	);
}
