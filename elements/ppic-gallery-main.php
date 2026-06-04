<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_gallery_main', 'ppic_gallery_main_render' );
function ppic_gallery_main_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'csv_url'  => '',
            'el_id'    => '',
            'el_class' => '',
        ),
        $atts
    );

    if ( empty( $atts['csv_url'] ) ) {
        return '<p style="text-align:center; padding: 20px;">Silakan masukkan URL file CSV pada pengaturan elemen Galeri.</p>';
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : ' id="galleryGridWrapper"';
    $wrapper_class = 'gallery-main ppic-gallery-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Fetch and parse CSV (dengan transient cache 12 jam)
    $transient_key = 'ppic_gallery_' . md5( $atts['csv_url'] );
    $gallery_data = get_transient( $transient_key );

    if ( false === $gallery_data ) {
        $gallery_data = array(
            'categories' => array(),
            'items'      => array()
        );

        if ( ( $handle = fopen( $atts['csv_url'], "r" ) ) !== false ) {
            $header = fgetcsv( $handle, 1000, "," ); // skip header
            while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== false ) {
                if ( count( $data ) >= 4 ) {
                    $category = trim( $data[0] );
                    $img_url  = trim( $data[1] );
                    $title    = trim( $data[2] );
                    $desc     = trim( $data[3] );

                    if ( ! empty( $category ) && ! in_array( $category, $gallery_data['categories'] ) ) {
                        $gallery_data['categories'][] = $category;
                    }

                    $gallery_data['items'][] = array(
                        'category' => $category,
                        'img_url'  => $img_url,
                        'title'    => $title,
                        'desc'     => $desc
                    );
                }
            }
            fclose( $handle );
            set_transient( $transient_key, $gallery_data, 12 * HOUR_IN_SECONDS );
        }
    }

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-gallery-container">
            
            <?php if ( ! empty( $gallery_data['categories'] ) ) : ?>
                <div class="ppic-gallery-filters">
                    <button class="gallery-filter-btn active" data-filter="all">Semua</button>
                    <?php foreach ( $gallery_data['categories'] as $cat ) : ?>
                        <button class="gallery-filter-btn" data-filter="<?php echo esc_attr( $cat ); ?>"><?php echo esc_html( $cat ); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="gallery-grid" id="galleryGrid">
                <?php foreach ( $gallery_data['items'] as $item ) : ?>
                    <div class="gallery-card" data-category="<?php echo esc_attr( $item['category'] ); ?>">
                        <div class="image-wrapper">
                            <img class="gallery-img" src="<?php echo esc_url( $item['img_url'] ); ?>" loading="lazy" alt="<?php echo esc_attr( $item['title'] ); ?>" onerror="this.src='https://via.placeholder.com/500x300?text=Image+Not+Found'">
                            <div class="overlay-caption">
                                <div class="caption-title"><i class="fas fa-camera"></i> <?php echo esc_html( $item['title'] ); ?></div>
                                <div class="caption-desc"><i class="fas fa-feather-alt"></i> <?php echo esc_html( $item['desc'] ); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="stats-minimal" id="galleryStats">
                <i class="fas fa-images"></i> <?php echo count( $gallery_data['items'] ); ?> momen visual · Pusat Unggulan Aviasi Nasional
            </div>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.gallery-filter-btn');
            const galleryCards = document.querySelectorAll('.gallery-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update active button
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    // Filter images
                    const filterValue = btn.getAttribute('data-filter');
                    galleryCards.forEach(card => {
                        if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
    <?php

    return ob_get_clean();
}

// WPBakery Mapping
add_action( 'vc_before_init', 'ppic_gallery_main_map' );
function ppic_gallery_main_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Main Gallery (CSV)', 'ppic-custom-element' ),
            'base'     => 'ppic_gallery_main',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-images-alt2',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'URL File CSV', 'ppic-custom-element' ),
                    'param_name'  => 'csv_url',
                    'description' => __( 'Masukkan URL absolut dari file CSV galeri Anda. Format kolom: Kategori, Image URL, Title, Deskripsi', 'ppic-custom-element' ),
                    'admin_label' => true,
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