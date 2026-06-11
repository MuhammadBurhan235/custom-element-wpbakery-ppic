<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_prestasi', 'ppic_kenapa_prestasi_render' );
function ppic_kenapa_prestasi_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'Prestasi Membanggakan',
            'subtitle' => 'PPI Curug terus mencatatkan prestasi di tingkat nasional maupun internasional.',
            'cards'    => '',
            'el_id'    => '',
            'el_class' => '',
        ),
        $atts
    );

    // Proses data kartu prestasi
    $cards = vc_param_group_parse_atts( $atts['cards'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-prestasi-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-prestasi-container">
            
            <div class="ppic-prestasi-header">
                <h2 class="ppic-prestasi-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-prestasi-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $cards ) && is_array( $cards ) ) : ?>
                <div class="ppic-prestasi-grid">
                    <?php foreach ( $cards as $card ) : 
                        $icon  = isset( $card['icon'] ) ? trim( $card['icon'] ) : 'fas fa-star';
                        $title = isset( $card['title'] ) ? trim( $card['title'] ) : '';
                        $desc  = isset( $card['desc'] ) ? trim( $card['desc'] ) : '';
                        $tag   = isset( $card['tag'] ) ? trim( $card['tag'] ) : '';
                        
                        if ( empty( $title ) && empty( $desc ) ) continue;
                        ?>
                        <div class="ppic-prestasi-card">
                            <div class="ppic-prestasi-icon">
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            <p><?php echo esc_html( $desc ); ?></p>
                            
                            <?php if ( ! empty( $tag ) ) : ?>
                                <span class="ppic-prestasi-tag"><?php echo esc_html( $tag ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_prestasi_element' );
function ppic_register_kenapa_prestasi_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_cards = array(
        array(
            'icon'  => 'fas fa-graduation-cap',
            'title' => 'Akademik & Profesi',
            'desc'  => 'Lulusan menjadi ATC di Qatar, pilot TNI AU, pengakuan industri global.',
            'tag'   => 'Go International'
        ),
        array(
            'icon'  => 'fas fa-medal',
            'title' => 'Non-Akademik',
            'desc'  => 'Juara olahraga nasional, olimpiade taruna, seni dan budaya.',
            'tag'   => 'Holistic Excellence'
        ),
        array(
            'icon'  => 'fas fa-chart-line',
            'title' => 'Riset Terkini',
            'desc'  => 'Kolaborasi riset dengan PTDI dan perguruan tinggi.',
            'tag'   => 'Research & Innovation'
        ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Prestasi', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_prestasi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-awards',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Prestasi Membanggakan',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'PPI Curug terus mencatatkan prestasi di tingkat nasional maupun internasional.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Kartu Prestasi', 'ppic-custom-element' ),
                    'param_name' => 'cards',
                    'value'      => urlencode( wp_json_encode( $dummy_cards ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Icon Class (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'description' => 'Contoh: fas fa-graduation-cap',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Judul Kartu', 'ppic-custom-element' ),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                            'param_name' => 'desc',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Label Tag (Bawah)', 'ppic-custom-element' ),
                            'param_name' => 'tag',
                            'description'=> 'Contoh: Go International',
                        ),
                    ),
                ),
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