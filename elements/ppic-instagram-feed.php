<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_instagram_feed', 'ppic_instagram_feed_render' );
function ppic_instagram_feed_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'        => 'PPI Curug on Instagram',
            'follow_text'  => 'Follow @ppicurug.official',
            'follow_link'  => '',
            'data_source'  => 'manual', // Default ke manual
            'access_token' => '',
            'hashtag'      => '',
            'limit'        => '5', // Diubah defaultnya ke 5 menyesuaikan CSS grid 5 kolom
            'items'        => '',
            'el_id'        => '',
            'el_class'     => '',
        ),
        $atts
    );

    // Persiapan variabel umum
    $data_source = $atts['data_source'];
    $wrapper_id  = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-instagram-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parsing link tombol follow
    $link_data = vc_build_link( $atts['follow_link'] );
    $a_href    = ! empty( $link_data['url'] ) ? $link_data['url'] : '#';
    $a_target  = ! empty( $link_data['target'] ) ? ' target="' . esc_attr( trim( $link_data['target'] ) ) . '"' : ' target="_blank"';

    // Array penampung final untuk ditampilkan
    $display_items = array();

    // ==========================================
    // LOGIKA SUMBER DATA: API INSTAGRAM
    // ==========================================
    if ( $data_source === 'api' ) {
        $token   = trim( $atts['access_token'] );
        $limit   = intval( $atts['limit'] ) > 0 ? intval( $atts['limit'] ) : 5;
        $hashtag = strtolower( trim( $atts['hashtag'] ) );

        if ( empty( $token ) ) {
            return '<p style="color:red; text-align:center; padding: 40px 0;">Error: Access Token Instagram belum diisi di pengaturan elemen WPBakery.</p>';
        }

        $transient_key = 'ppic_ig_raw_data_' . md5( $token );
        $ig_posts_raw  = get_transient( $transient_key );

        if ( false === $ig_posts_raw ) {
            $api_url = "https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url&limit=50&access_token={$token}";
            $response = wp_remote_get( $api_url );

            if ( is_wp_error( $response ) ) {
                return '<p style="text-align:center; padding: 40px 0;">Gagal terhubung ke server Instagram.</p>';
            }

            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );

            if ( isset( $data['error'] ) ) {
                return '<p style="color:red; text-align:center; padding: 40px 0;">API Error: ' . esc_html( $data['error']['message'] ) . '</p>';
            }

            $ig_posts_raw = isset( $data['data'] ) ? $data['data'] : array();
            set_transient( $transient_key, $ig_posts_raw, 4 * HOUR_IN_SECONDS );
        }

        if ( is_array( $ig_posts_raw ) ) {
            $count = 0;
            foreach ( $ig_posts_raw as $post ) {
                if ( ! empty( $hashtag ) ) {
                    $caption = isset( $post['caption'] ) ? strtolower( $post['caption'] ) : '';
                    if ( strpos( $caption, $hashtag ) === false ) {
                        continue;
                    }
                }
                
                $image_url = ( $post['media_type'] === 'VIDEO' && !empty($post['thumbnail_url']) ) ? $post['thumbnail_url'] : $post['media_url'];
                $alt_text  = isset( $post['caption'] ) ? wp_trim_words( $post['caption'], 15, '...' ) : 'Instagram Post';
                
                $display_items[] = array(
                    'image_url' => $image_url,
                    'permalink' => $post['permalink'],
                    'alt_text'  => $alt_text
                );

                $count++;
                if ( $count >= $limit ) break;
            }
        }
    } 
    // ==========================================
    // LOGIKA SUMBER DATA: MANUAL (DUMMY)
    // ==========================================
    else {
        $manual_items = vc_param_group_parse_atts( $atts['items'] );
        if ( is_array( $manual_items ) ) {
            foreach ( $manual_items as $item ) {
                $image_url = isset( $item['image_url'] ) ? trim( $item['image_url'] ) : '';
                if ( empty( $image_url ) ) continue;

                $display_items[] = array(
                    'image_url' => $image_url,
                    'permalink' => isset( $item['permalink'] ) ? trim( $item['permalink'] ) : '#',
                    'alt_text'  => isset( $item['alt_text'] ) ? trim( $item['alt_text'] ) : 'Instagram post'
                );
            }
        }
    }

    // ==========================================
    // RENDER HTML MENYESUAIKAN CSS BARU
    // ==========================================
    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-instagram-container">
            
            <div class="ppic-instagram-header">
                <h2><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['follow_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $a_href ); ?>"<?php echo $a_target; ?>>
                        <i class="fab fa-instagram" aria-hidden="true"></i> <?php echo esc_html( $atts['follow_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="ppic-instagram-grid">
                <?php if ( ! empty( $display_items ) ) : ?>
                    <?php foreach ( $display_items as $item ) : ?>
                        <a href="<?php echo esc_url( $item['permalink'] ); ?>" target="_blank" rel="noopener noreferrer" class="ppic-instagram-card">
                            <img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['alt_text'] ); ?>" loading="lazy">
                            <div class="ppic-instagram-icon">
                                <i class="fab fa-instagram" aria-hidden="true"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #666;">Belum ada postingan untuk ditampilkan.</p>
                <?php endif; ?>
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
            'name'     => __( 'PPIC Instagram Feed', 'ppic-custom-element' ),
            'base'     => 'ppic_instagram_feed',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-camera',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'PPI Curug on Instagram',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol Follow', 'ppic-custom-element' ),
                    'param_name'  => 'follow_text',
                    'value'       => 'Follow @ppicurug.official',
                ),
                array(
                    'type'        => 'vc_link',
                    'heading'     => __( 'Link Akun Instagram', 'ppic-custom-element' ),
                    'param_name'  => 'follow_link',
                    'value'       => 'url:https%3A%2F%2Finstagram.com%2Fppicurug.official|title:Follow%20%40ppicurug.official|target:_blank',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Sumber Data', 'ppic-custom-element' ),
                    'param_name'  => 'data_source',
                    'value'       => array(
                        'Mengisi Manual (Data Dummy)' => 'manual',
                        'Menggunakan Access Token (Instagram API)' => 'api',
                    ),
                    'description' => __( 'Pilih cara Anda ingin menampilkan feed Instagram.', 'ppic-custom-element' ),
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Instagram Access Token', 'ppic-custom-element' ),
                    'param_name'  => 'access_token',
                    'description' => __( 'Masukkan Long-Lived Access Token dari Meta.', 'ppic-custom-element' ),
                    'dependency'  => array(
                        'element' => 'data_source',
                        'value'   => array( 'api' ),
                    ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Kata Kunci / Filter Deskripsi (Opsional)', 'ppic-custom-element' ),
                    'param_name'  => 'hashtag',
                    'description' => __( 'Ketik kata kunci yang ada di caption. Kosongkan jika ingin menampilkan semua.', 'ppic-custom-element' ),
                    'dependency'  => array(
                        'element' => 'data_source',
                        'value'   => array( 'api' ),
                    ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Jumlah Foto', 'ppic-custom-element' ),
                    'param_name'  => 'limit',
                    'value'       => '5',
                    'description' => __( 'Berapa postingan yang ditampilkan?', 'ppic-custom-element' ),
                    'dependency'  => array(
                        'element' => 'data_source',
                        'value'   => array( 'api' ),
                    ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Foto Instagram (Manual)', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'dependency'  => array(
                        'element' => 'data_source',
                        'value'   => array( 'manual' ),
                    ),
                    'value'       => urlencode(
                        wp_json_encode(
                            array(
                                array( 'image_url' => 'https://lh3.googleusercontent.com/d/1jY6Cm8VSrmZgS3-q1QLdeRaz4uhpoWHk', 'permalink' => '#', 'alt_text' => 'Instagram post 1' ),
                                array( 'image_url' => 'https://lh3.googleusercontent.com/d/1Ti6j9dyds0ddi_E_Yn0SGhSSNMnAkLUD', 'permalink' => '#', 'alt_text' => 'Instagram post 2' ),
                                array( 'image_url' => 'https://lh3.googleusercontent.com/d/12l-TKRZ4MxTPUcCLsY6SbKDJobdHoS-3', 'permalink' => '#', 'alt_text' => 'Instagram post 3' ),
                                array( 'image_url' => 'https://lh3.googleusercontent.com/d/19HUvtgE6xfQ4-7EqyD78eEXXx0TC3J7V', 'permalink' => '#', 'alt_text' => 'Instagram post 4' ),
                                array( 'image_url' => 'https://lh3.googleusercontent.com/d/1XqyFlWjglYFZB9JAxhwAwdIZsgnHRHgk', 'permalink' => '#', 'alt_text' => 'Instagram post 5' ),
                            )
                        )
                    ),
                    'params'      => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'URL Gambar', 'ppic-custom-element' ),
                            'param_name'  => 'image_url',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Link Postingan Instagram', 'ppic-custom-element' ),
                            'param_name'  => 'permalink',
                            'description' => __( 'Opsional: URL jika gambar diklik', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Alt Text', 'ppic-custom-element' ),
                            'param_name'  => 'alt_text',
                        ),
                    ),
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