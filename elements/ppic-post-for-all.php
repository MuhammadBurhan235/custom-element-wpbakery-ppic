<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_post_for_all', 'ppic_post_for_all_render' );
function ppic_post_for_all_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'post_mode'   => 'semua', 
            'category'    => '',      
            'filter_cats' => '',      
            'display_count'=> '3',     
            'fetch_count' => '6',     
            'title'       => 'Berita Terkini',
            'desc'        => 'Informasi unggulan seputar prestasi, kerja sama, dan dinamika kampus kedirgantaraan.',
            'btn_text'    => 'Lihat Semua Berita',
            'btn_url'     => 'url:%2Fberita|title:Semua%20Berita',
            'el_id'       => 'post-section',
            'el_class'    => '',
        ),
        $atts
    );

    $link = ( '||' !== $atts['btn_url'] ) ? vc_build_link( $atts['btn_url'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-post-wrapper' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    $display_limit = intval( $atts['display_count'] ) > 0 ? intval( $atts['display_count'] ) : 3;
    $fetch_limit   = intval( $atts['fetch_count'] ) > 0 ? intval( $atts['fetch_count'] ) : 6;

    // KATEGORI UNTUK FILTER
    $active_categories = array();
    $cat_ids_to_query  = array();

    if ( $atts['post_mode'] === 'semua' ) {
        if ( ! empty( $atts['filter_cats'] ) ) {
            $selected_ids = explode( ',', $atts['filter_cats'] );
            foreach ( $selected_ids as $cid ) {
                $term = get_term( $cid, 'category' );
                if ( $term && ! is_wp_error( $term ) ) {
                    $active_categories[$term->slug] = $term->name;
                    $cat_ids_to_query[] = $term->term_id;
                }
            }
        } else {
            $all_cats = get_categories( array( 'hide_empty' => true ) );
            foreach ( $all_cats as $c ) {
                $active_categories[$c->slug] = $c->name;
                $cat_ids_to_query[] = $c->term_id;
            }
        }
    }

    // QUERY POST
    $fetched_posts = array();

    if ( $atts['post_mode'] === 'semua' ) {
        foreach ( $cat_ids_to_query as $cid ) {
            $posts_in_cat = get_posts( array(
                'post_type'   => 'post',
                'post_status' => 'publish',
                'numberposts' => $fetch_limit,
                'category'    => $cid,
            ) );
            
            foreach ( $posts_in_cat as $p ) {
                $fetched_posts[ $p->ID ] = $p;
            }
        }
        usort( $fetched_posts, function($a, $b) {
            return strtotime( $b->post_date ) > strtotime( $a->post_date ) ? 1 : -1;
        });

    } else {
        $cid = ! empty( $atts['category'] ) ? intval( $atts['category'] ) : 0;
        $fetched_posts = get_posts( array(
            'post_type'   => 'post',
            'post_status' => 'publish',
            'numberposts' => $display_limit,
            'category'    => $cid,
        ) );
    }

    // RENDER KARTU
    global $post;
    $posts_html = '';

    if ( ! empty( $fetched_posts ) ) {
        foreach ( $fetched_posts as $post ) {
            setup_postdata( $post );
            
            $post_title = get_the_title();
            $post_date  = get_the_date('d F Y');
            $post_link  = get_the_permalink();
            $post_exc   = wp_trim_words( get_the_excerpt(), 15, '...' );
            
            if ( has_post_thumbnail() ) {
                $post_img = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
            } else {
                $post_img = 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=600&q=80';
            }

            $post_cats = get_the_category();
            $cat_slugs = array();
            $tags_html = '';
            $primary_cat_name = 'BERITA'; 

            if ( ! empty( $post_cats ) ) {
                $primary_cat_name = strtoupper( $post_cats[0]->name );
                foreach ( $post_cats as $c ) {
                    $cat_slugs[] = $c->slug;
                    $tags_html .= '<span class="tag"><i class="fas fa-tag"></i> ' . esc_html( $c->name ) . '</span>';
                }
            }
            $cat_string = implode(' ', $cat_slugs);

            $posts_html .= '
            <div class="post-card" data-categories="' . esc_attr( $cat_string ) . '" style="display:none;">
                <div class="post-image">
                    <img src="' . esc_url( $post_img ) . '" alt="' . esc_attr( $post_title ) . '" loading="lazy" />
                </div>
                <div class="post-content">
                    <span class="content-badge">' . esc_html( $primary_cat_name ) . '</span>
                    <h4>' . esc_html( $post_title ) . '</h4>
                    <div class="date"><i class="far fa-calendar-alt"></i> ' . esc_html( $post_date ) . '</div>
                    <p>' . esc_html( $post_exc ) . '</p>
                    
                    <a href="' . esc_url( $post_link ) . '" class="read-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                    
                    <div class="card-tags">' . $tags_html . '</div>
                </div>
            </div>';
        }
        wp_reset_postdata();
    } else {
        $posts_html = '<p style="text-align:center; width:100%; display:block;">Belum ada konten yang diterbitkan.</p>';
    }

    ob_start(); ?>
    <div<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" data-limit="<?php echo esc_attr( $display_limit ); ?>">
        <section class="ppic-post-section">
            <div class="container">
                <div class="ppic-post-header">
                    <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                    <?php if ( ! empty( $atts['desc'] ) ) : ?>
                        <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                    <?php endif; ?>

                    <?php 
                    // FILTER BAR DIPINDAHKAN KE SINI (DI BAWAH SUBTITLE)
                    if ( $atts['post_mode'] === 'semua' && ! empty( $active_categories ) ) : 
                    ?>
                        <div class="filter-bar">
                            <div class="filter-container">
                                <button class="filter-btn active" data-filter="all">Semua</button>
                                <?php foreach ( $active_categories as $slug => $name ) : ?>
                                    <button class="filter-btn" data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="post-grid-wrapper">
                    <div class="post-grid">
                        <?php echo $posts_html; ?>
                    </div>
                    <div class="no-post-msg" style="display: none;">
                        <i class="fas fa-folder-open"></i>
                        <p>Belum ada post terbaru di kategori ini. Silakan pilih kategori lain.</p>
                    </div>
                </div>

                <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                    <div class="view-all-btn">
                        <a href="<?php echo esc_url( $a_href ); ?>" class="btn-outline-post"<?php echo $a_target; ?>>
                            <?php echo esc_html( $atts['btn_text'] ); ?> <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <?php
    if ( $atts['post_mode'] === 'semua' && ! empty( $active_categories ) ) {
        static $ppic_post_js_printed = false;
        if ( ! $ppic_post_js_printed ) {
            $ppic_post_js_printed = true;
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var wrappers = document.querySelectorAll('.ppic-post-wrapper');
                    
                    wrappers.forEach(function(wrapper) {
                        var filterBtns = wrapper.querySelectorAll('.filter-btn');
                        var postCards  = wrapper.querySelectorAll('.post-card');
                        var grid       = wrapper.querySelector('.post-grid');
                        var noMsg      = wrapper.querySelector('.no-post-msg');
                        
                        var displayLimit = parseInt(wrapper.getAttribute('data-limit')) || 3;

                        if(filterBtns.length > 0) {
                            filterBtns.forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    filterBtns.forEach(b => b.classList.remove('active'));
                                    this.classList.add('active');

                                    var filterValue  = this.getAttribute('data-filter');
                                    var visibleCount = 0;

                                    postCards.forEach(function(card) {
                                        var isMatch = false;

                                        if (filterValue === 'all') {
                                            isMatch = true;
                                        } else {
                                            var categories = card.getAttribute('data-categories');
                                            if (categories && categories.split(' ').indexOf(filterValue) > -1) {
                                                isMatch = true;
                                            }
                                        }

                                        if (isMatch && visibleCount < displayLimit) {
                                            card.style.display = 'flex';
                                            visibleCount++;
                                        } else {
                                            card.style.display = 'none';
                                        }
                                    });

                                    if(visibleCount === 0) {
                                        grid.style.display = 'none';
                                        if(noMsg) noMsg.style.display = 'flex';
                                    } else {
                                        grid.style.display = 'grid';
                                        if(noMsg) noMsg.style.display = 'none';
                                    }
                                });
                            });

                            var activeBtn = wrapper.querySelector('.filter-btn.active');
                            if (activeBtn) {
                                activeBtn.click();
                            }
                        }
                    });
                });
            </script>
            <?php
        }
    } else if ( $atts['post_mode'] === 'spesifik' ) {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var specificCards = document.querySelectorAll('.ppic-post-wrapper[data-limit] .post-card');
                specificCards.forEach(function(card) {
                    card.style.display = 'flex';
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_post_for_all_element' );
function ppic_register_post_for_all_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $cat_options = array();
    $categories = get_categories( array( 'hide_empty' => false ) );
    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        foreach ( $categories as $cat ) {
            $cat_options[ $cat->name ] = $cat->term_id;
        }
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Filter Post', 'ppic-custom-element' ),
            'base'     => 'ppic_post_for_all',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-media-document',
            'params'   => array(
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Jenis Konten yang Ditampilkan', 'ppic-custom-element' ),
                    'param_name'  => 'post_mode',
                    'value'       => array(
                        'Mode Terkini (Menampilkan Semua & Filter Bar Aktif)' => 'semua',
                        'Mode Spesifik (Pilih 1 Kategori, Filter Bar Dimatikan)' => 'spesifik',
                    ),
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'checkbox',
                    'heading'     => __( 'Tombol Filter Kategori (Khusus Mode Terkini)', 'ppic-custom-element' ),
                    'param_name'  => 'filter_cats',
                    'value'       => $cat_options,
                    'dependency'  => array(
                        'element' => 'post_mode',
                        'value'   => 'semua',
                    ),
                    'description' => __( 'Centang kategori apa saja yang ingin Anda jadikan tombol di Filter Bar. Jika dibiarkan kosong, semua kategori akan ditarik.', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Pilih Kategori Spesifik', 'ppic-custom-element' ),
                    'param_name'  => 'category',
                    'value'       => $cat_options,
                    'dependency'  => array(
                        'element' => 'post_mode',
                        'value'   => 'spesifik',
                    ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Jumlah Maksimal Ditampilkan', 'ppic-custom-element' ),
                    'param_name'  => 'display_count',
                    'value'       => '3',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Jumlah Data Ditarik (Sistem)', 'ppic-custom-element' ),
                    'param_name'  => 'fetch_count',
                    'value'       => '6',
                    'dependency'  => array(
                        'element' => 'post_mode',
                        'value'   => 'semua',
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Berita Terkini',
                    'group'      => __( 'Teks', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul / Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Informasi unggulan seputar prestasi, kerja sama, dan dinamika kampus kedirgantaraan.',
                    'group'      => __( 'Teks', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Bawah', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Lihat Semua Berita',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_url',
                    'value'      => 'url:%2Fberita|title:Semua%20Berita',
                    'group'      => __( 'Tombol', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'post-section',
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