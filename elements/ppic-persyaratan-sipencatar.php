<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Render Shortcode
add_shortcode( 'ppic_persyaratan_sipencatar', 'ppic_persyaratan_sipencatar_render' );
function ppic_persyaratan_sipencatar_render( $atts ) {
    
    // Data Default (3 Kolom Persyaratan)
    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class'    => 'fas fa-calendar-alt', 
            'item_title'    => 'Usia & Pendidikan', 
            'item_features' => "Usia maksimal 21 tahun per 1 September tahun pendaftaran\nLulusan SMA/SMK/MA (IPA atau teknik) tahun berjalan atau 2 tahun sebelumnya\nNilai rata-rata rapor minimal 75 (skala 100) atau ekuivalen\nAkreditasi sekolah minimal B"
        ),
        array( 
            'icon_class'    => 'fas fa-heartbeat', 
            'item_title'    => 'Kesehatan & Fisik', 
            'item_features' => "Tinggi badan minimal 160 cm (pria) / 155 cm (wanita)\nTidak buta warna (total/parsial)\nBerat badan ideal sesuai IMT\nBebas dari riwayat penyakit serius (jantung, epilepsi, asma berat)\nTidak memiliki tato atau tindik (kecuali untuk tindik medis)"
        ),
        array( 
            'icon_class'    => 'fas fa-file-alt', 
            'item_title'    => 'Dokumen & Lainnya', 
            'item_features' => "Ijazah atau SKL (Surat Keterangan Lulus)\nTranskrip nilai rapor (semester 1-5 atau 1-6)\nKTP atau KK (Kartu Keluarga)\nPas foto terbaru (background merah)\nMengikuti seluruh tahapan seleksi dengan jujur"
        )
    ) ) );

    $atts = shortcode_atts(
        array(
            'title'        => 'Persyaratan Umum',
            'subtitle'     => 'Pastikan Anda memenuhi seluruh persyaratan sebelum melakukan pendaftaran. Persyaratan berlaku untuk semua jalur penerimaan.',
            'syarat_items' => $default_items,
            'bottom_note'  => '*Persyaratan khusus program studi dapat dilihat pada panduan SIPENCATAR',
            'el_id'        => '',
            'el_class'     => '',
        ),
        $atts
    );

    // Parse Data
    $items = array();
    if ( ! empty( $atts['syarat_items'] ) ) {
        $items = vc_param_group_parse_atts( $atts['syarat_items'] );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-persyaratan-section ' . esc_attr( $atts['el_class'] );

    // Pastikan FontAwesome dimuat
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
                <div class="grid-3">
                    <?php foreach ( $items as $item ) : ?>
                        <?php 
                        $icon     = ! empty( $item['icon_class'] ) ? $item['icon_class'] : 'fas fa-check';
                        $title    = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
                        $features = ! empty( $item['item_features'] ) ? explode( "\n", $item['item_features'] ) : array();
                        ?>
                        <div class="requirement-card">
                            <div class="card-icon">
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            
                            <?php if ( ! empty( $features ) ) : ?>
                                <ul class="requirement-list">
                                    <?php foreach ( $features as $feature ) : 
                                        $feature = trim( $feature );
                                        if ( empty( $feature ) ) continue;
                                    ?>
                                        <li><?php echo esc_html( $feature ); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $atts['bottom_note'] ) ) : ?>
                <div class="persyaratan-bottom-note">
                    <span class="badge-info"><?php echo esc_html( $atts['bottom_note'] ); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// 2. Mapping ke WPBakery
add_action( 'vc_before_init', 'ppic_persyaratan_sipencatar_map' );
function ppic_persyaratan_sipencatar_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class'    => 'fas fa-calendar-alt', 
            'item_title'    => 'Usia & Pendidikan', 
            'item_features' => "Usia maksimal 21 tahun per 1 September tahun pendaftaran\nLulusan SMA/SMK/MA (IPA atau teknik) tahun berjalan atau 2 tahun sebelumnya\nNilai rata-rata rapor minimal 75 (skala 100) atau ekuivalen\nAkreditasi sekolah minimal B"
        ),
        array( 
            'icon_class'    => 'fas fa-heartbeat', 
            'item_title'    => 'Kesehatan & Fisik', 
            'item_features' => "Tinggi badan minimal 160 cm (pria) / 155 cm (wanita)\nTidak buta warna (total/parsial)\nBerat badan ideal sesuai IMT\nBebas dari riwayat penyakit serius (jantung, epilepsi, asma berat)\nTidak memiliki tato atau tindik (kecuali untuk tindik medis)"
        ),
        array( 
            'icon_class'    => 'fas fa-file-alt', 
            'item_title'    => 'Dokumen & Lainnya', 
            'item_features' => "Ijazah atau SKL (Surat Keterangan Lulus)\nTranskrip nilai rapor (semester 1-5 atau 1-6)\nKTP atau KK (Kartu Keluarga)\nPas foto terbaru (background merah)\nMengikuti seluruh tahapan seleksi dengan jujur"
        )
    ) ) );

    vc_map(
        array(
            'name'     => __( 'PPIC Persyaratan Umum Sipencatar', 'ppic-custom-element' ),
            'base'     => 'ppic_persyaratan_sipencatar',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-clipboard',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Persyaratan Umum',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Pastikan Anda memenuhi seluruh persyaratan sebelum melakukan pendaftaran. Persyaratan berlaku untuk semua jalur penerimaan.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Persyaratan (Kolom)', 'ppic-custom-element' ),
                    'param_name' => 'syarat_items',
                    'value'      => $default_items,
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value'      => 'fas fa-check',
                            'description'=> __( 'Contoh: fas fa-calendar-alt', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Judul Kolom', 'ppic-custom-element' ),
                            'param_name'  => 'item_title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Daftar Syarat (Bullet Points)', 'ppic-custom-element' ),
                            'param_name' => 'item_features',
                            'description'=> __( 'Pisahkan dengan Enter (baris baru) untuk setiap poin.', 'ppic-custom-element' ),
                        ),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Catatan Bawah (Badge Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'bottom_note',
                    'value'      => '*Persyaratan khusus program studi dapat dilihat pada panduan SIPENCATAR',
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