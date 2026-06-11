<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_serapan', 'ppic_kenapa_serapan_render' );
function ppic_kenapa_serapan_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // Bagian Teks (Kiri)
            'badge_text' => 'Karir Gemilang',
            'title'      => 'Serapan Alumni & Jejak Karier',
            'desc'       => 'Ikatan Alumni Curug (IAC) tersebar di seluruh Indonesia & global. Banyak alumni menjadi pimpinan maskapai, bandara, otoritas penerbangan. Kerjasama dengan Kadin untuk penempatan ke Jepang.',
            'features'   => '',
            
            // Bagian Kartu Visual (Kanan)
            'card_icon'  => 'fas fa-users',
            'card_title' => 'Nasional & Global',
            'card_desc'  => 'Alumni tersebar di Indonesia dan mancanegara',
            
            // Pengaturan Umum
            'el_id'      => '',
            'el_class'   => '',
        ),
        $atts
    );

    // Proses data fitur list
    $features = vc_param_group_parse_atts( $atts['features'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-serapan-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-serapan-container">
            
            <div class="ppic-serapan-text">
                <?php if ( ! empty( $atts['badge_text'] ) ) : ?>
                    <div class="ppic-serapan-badge"><?php echo esc_html( $atts['badge_text'] ); ?></div>
                <?php endif; ?>
                
                <h2 class="ppic-serapan-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="ppic-serapan-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $features ) && is_array( $features ) ) : ?>
                    <ul class="ppic-serapan-features">
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

            <div class="ppic-serapan-visual">
                <div class="ppic-serapan-card">
                    <i class="<?php echo esc_attr( $atts['card_icon'] ); ?>" aria-hidden="true"></i>
                    <h3><?php echo esc_html( $atts['card_title'] ); ?></h3>
                    <p><?php echo esc_html( $atts['card_desc'] ); ?></p>
                </div>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_serapan_element' );
function ppic_register_kenapa_serapan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_features = array(
        array( 'icon' => 'fas fa-briefcase', 'text' => 'PNS, teknisi, pilot, ATC, manajer bandara' ),
        array( 'icon' => 'fas fa-chart-line', 'text' => 'Pimpinan di perusahaan aviasi' ),
        array( 'icon' => 'fas fa-globe-americas', 'text' => 'ATC di Qatar, pilot TNI AU' )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Serapan Alumni & Jejak Karier', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_serapan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-businessman',
            'params'   => array(
                // Pengaturan Teks (Kolom Kiri)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Badge (Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'badge_text',
                    'value'      => 'Karir Gemilang',
                    'group'      => __( 'Konten Teks', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Serapan Alumni & Jejak Karier',
                    'admin_label'=> true,
                    'group'      => __( 'Konten Teks', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Ikatan Alumni Curug (IAC) tersebar di seluruh Indonesia & global. Banyak alumni menjadi pimpinan maskapai, bandara, otoritas penerbangan. Kerjasama dengan Kadin untuk penempatan ke Jepang.',
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
                            'description' => 'Contoh: fas fa-briefcase',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Teks Fitur', 'ppic-custom-element' ),
                            'param_name'  => 'text',
                            'admin_label' => true,
                        ),
                    ),
                ),
                // Pengaturan Kartu Visual (Kolom Kanan)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_icon',
                    'value'      => 'fas fa-users',
                    'group'      => __( 'Kartu Visual', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_title',
                    'value'      => 'Nasional & Global',
                    'group'      => __( 'Kartu Visual', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_desc',
                    'value'      => 'Alumni tersebar di Indonesia dan mancanegara',
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