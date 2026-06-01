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

			<?php if ( $query->have_posts() ) : ?>
				<div class="ppic-courses-grid">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();

						$post_id = get_the_ID();
						$thumbnail = get_the_post_thumbnail_url( $post_id, 'large' );

						if ( empty( $thumbnail ) ) {
							$thumbnail = 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg';
						}
						?>
						<article class="ppic-course-card">
							<a class="ppic-course-card__link" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
								<div class="ppic-course-card__image-wrap">
									<img
										class="ppic-course-card__image"
										src="<?php echo esc_url( $thumbnail ); ?>"
										alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"
										loading="lazy"
										decoding="async"
									>
								</div>
								<div class="ppic-course-card__body">
									<?php if ( ! empty( $atts['label_text'] ) ) : ?>
										<span class="ppic-course-card__label"><?php echo esc_html( $atts['label_text'] ); ?></span>
									<?php endif; ?>
									<h3 class="ppic-course-card__title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h3>
									<p class="ppic-course-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), max( 6, intval( $atts['excerpt_length'] ) ), '...' ) ); ?></p>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<div class="ppic-courses-empty">
					<p>Belum ada course yang bisa ditampilkan.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	wp_reset_postdata();

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
					'type' => 'textfield',
					'heading' => __( 'Post Type', 'ppic-custom-element' ),
					'param_name' => 'post_type',
					'value' => 'course',
					'description' => __( 'Slug post type course. Ubah hanya jika website memakai slug berbeda.', 'ppic-custom-element' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Number of Post You want show.', 'ppic-custom-element' ),
					'param_name' => 'posts_count',
					'value' => '6',
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
