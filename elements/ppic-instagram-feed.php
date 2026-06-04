<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_instagram_feed', 'ppic_instagram_feed_render' );
function ppic_instagram_feed_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => 'PPI Curug on Instagram',
            'follow_text' => 'Follow @ppicurug.official',
            'follow_link' => 'url:https%3A%2F%2Finstagram.com%2Fppicurug.official|title:Follow @ppicurug.official|target:_blank',
            'el_id' => '',
            'el_class' => '',
            'items' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1jY6Cm8VSrmZgS3-q1QLdeRaz4uhpoWHk',
                            'alt_text' => 'Instagram post 2',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1Ti6j9dyds0ddi_E_Yn0SGhSSNMnAkLUD',
                            'alt_text' => 'Instagram post 3',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/12l-TKRZ4MxTPUcCLsY6SbKDJobdHoS-3',
                            'alt_text' => 'Instagram post 4',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/19HUvtgE6xfQ4-7EqyD78eEXXx0TC3J7V',
                            'alt_text' => 'Instagram post 5',
                        ),
                        array(
                            'image_url' => 'https://lh3.googleusercontent.com/d/1XqyFlWjglYFZB9JAxhwAwdIZsgnHRHgk',
                            'alt_text' => 'Instagram post 6',
                        ),
                    )
                )
            ),
        ),
        $atts
    );

    $follow_link = vc_build_link( $atts['follow_link'] );
    $follow_href = ! empty( $follow_link['url'] ) ? $follow_link['url'] : '#';
    $follow_label = ! empty( $follow_link['title'] ) ? $follow_link['title'] : $atts['follow_text'];
    $follow_target = ! empty( $follow_link['target'] ) ? ' target="' . trim( $follow_link['target'] ) . '"' : '';

    $items = vc_param_group_parse_atts( $atts['items'] );

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '';
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-instagram-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-instagram-container">
            <div class="ppic-instagram-header">
                <h2><?php echo esc_html( $atts['title'] ); ?></h2>
                <a href="<?php echo esc_url( $follow_href ); ?>"<?php echo $follow_target; ?>>
                    <?php echo esc_html( $follow_label ); ?>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
            <div class="ppic-instagram-grid">
                <?php foreach ( $items as $item ) :
                    $image_url = isset( $item['image_url'] ) ? trim( $item['image_url'] ) : '';
                    $alt_text  = isset( $item['alt_text'] ) ? trim( $item['alt_text'] ) : 'Instagram post';

                    if ( '' === $image_url ) {
                        continue;
                    }
                    ?>
                    <div class="ppic-instagram-card">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>">
                        <i class="fab fa-instagram ppic-instagram-icon" aria-hidden="true"></i>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_instagram_feed_map' );
function ppic_instagram_feed_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Instagram Feed', 'ppic-custom-element' ),
            'base' => 'ppic_instagram_feed',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-instagram',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value' => 'PPI Curug on Instagram',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks Tombol Follow', 'ppic-custom-element' ),
                    'param_name' => 'follow_text',
                    'value' => 'Follow @ppicurug.official',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link Follow', 'ppic-custom-element' ),
                    'param_name' => 'follow_link',
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
                    'heading' => __( 'Daftar Post Instagram', 'ppic-custom-element' ),
                    'param_name' => 'items',
                    'value' => urlencode(
                        wp_json_encode(
                            array(
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1jY6Cm8VSrmZgS3-q1QLdeRaz4uhpoWHk',
                                    'alt_text' => 'Instagram post 2',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1Ti6j9dyds0ddi_E_Yn0SGhSSNMnAkLUD',
                                    'alt_text' => 'Instagram post 3',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/12l-TKRZ4MxTPUcCLsY6SbKDJobdHoS-3',
                                    'alt_text' => 'Instagram post 4',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/19HUvtgE6xfQ4-7EqyD78eEXXx0TC3J7V',
                                    'alt_text' => 'Instagram post 5',
                                ),
                                array(
                                    'image_url' => 'https://lh3.googleusercontent.com/d/1XqyFlWjglYFZB9JAxhwAwdIZsgnHRHgk',
                                    'alt_text' => 'Instagram post 6',
                                ),
                            )
                        )
                    ),
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'URL Gambar', 'ppic-custom-element' ),
                            'param_name' => 'image_url',
                            'description' => __( 'Masukkan URL gambar post.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Alt Text', 'ppic-custom-element' ),
                            'param_name' => 'alt_text',
                        ),
                    ),
                ),
            ),
        )
    );
}