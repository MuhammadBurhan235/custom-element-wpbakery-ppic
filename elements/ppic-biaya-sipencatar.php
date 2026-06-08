<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Render Shortcode
add_shortcode( 'ppic_biaya_sipencatar', 'ppic_biaya_sipencatar_render' );
function ppic_biaya_sipencatar_render( $atts ) {
    
    // Data Default
    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class'    => 'fas fa-wallet', 
            'item_title'    => 'Biaya Pendaftaran', 
            'item_features' => "<strong>Pola Pembibitan:</strong> GRATIS\n<strong>Jalur Mandiri:</strong> Rp 350.000 (belum termasuk biaya seleksi)\nBiaya seleksi (kesehatan, psikologi) dibayar di tempat saat pelaksanaan",
            'item_badge'    => '',
            'is_highlight'  => false
        ),
        array( 
            'icon_class'    => 'fas fa-graduation-cap', 
            'item_title'    => 'Biaya Pendidikan per Semester (Jalur Mandiri)', 
            'item_features' => "Sumbangan Pengembangan Institusi (SPI): Rp 12.000.000 - 25.000.000 (sekali bayar di awal)\nUang Kuliah Tunggal (UKT): Rp 4.500.000 per semester\nBiaya asrama dan perlengkapan: sekitar Rp 3.000.000 per semester\nTotal perkiraan per semester (setelah SPI): Rp 7.500.000 - 9.000.000",
            'item_badge'    => 'Biaya bisa berubah sesuai kebijakan. Peserta Pola Pembibitan gratis.',
            'is_highlight'  => true
        )
    ) ) );

    $atts = shortcode_atts(
        array(
            'title'       => 'Biaya Pendidikan',
            'subtitle'    => 'Ringkasan biaya pendidikan untuk Jalur Mandiri. Peserta Pola Pembibitan tidak dikenakan biaya pendidikan (beasiswa penuh).',
            'biaya_items' => $default_items,
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    // Parse Data
    $items = array();
    if ( ! empty( $atts['biaya_items'] ) ) {
        $items = vc_param_group_parse_atts( $atts['biaya_items'] );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-biaya-section ' . esc_attr( $atts['el_class'] );

    // Pastikan FontAwesome diload
    if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
        vc_icon_element_fonts_enqueue( 'fontawesome' );
    }

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container">
            <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="section-sub"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
                <div class="grid-2">
                    <?php foreach ( $items as $item ) : ?>
                        <?php 
                        $icon       = ! empty( $item['icon_class'] ) ? $item['icon_class'] : 'fas fa-check';
                        $title      = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
                        $features   = ! empty( $item['item_features'] ) ? explode( "\n", $item['item_features'] ) : array();
                        $badge      = ! empty( $item['item_badge'] ) ? $item['item_badge'] : '';
                        $highlight  = ( isset( $item['is_highlight'] ) && $item['is_highlight'] == 'true' ) ? ' highlight' : '';
                        ?>
                        <div class="info-card<?php echo $highlight; ?>">
                            <div class="card-icon">
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            
                            <?php if ( ! empty( $features ) ) : ?>
                                <ul class="info-list">
                                    <?php foreach ( $features as $feature ) : 
                                        $feature = trim( $feature );
                                        if ( empty( $feature ) ) continue;
                                    ?>
                                        <!-- wp_kses_post agar tag <strong> terbaca -->
                                        <li><?php echo wp_kses_post( $feature ); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if ( ! empty( $badge ) ) : ?>
                                <div class="badge-wrapper">
                                    <span class="badge-info"><?php echo esc_html( $badge ); ?></span>
                                </div>
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

// 2. Mapping ke WPBakery
add_action( 'vc_before_init', 'ppic_biaya_sipencatar_map' );
function ppic_biaya_sipencatar_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class'    => 'fas fa-wallet', 
            'item_title'    => 'Biaya Pendaftaran', 
            'item_features' => "<strong>Pola Pembibitan:</strong> GRATIS\n<strong>Jalur Mandiri:</strong> Rp 350.000 (belum termasuk biaya seleksi)\nBiaya seleksi (kesehatan, psikologi) dibayar di tempat saat pelaksanaan",
            'item_badge'    => '',
            'is_highlight'  => false
        ),
        array( 
            'icon_class'    => 'fas fa-graduation-cap', 
            'item_title'    => 'Biaya Pendidikan per Semester (Jalur Mandiri)', 
            'item_features' => "Sumbangan Pengembangan Institusi (SPI): Rp 12.000.000 - 25.000.000 (sekali bayar di awal)\nUang Kuliah Tunggal (UKT): Rp 4.500.000 per semester\nBiaya asrama dan perlengkapan: sekitar Rp 3.000.000 per semester\nTotal perkiraan per semester (setelah SPI): Rp 7.500.000 - 9.000.000",
            'item_badge'    => 'Biaya bisa berubah sesuai kebijakan. Peserta Pola Pembibitan gratis.',
            'is_highlight'  => true
        )
    ) ) );

    vc_map(
        array(
            'name'     => __( 'PPIC Biaya Pendidikan Sipencatar', 'ppic-custom-element' ),
            'base'     => 'ppic_biaya_sipencatar',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-money-alt',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Biaya Pendidikan',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Ringkasan biaya pendidikan untuk Jalur Mandiri. Peserta Pola Pembibitan tidak dikenakan biaya pendidikan (beasiswa penuh).',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Biaya', 'ppic-custom-element' ),
                    'param_name' => 'biaya_items',
                    'value'      => $default_items,
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value'      => 'fas fa-wallet',
                            'description'=> __( 'Contoh: fas fa-wallet', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Nama Kartu Biaya', 'ppic-custom-element' ),
                            'param_name'  => 'item_title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Rincian Biaya (Bullet Points)', 'ppic-custom-element' ),
                            'param_name' => 'item_features',
                            'description'=> __( 'Pisahkan dengan Enter. Anda juga bisa menggunakan tag &lt;strong&gt;Teks&lt;/strong&gt; untuk menebalkan teks.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Teks Badge (Bawah)', 'ppic-custom-element' ),
                            'param_name' => 'item_badge',
                            'description'=> __( 'Kotak kuning kecil di bagian bawah. Kosongkan jika tidak perlu.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'       => 'checkbox',
                            'heading'    => __( 'Highlight Kotak (Garis Oranye)', 'ppic-custom-element' ),
                            'param_name' => 'is_highlight',
                            'value'      => array( __( 'Ya, jadikan kotak ini bergaris oranye', 'ppic-custom-element' ) => 'true' ),
                        ),
                    ),
                ),
                array(
                    'type'       => 'el_id',
                    'heading'    => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                ),
            ),
        )
    );
}