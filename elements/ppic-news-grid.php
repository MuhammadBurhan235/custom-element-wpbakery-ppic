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
    ), $atts );

    ob_start();
    ?>
    <div class="ppic-news-section">
        <div class="ppic-news-header">
            <h2 class="ppic-news-main-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-news-sub-title"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>
        </div>

        <div class="row ppic-news-row">
            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => intval( $atts['posts_count'] ),
                'post_status'    => 'publish',
            );
            $news_query = new WP_Query( $args );

            if ( $news_query->have_posts() ) :
                while ( $news_query->have_posts() ) : $news_query->the_post(); 
                    
                    $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    if ( ! $thumb_url ) {
                        $thumb_url = 'https://via.placeholder.com/600x400?text=PPI+Curug'; 
                    }
                    ?>
                    
                    <div class="col-lg-4 col-md-6 ppic-news-col">
                        <div class="ppic-news-card">
                            <div class="ppic-news-img-wrap">
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>">
                            </div>
                            <div class="ppic-news-content">
                                <h4><?php the_title(); ?></h4>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?></p>
                                <a href="<?php the_permalink(); ?>" class="ppic-news-readmore">Selengkapnya &rarr;</a>
                            </div>
                        </div>
                    </div>

                <?php 
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'ppic_news_grid', 'ppic_news_grid_render' );