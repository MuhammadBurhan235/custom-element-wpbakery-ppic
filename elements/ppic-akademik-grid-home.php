<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'ppic_akademik_grid_home', 'ppic_akademik_grid_home_render' );
function ppic_akademik_grid_home_render( $atts ) {
	$atts = shortcode_atts(
		array(
			'title' => '',
			'subtitle' => '',
			'el_id' => '',
			'el_class' => '',
			'items' => urlencode(
				wp_json_encode(
					array(
						array(
							'title' => 'Program',
							'button_text' => '10 Program Studi',
							'link' => 'url:https%3A%2F%2Fppicurug.ac.id%2Fprogram-studi%2F|title:Program',
							'overlay_variant' => 'is-navy',
						),
						array(
							'title' => 'Diklat Pendek',
							'button_text' => '100+ Diklat Pendek',
							'link' => 'url:https%3A%2F%2Fppicurug.ac.id%2Fshortcourse%2F|title:Diklat Pendek',
							'overlay_variant' => 'is-magenta',
						),
						array(
							'title' => 'Program RPL',
							'button_text' => '4 Program Studi Rekognisi Pembelajaran Lampau',
							'link' => 'url:https%3A%2F%2Frpl.ppicurug.ac.id%2F|title:Program RPL',
							'overlay_variant' => 'is-indigo',
						),
					)
				)
			),
		),
		$atts
	);

	$items = vc_param_group_parse_atts( $atts['items'] );
	$wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
	$wrapper_class = 'ppic-akademik-grid-home' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

	if ( empty( $items ) || ! is_array( $items ) ) {
		return '';
	}

	ob_start();
	?>
	<section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
		<div class="ppic-akademik-grid-home__container">
			<?php if ( ! empty( $atts['title'] ) || ! empty( $atts['subtitle'] ) ) : ?>
				<div class="ppic-akademik-grid-home__header">
					<?php if ( ! empty( $atts['title'] ) ) : ?>
						<h2 class="ppic-akademik-grid-home__title"><?php echo wp_kses_post( $atts['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $atts['subtitle'] ) ) : ?>
						<p class="ppic-akademik-grid-home__subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ppic-akademik-grid-home__grid">
				<?php foreach ( $items as $index => $item ) :
					$item_title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
					$button_text = isset( $item['button_text'] ) ? trim( $item['button_text'] ) : '';
					$overlay_variant = isset( $item['overlay_variant'] ) ? trim( $item['overlay_variant'] ) : 'is-navy';
					$image_id = ! empty( $item['image'] ) ? absint( $item['image'] ) : 0;
					$image_url = '';

					if ( $image_id ) {
						$image_data = wp_get_attachment_image_src( $image_id, 'full' );
						if ( $image_data && ! empty( $image_data[0] ) ) {
							$image_url = $image_data[0];
						}
					}

					$default_images = array(
						'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg',
						'https://ppicurug.ac.id/wp-content/uploads/2026/02/WhatsApp-Image-2026-02-04-at-10.28.58.jpeg',
						'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg',
					);

					if ( empty( $image_url ) ) {
						$image_url = isset( $default_images[ $index ] ) ? $default_images[ $index ] : $default_images[0];
					}

					$link = ! empty( $item['link'] ) ? vc_build_link( $item['link'] ) : array();
					$href = ! empty( $link['url'] ) ? $link['url'] : '#';
					$target = ! empty( $link['target'] ) ? trim( $link['target'] ) : '';
					$rel = '_blank' === $target ? 'noopener noreferrer' : '';
					$card_title = '' !== $item_title ? $item_title : sprintf( 'Akademik Item %d', $index + 1 );
					?>
					<article class="ppic-akademik-grid-home__item <?php echo esc_attr( $overlay_variant ); ?><?php echo empty( $image_url ) ? ' has-no-image' : ''; ?>">
						<a
							class="ppic-akademik-grid-home__link"
							href="<?php echo esc_url( $href ); ?>"
							<?php echo '' !== $target ? ' target="' . esc_attr( $target ) . '"' : ''; ?>
							<?php echo '' !== $rel ? ' rel="' . esc_attr( $rel ) . '"' : ''; ?>
							aria-label="<?php echo esc_attr( $card_title ); ?>"
						>
							<span class="ppic-akademik-grid-home__media" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"></span>
							<span class="ppic-akademik-grid-home__overlay"></span>
							<span class="ppic-akademik-grid-home__content">
								<span class="ppic-akademik-grid-home__name"><?php echo wp_kses_post( nl2br( esc_html( $card_title ) ) ); ?></span>
								<?php if ( '' !== $button_text ) : ?>
									<span class="ppic-akademik-grid-home__button"><?php echo esc_html( $button_text ); ?></span>
								<?php endif; ?>
							</span>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php

	return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_akademik_grid_home_map' );
function ppic_akademik_grid_home_map() {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}

	vc_map(
		array(
			'name' => __( 'PPIC Akademik Grid Home', 'ppic-custom-element' ),
			'base' => 'ppic_akademik_grid_home',
			'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
			'icon' => 'dashicons dashicons-screenoptions',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Judul Section', 'ppic-custom-element' ),
					'param_name' => 'title',
					'description' => __( 'Opsional. Kosongkan jika section ini tidak perlu judul.', 'ppic-custom-element' ),
				),
				array(
					'type' => 'textarea',
					'heading' => __( 'Subjudul Section', 'ppic-custom-element' ),
					'param_name' => 'subtitle',
					'description' => __( 'Opsional. Dipakai jika ingin ada pengantar singkat di atas grid.', 'ppic-custom-element' ),
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
				array(
					'type' => 'param_group',
					'heading' => __( 'Daftar Kartu Akademik', 'ppic-custom-element' ),
					'param_name' => 'items',
					'value' => urlencode(
						wp_json_encode(
							array(
								array(
									'title' => 'Program',
									'button_text' => '10 Program Studi',
									'link' => 'url:https%3A%2F%2Fppicurug.ac.id%2Fprogram-studi%2F|title:Program',
									'overlay_variant' => 'is-navy',
								),
								array(
									'title' => 'Diklat Pendek',
									'button_text' => '100+ Diklat Pendek',
									'link' => 'url:https%3A%2F%2Fppicurug.ac.id%2Fshortcourse%2F|title:Diklat Pendek',
									'overlay_variant' => 'is-magenta',
								),
								array(
									'title' => 'Program RPL',
									'button_text' => '4 Program Studi Rekognisi Pembelajaran Lampau',
									'link' => 'url:https%3A%2F%2Frpl.ppicurug.ac.id%2F|title:Program RPL',
									'overlay_variant' => 'is-indigo',
								),
							)
						)
					),
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => __( 'Judul Kartu', 'ppic-custom-element' ),
							'param_name' => 'title',
							'admin_label' => true,
						),
						array(
							'type' => 'attach_image',
							'heading' => __( 'Gambar Background', 'ppic-custom-element' ),
							'param_name' => 'image',
							'description' => __( 'Upload background untuk kartu ini.', 'ppic-custom-element' ),
						),
						array(
							'type' => 'vc_link',
							'heading' => __( 'Link Kartu', 'ppic-custom-element' ),
							'param_name' => 'link',
							'description' => __( 'Seluruh kartu akan bisa diklik ke link ini.', 'ppic-custom-element' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Teks Tombol', 'ppic-custom-element' ),
							'param_name' => 'button_text',
						),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Warna Overlay', 'ppic-custom-element' ),
							'param_name' => 'overlay_variant',
							'value' => array(
								__( 'Navy', 'ppic-custom-element' ) => 'is-navy',
								__( 'Magenta', 'ppic-custom-element' ) => 'is-magenta',
								__( 'Indigo', 'ppic-custom-element' ) => 'is-indigo',
							),
							'std' => 'is-navy',
						),
					),
				),
			),
		)
	);
}
