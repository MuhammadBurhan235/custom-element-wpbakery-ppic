<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_daftar_informasi', 'ppic_ppid_daftar_informasi_render' );
function ppic_ppid_daftar_informasi_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'Daftar Informasi Publik',
            'subtitle' => 'Sebagai bentuk komitmen keterbukaan, kami menyediakan informasi yang dapat diakses secara berkala, setiap saat, maupun serta merta sesuai regulasi.',
            'cards'    => '',
            'cta_text' => 'Jelajahi Semua Informasi Publik',
            'cta_url'  => '#',
            'el_id'    => '',
            'el_class' => '',
        ),
        $atts
    );

    $cards = vc_param_group_parse_atts( $atts['cards'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-ppid-daftar-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-ppid-daftar-container">
            <div class="ppic-ppid-daftar-header">
                <h2 class="ppic-ppid-daftar-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-ppid-daftar-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $cards ) && is_array( $cards ) ) : ?>
                <div class="ppic-ppid-daftar-grid">
                    <?php foreach ( $cards as $card ) : 
                        $icon  = isset( $card['icon'] ) ? trim( $card['icon'] ) : 'fas fa-info-circle';
                        $title = isset( $card['title'] ) ? trim( $card['title'] ) : '';
                        $desc  = isset( $card['desc'] ) ? trim( $card['desc'] ) : '';
                        $link  = isset( $card['link'] ) && !empty( $card['link'] ) ? trim( $card['link'] ) : '#';
                        $label = isset( $card['label'] ) && !empty( $card['label'] ) ? trim( $card['label'] ) : 'Selengkapnya';

                        if ( '' === $title && '' === $desc ) continue;
                        ?>
                        <div class="ppic-ppid-daftar-card">
                            <div class="ppic-ppid-daftar-icon">
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            <p><?php echo esc_html( $desc ); ?></p>
                            <a href="<?php echo esc_url( $link ); ?>" class="ppic-ppid-daftar-btn-outline">
                                <?php echo esc_html( $label ); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $atts['cta_text'] ) ) : ?>
                <div class="ppic-ppid-daftar-cta-wrapper">
                    <a href="<?php echo esc_url( $atts['cta_url'] ); ?>" class="ppic-ppid-daftar-cta">
                        <?php echo esc_html( $atts['cta_text'] ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_daftar_informasi_element' );
function ppic_register_ppid_daftar_informasi_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_cards = array(
        array(
            'icon'  => 'fas fa-calendar-alt',
            'title' => 'Informasi Berkala',
            'desc'  => 'Informasi yang wajib disediakan dan diumumkan secara berkala.',
            'label' => 'Selengkapnya',
            'link'  => '#'
        ),
        array(
            'icon'  => 'fas fa-clock',
            'title' => 'Informasi Setiap Saat',
            'desc'  => 'Informasi yang tersedia setiap saat dan dapat diakses publik.',
            'label' => 'Selengkapnya',
            'link'  => '#'
        ),
        array(
            'icon'  => 'fas fa-bolt',
            'title' => 'Informasi Serta Merta',
            'desc'  => 'Informasi yang mengancam hajat hidup orang banyak dan ketertiban umum.',
            'label' => 'Selengkapnya',
            'link'  => '#'
        ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Daftar Info', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_daftar_informasi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-media-document',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Daftar Informasi Publik',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Sebagai bentuk komitmen keterbukaan, kami menyediakan informasi yang dapat diakses secara berkala, setiap saat, maupun serta merta sesuai regulasi.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Kartu Informasi', 'ppic-custom-element' ),
                    'param_name' => 'cards',
                    'value'      => urlencode( wp_json_encode( $dummy_cards ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Ikon (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'description' => 'Contoh: fas fa-calendar-alt',
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
                            'heading'    => __( 'Label Tombol', 'ppic-custom-element' ),
                            'param_name' => 'label',
                            'value'      => 'Selengkapnya',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Tombol', 'ppic-custom-element' ),
                            'param_name' => 'link',
                            'value'      => '#',
                        ),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Bawah (CTA)', 'ppic-custom-element' ),
                    'param_name' => 'cta_text',
                    'value'      => 'Jelajahi Semua Informasi Publik',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'URL Tombol Bawah (CTA)', 'ppic-custom-element' ),
                    'param_name' => 'cta_url',
                    'value'      => '#',
                ),
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