<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Render Shortcode
add_shortcode( 'ppic_pendaftaran_sipencatar', 'ppic_pendaftaran_sipencatar_render' );
function ppic_pendaftaran_sipencatar_render( $atts ) {
    
    // Data Default 3 Langkah Pendaftaran
    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class' => 'fas fa-globe', 
            'item_title' => '1. Registrasi Akun', 
            'item_desc'  => 'Buka portal resmi SIPENCATAR di <strong>https://sipencatar.ppicurug.ac.id</strong>. Buat akun dengan mengisi data diri (email aktif dan nomor HP).',
            'item_badge' => 'Pastikan email dan nomor HP valid'
        ),
        array( 
            'icon_class' => 'fas fa-upload', 
            'item_title' => '2. Isi Biodata & Upload Dokumen', 
            'item_desc'  => 'Login, lengkapi biodata, unggah persyaratan dokumen (ijazah, rapor, KTP, pas foto, dan sertifikat pendukung).',
            'item_badge' => 'Gunakan format PDF/JPG, maks 2MB'
        ),
        array( 
            'icon_class' => 'fas fa-credit-card', 
            'item_title' => '3. Pembayaran & Cetak Kartu', 
            'item_desc'  => 'Lakukan pembayaran biaya pendaftaran (jika jalur mandiri) melalui bank yang ditunjuk. Cetak kartu peserta ujian sebagai bukti.',
            'item_badge' => 'Simpan kartu peserta sampai seleksi selesai'
        )
    ) ) );

    $atts = shortcode_atts(
        array(
            'title'       => 'Cara Pendaftaran',
            'subtitle'    => 'Ikuti langkah-langkah berikut untuk menjadi calon taruna PPI Curug. Semua proses dilakukan secara online melalui portal SIPENCATAR.',
            'steps_items' => $default_items,
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    // Parse Data
    $items = array();
    if ( ! empty( $atts['steps_items'] ) ) {
        $items = vc_param_group_parse_atts( $atts['steps_items'] );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-pendaftaran-section ' . esc_attr( $atts['el_class'] );

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
                        $icon  = ! empty( $item['icon_class'] ) ? $item['icon_class'] : 'fas fa-check';
                        $title = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
                        $desc  = ! empty( $item['item_desc'] ) ? $item['item_desc'] : '';
                        $badge = ! empty( $item['item_badge'] ) ? $item['item_badge'] : '';
                        ?>
                        <div class="info-card">
                            <div class="card-icon">
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            <?php if ( ! empty( $desc ) ) : ?>
                                <p><?php echo wp_kses_post( $desc ); ?></p>
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
add_action( 'vc_before_init', 'ppic_pendaftaran_sipencatar_map' );
function ppic_pendaftaran_sipencatar_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class' => 'fas fa-globe', 
            'item_title' => '1. Registrasi Akun', 
            'item_desc'  => 'Buka portal resmi SIPENCATAR di <strong>https://sipencatar.ppicurug.ac.id</strong>. Buat akun dengan mengisi data diri (email aktif dan nomor HP).',
            'item_badge' => 'Pastikan email dan nomor HP valid'
        ),
        array( 
            'icon_class' => 'fas fa-upload', 
            'item_title' => '2. Isi Biodata & Upload Dokumen', 
            'item_desc'  => 'Login, lengkapi biodata, unggah persyaratan dokumen (ijazah, rapor, KTP, pas foto, dan sertifikat pendukung).',
            'item_badge' => 'Gunakan format PDF/JPG, maks 2MB'
        ),
        array( 
            'icon_class' => 'fas fa-credit-card', 
            'item_title' => '3. Pembayaran & Cetak Kartu', 
            'item_desc'  => 'Lakukan pembayaran biaya pendaftaran (jika jalur mandiri) melalui bank yang ditunjuk. Cetak kartu peserta ujian sebagai bukti.',
            'item_badge' => 'Simpan kartu peserta sampai seleksi selesai'
        )
    ) ) );

    vc_map(
        array(
            'name'     => __( 'PPIC Cara Pendaftaran Sipencatar', 'ppic-custom-element' ),
            'base'     => 'ppic_pendaftaran_sipencatar',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-write-blog',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Cara Pendaftaran',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Ikuti langkah-langkah berikut untuk menjadi calon taruna PPI Curug. Semua proses dilakukan secara online melalui portal SIPENCATAR.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Langkah-langkah Pendaftaran', 'ppic-custom-element' ),
                    'param_name' => 'steps_items',
                    'value'      => $default_items,
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value'      => 'fas fa-globe',
                            'description'=> __( 'Contoh: fas fa-upload', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Judul Langkah', 'ppic-custom-element' ),
                            'param_name'  => 'item_title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea_html', /* Menggunakan textarea_html agar user bisa menebalkan teks link */
                            'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                            'param_name' => 'item_desc',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Catatan / Teks Badge', 'ppic-custom-element' ),
                            'param_name' => 'item_badge',
                            'description'=> __( 'Muncul sebagai kotak kecil di bawah deskripsi.', 'ppic-custom-element' ),
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