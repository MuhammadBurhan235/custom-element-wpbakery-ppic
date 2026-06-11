<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_kampus', 'ppic_kenapa_kampus_render' );
function ppic_kenapa_kampus_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // Kolom Kiri (Teks)
            'badge_text'   => 'Berkarya Sejak 1952',
            'title'        => 'Kehidupan Kampus Terpadu',
            'description'  => 'PPI Curug adalah kampus penerbangan tertua di Asia Tenggara, didirikan pada 1 Juni 1952 di Jakarta dengan nama Akademi Penerbangan Indonesia (API). Berlokasi strategis di Legok, Tangerang, kampus ini terintegrasi langsung dengan Bandara Budiarto, menjadikannya satu-satunya kampus di Indonesia yang co-located dengan bandara aktif. Lingkungan asri <strong>Green Campus</strong> dengan pepohonan rindang menciptakan suasana belajar yang kondusif dan sejuk.',
            'features'     => '',
            // Kolom Kanan (Kartu)
            'card_title'   => 'Established in 1952',
            'card_sub'     => 'Tertua di Asia Tenggara',
            'card_icon'    => 'fas fa-check-circle',
            'card_hl_title'=> 'Co-located with Airport',
            'card_hl_desc' => 'Satu-satunya kampus yang menyatu dengan Bandara Budiarto',
            // Umum
            'el_id'        => '',
            'el_class'     => '',
        ),
        $atts
    );

    // Parse daftar fitur
    $features = vc_param_group_parse_atts( $atts['features'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-kenapa-kampus-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-kenapa-kampus-container">
            <div class="ppic-kenapa-kampus-text-col">
                <?php if ( ! empty( $atts['badge_text'] ) ) : ?>
                    <div class="ppic-kenapa-kampus-badge"><?php echo esc_html( $atts['badge_text'] ); ?></div>
                <?php endif; ?>

                <h3 class="ppic-kenapa-kampus-title"><?php echo esc_html( $atts['title'] ); ?></h3>
                
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <div class="ppic-kenapa-kampus-desc">
                        <?php echo wp_kses_post( $atts['description'] ); // Mendukung tag <strong> dll ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $features ) && is_array( $features ) ) : ?>
                    <ul class="ppic-kenapa-kampus-features">
                        <?php foreach ( $features as $feat ) : 
                            $icon = isset( $feat['icon'] ) ? trim( $feat['icon'] ) : 'fas fa-check';
                            $text = isset( $feat['text'] ) ? trim( $feat['text'] ) : '';
                            
                            if ( empty( $text ) ) continue;
                            ?>
                            <li>
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                                <span><?php echo esc_html( $text ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="ppic-kenapa-kampus-visual-col">
                <div class="ppic-kenapa-kampus-card">
                    <div class="ppic-kenapa-kampus-card-title"><?php echo esc_html( $atts['card_title'] ); ?></div>
                    <div class="ppic-kenapa-kampus-card-sub"><?php echo esc_html( $atts['card_sub'] ); ?></div>
                    
                    <hr class="ppic-kenapa-kampus-card-divider" />
                    
                    <div class="ppic-kenapa-kampus-highlight">
                        <div class="highlight-icon">
                            <i class="<?php echo esc_attr( $atts['card_icon'] ); ?>" aria-hidden="true"></i>
                        </div>
                        <div class="highlight-text">
                            <strong><?php echo esc_html( $atts['card_hl_title'] ); ?></strong>
                            <span><?php echo esc_html( $atts['card_hl_desc'] ); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_kampus_element' );
function ppic_register_kenapa_kampus_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_features = array(
        array(
            'icon' => 'fas fa-leaf',
            'text' => 'Lingkungan Asri & Nyaman – kampus yang sejuk dan teduh'
        ),
        array(
            'icon' => 'fas fa-gavel',
            'text' => 'Kedisiplinan Tinggi – sistem asrama dengan pembinaan karakter'
        ),
        array(
            'icon' => 'fas fa-users',
            'text' => 'Kebersamaan Kuat – motto "Cewama Eka Tayai" (Mengabdi Untuk Kesatuan)'
        ),
        array(
            'icon' => 'fas fa-city',
            'text' => 'Lokasi Premium – dekat dengan kawasan CBD seperti BSD, Gading Serpong, & Citra Raya'
        )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Kehidupan Kampus', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_kampus',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-building',
            'params'   => array(
                // Kolom Kiri
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Badge Kuning', 'ppic-custom-element' ),
                    'param_name' => 'badge_text',
                    'value'      => 'Berkarya Sejak 1952',
                    'group'      => __( 'Kolom Kiri (Teks)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Kehidupan Kampus Terpadu',
                    'admin_label'=> true,
                    'group'      => __( 'Kolom Kiri (Teks)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value'      => 'PPI Curug adalah kampus penerbangan tertua di Asia Tenggara, didirikan pada 1 Juni 1952 di Jakarta dengan nama Akademi Penerbangan Indonesia (API). Berlokasi strategis di Legok, Tangerang, kampus ini terintegrasi langsung dengan Bandara Budiarto, menjadikannya satu-satunya kampus di Indonesia yang co-located dengan bandara aktif. Lingkungan asri <strong>Green Campus</strong> dengan pepohonan rindang menciptakan suasana belajar yang kondusif dan sejuk.',
                    'group'      => __( 'Kolom Kiri (Teks)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Poin Fitur', 'ppic-custom-element' ),
                    'param_name' => 'features',
                    'value'      => urlencode( wp_json_encode( $dummy_features ) ),
                    'group'      => __( 'Kolom Kiri (Teks)', 'ppic-custom-element' ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Ikon FontAwesome', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'description' => 'Contoh: fas fa-leaf',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Teks Fitur', 'ppic-custom-element' ),
                            'param_name' => 'text',
                            'admin_label'=> true,
                        ),
                    ),
                ),
                // Kolom Kanan
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu Besar', 'ppic-custom-element' ),
                    'param_name' => 'card_title',
                    'value'      => 'Established in 1952',
                    'group'      => __( 'Kolom Kanan (Kartu)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Sub-judul Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_sub',
                    'value'      => 'Tertua di Asia Tenggara',
                    'group'      => __( 'Kolom Kanan (Kartu)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Highlight', 'ppic-custom-element' ),
                    'param_name' => 'card_icon',
                    'value'      => 'fas fa-check-circle',
                    'group'      => __( 'Kolom Kanan (Kartu)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight', 'ppic-custom-element' ),
                    'param_name' => 'card_hl_title',
                    'value'      => 'Co-located with Airport',
                    'group'      => __( 'Kolom Kanan (Kartu)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Highlight', 'ppic-custom-element' ),
                    'param_name' => 'card_hl_desc',
                    'value'      => 'Satu-satunya kampus yang menyatu dengan Bandara Budiarto',
                    'group'      => __( 'Kolom Kanan (Kartu)', 'ppic-custom-element' ),
                ),
                // General
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}