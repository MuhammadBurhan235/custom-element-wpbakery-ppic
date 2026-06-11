<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_beasiswa', 'ppic_kenapa_beasiswa_render' );
function ppic_kenapa_beasiswa_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // Bagian Teks (Kanan)
            'badge_text' => 'Dukungan Penuh',
            'title'      => 'Beasiswa & Bantuan Pendidikan',
            'desc'       => 'Pola Pembibitan (beasiswa penuh Kemenhub), beasiswa CSR dari PTDI, Beasiswa Tangerang Gemilang 2026.',
            'features'   => '',
            
            // Bagian Kartu Visual (Kiri)
            'card_icon'  => 'fas fa-hand-holding-usd',
            'card_title' => 'Penuh / Parsial',
            'card_desc'  => 'Beasiswa dari Pemerintah & Industri',
            
            // Pengaturan Umum
            'el_id'      => '',
            'el_class'   => '',
        ),
        $atts
    );

    // Proses data fitur list
    $features = vc_param_group_parse_atts( $atts['features'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-beasiswa-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-beasiswa-container">
            
            <div class="ppic-beasiswa-visual">
                <div class="ppic-beasiswa-card">
                    <i class="<?php echo esc_attr( $atts['card_icon'] ); ?>" aria-hidden="true"></i>
                    <h3><?php echo esc_html( $atts['card_title'] ); ?></h3>
                    <p><?php echo esc_html( $atts['card_desc'] ); ?></p>
                </div>
            </div>

            <div class="ppic-beasiswa-text">
                <?php if ( ! empty( $atts['badge_text'] ) ) : ?>
                    <div class="ppic-beasiswa-badge"><?php echo esc_html( $atts['badge_text'] ); ?></div>
                <?php endif; ?>
                
                <h2 class="ppic-beasiswa-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="ppic-beasiswa-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $features ) && is_array( $features ) ) : ?>
                    <ul class="ppic-beasiswa-features">
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

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_beasiswa_element' );
function ppic_register_kenapa_beasiswa_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_features = array(
        array( 'icon' => 'fas fa-user-graduate', 'text' => 'Pola Pembibitan (beasiswa penuh)' ),
        array( 'icon' => 'fas fa-building', 'text' => 'Beasiswa CSR mitra industri' ),
        array( 'icon' => 'fas fa-city', 'text' => 'Beasiswa Tangerang Gemilang' )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Beasiswa dan Bantuan Pendidikan', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_beasiswa',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-learn-more',
            'params'   => array(
                // Pengaturan Teks (Kolom Kanan)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Badge (Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'badge_text',
                    'value'      => 'Dukungan Penuh',
                    'group'      => __( 'Konten Teks', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Beasiswa & Bantuan Pendidikan',
                    'admin_label'=> true,
                    'group'      => __( 'Konten Teks', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Pola Pembibitan (beasiswa penuh Kemenhub), beasiswa CSR dari PTDI, Beasiswa Tangerang Gemilang 2026.',
                    'group'      => __( 'Konten Teks', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Fitur List', 'ppic-custom-element' ),
                    'param_name' => 'features',
                    'value'      => urlencode( wp_json_encode( $dummy_features ) ),
                    'group'      => __( 'Konten Teks', 'ppic-custom-element' ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Icon Class (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'description' => 'Contoh: fas fa-user-graduate',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Teks Fitur', 'ppic-custom-element' ),
                            'param_name'  => 'text',
                            'admin_label' => true,
                        ),
                    ),
                ),
                // Pengaturan Kartu Visual (Kolom Kiri)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_icon',
                    'value'      => 'fas fa-hand-holding-usd',
                    'group'      => __( 'Kartu Visual', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_title',
                    'value'      => 'Penuh / Parsial',
                    'group'      => __( 'Kartu Visual', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_desc',
                    'value'      => 'Beasiswa dari Pemerintah & Industri',
                    'group'      => __( 'Kartu Visual', 'ppic-custom-element' ),
                ),
                // Pengaturan Lanjutan
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Enter element ID.', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently.', 'js_composer' ),
                ),
            ),
        )
    );
}