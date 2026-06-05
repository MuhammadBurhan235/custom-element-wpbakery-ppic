<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Render Shortcode
add_shortcode( 'ppic_jalur_sipencatar', 'ppic_jalur_sipencatar_render' );
function ppic_jalur_sipencatar_render( $atts ) {
    
    // Data Default (Pola Pembibitan & Mandiri)
    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class'    => 'fas fa-chalkboard-teacher', 
            'item_title'    => 'Pola Pembibitan', 
            'item_desc'     => 'Jalur seleksi nasional yang diperuntukkan bagi lulusan SMA/SMK/MA sederajat dengan akreditasi minimal B. Peserta akan mengikuti rangkaian tes akademik, kesehatan, psikologi, dan wawancara. Lulusan jalur ini berhak mendapatkan penempatan di unit kerja Kementerian Perhubungan sesuai kebutuhan.',
            'item_features' => "Pendaftaran gratis (tidak dipungut biaya)\nBeasiswa penuh selama pendidikan (Pola Pembibitan)\nPenempatan kerja setelah lulus di lingkungan Kementerian Perhubungan\nKuota terbatas, seleksi sangat ketat",
            'item_badge'    => 'Pendaftaran: Maret - Mei 2025',
            'is_highlight'  => false
        ),
        array( 
            'icon_class'    => 'fas fa-user-graduate', 
            'item_title'    => 'Jalur Mandiri', 
            'item_desc'     => 'Jalur penerimaan yang dibuka oleh PPI Curug secara independen untuk memenuhi kuota taruna. Peserta harus lulus tes seleksi (akademik, kesehatan, psikologi, wawancara) dan bersedia membiayai pendidikan sesuai ketentuan yang berlaku.',
            'item_features' => "Pendaftaran dikenakan biaya administrasi\nBiaya pendidikan sesuai standar politeknik kedinasan\nBebas memilih program studi yang tersedia\nPeluang beasiswa berprestasi dan bantuan KIP Kuliah",
            'item_badge'    => 'Pendaftaran: April - Juni 2025',
            'is_highlight'  => true // Otomatis bergaris oranye seperti di mockup
        )
    ) ) );

    $atts = shortcode_atts(
        array(
            'title'       => 'Jalur Penerimaan',
            'subtitle'    => 'PPI Curug membuka dua jalur seleksi nasional untuk menjadi taruna. Pilih jalur yang paling sesuai dengan prestasi dan kesiapan Anda.',
            'jalur_items' => $default_items,
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    // Parse Data
    $items = array();
    if ( ! empty( $atts['jalur_items'] ) ) {
        $items = vc_param_group_parse_atts( $atts['jalur_items'] );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-jalur-section ' . esc_attr( $atts['el_class'] );

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
                        $desc       = ! empty( $item['item_desc'] ) ? $item['item_desc'] : '';
                        $features   = ! empty( $item['item_features'] ) ? explode( "\n", $item['item_features'] ) : array();
                        $badge      = ! empty( $item['item_badge'] ) ? $item['item_badge'] : '';
                        $highlight  = ( isset( $item['is_highlight'] ) && $item['is_highlight'] == 'true' ) ? ' highlight' : '';
                        ?>
                        <div class="info-card<?php echo $highlight; ?>">
                            <div class="card-icon">
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            <?php if ( ! empty( $desc ) ) : ?>
                                <p><?php echo esc_html( $desc ); ?></p>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $features ) ) : ?>
                                <ul class="info-list">
                                    <?php foreach ( $features as $feature ) : 
                                        $feature = trim( $feature );
                                        if ( empty( $feature ) ) continue;
                                    ?>
                                        <li><?php echo esc_html( $feature ); ?></li>
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
add_action( 'vc_before_init', 'ppic_jalur_sipencatar_map' );
function ppic_jalur_sipencatar_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class'    => 'fas fa-chalkboard-teacher', 
            'item_title'    => 'Pola Pembibitan', 
            'item_desc'     => 'Jalur seleksi nasional yang diperuntukkan bagi lulusan SMA/SMK/MA sederajat dengan akreditasi minimal B. Peserta akan mengikuti rangkaian tes akademik, kesehatan, psikologi, dan wawancara. Lulusan jalur ini berhak mendapatkan penempatan di unit kerja Kementerian Perhubungan sesuai kebutuhan.',
            'item_features' => "Pendaftaran gratis (tidak dipungut biaya)\nBeasiswa penuh selama pendidikan (Pola Pembibitan)\nPenempatan kerja setelah lulus di lingkungan Kementerian Perhubungan\nKuota terbatas, seleksi sangat ketat",
            'item_badge'    => 'Pendaftaran: Maret - Mei 2025',
            'is_highlight'  => false
        ),
        array( 
            'icon_class'    => 'fas fa-user-graduate', 
            'item_title'    => 'Jalur Mandiri', 
            'item_desc'     => 'Jalur penerimaan yang dibuka oleh PPI Curug secara independen untuk memenuhi kuota taruna. Peserta harus lulus tes seleksi (akademik, kesehatan, psikologi, wawancara) dan bersedia membiayai pendidikan sesuai ketentuan yang berlaku.',
            'item_features' => "Pendaftaran dikenakan biaya administrasi\nBiaya pendidikan sesuai standar politeknik kedinasan\nBebas memilih program studi yang tersedia\nPeluang beasiswa berprestasi dan bantuan KIP Kuliah",
            'item_badge'    => 'Pendaftaran: April - Juni 2025',
            'is_highlight'  => true
        )
    ) ) );

    vc_map(
        array(
            'name'     => __( 'PPIC Jalur Penerimaan Sipencatar', 'ppic-custom-element' ),
            'base'     => 'ppic_jalur_sipencatar',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-networking',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Jalur Penerimaan Sipencatar',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'PPI Curug membuka dua jalur seleksi nasional untuk menjadi taruna. Pilih jalur yang paling sesuai dengan prestasi dan kesiapan Anda.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Jalur', 'ppic-custom-element' ),
                    'param_name' => 'jalur_items',
                    'value'      => $default_items,
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value'      => 'fas fa-user-graduate',
                            'description'=> __( 'Contoh: fas fa-chalkboard-teacher', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Nama Jalur', 'ppic-custom-element' ),
                            'param_name'  => 'item_title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                            'param_name' => 'item_desc',
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Daftar Keuntungan (Bullet Points)', 'ppic-custom-element' ),
                            'param_name' => 'item_features',
                            'description'=> __( 'Pisahkan dengan Enter (baris baru) untuk setiap poin.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Teks Badge (Bawah)', 'ppic-custom-element' ),
                            'param_name' => 'item_badge',
                            'description'=> __( 'Contoh: Pendaftaran: Maret - Mei 2025', 'ppic-custom-element' ),
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