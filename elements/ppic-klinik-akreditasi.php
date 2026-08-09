<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_klinik_akreditasi_bpjs', 'ppic_klinik_akreditasi_bpjs_render' );
function ppic_klinik_akreditasi_bpjs_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // Kartu 1: Akreditasi
            'c1_icon'      => 'fas fa-certificate',
            'c1_title'     => 'Nomor Akreditasi Klinik',
            'c1_val'       => 'YM.02.01/D/44761/2024',
            'c1_note'      => '🖱 Klik untuk lihat sertifikat',
            'c1_id'        => 'certTrigger',
            
            // Pop-up Sertifikat
            'popup_image'  => '',
            'popup_text'   => 'Sertifikat Akreditasi Klinik PPI Curug',
            
            // Kartu 2: BPJS
            'c2_icon'      => 'fas fa-id-card',
            'c2_title'     => 'Kode Faskes BPJS',
            'c2_val'       => '36030300233',
            
            // Kartu 3: Jam Pelayanan
            'c3_icon'      => 'fas fa-clock',
            'c3_title1'    => 'Jam Pelayanan BPJS',
            'c3_val1'      => 'Senin–Jumat 08.00 – 16.00 WIB',
            'c3_title2'    => 'Jam Pelayanan Taruna',
            'c3_val2'      => 'Setiap Hari 24 Jam',
            
            'el_class'     => '',
        ),
        $atts
    );

    $wrapper_class = 'ppic-klinik-akreditasi' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse Gambar Pop-up
    $popup_img_url = 'https://lh3.googleusercontent.com/d/1wbzKdXo8vSX1xo7GcPBAZI1C9vzVtuFL'; // Fallback bawaan
    if ( ! empty( $atts['popup_image'] ) ) {
        $img = wp_get_attachment_image_url( $atts['popup_image'], 'full' );
        if ( $img ) $popup_img_url = $img;
    }

    $unique_id = esc_attr( $atts['c1_id'] ) . '_' . wp_rand(100, 999);

    ob_start(); ?>
    
    <section class="<?php echo $wrapper_class; ?>">
        <div class="ppic-akreditasi-container">
            <div class="ppic-akreditasi-grid">
                
                <!-- Kartu 1: Akreditasi (Clickable) -->
                <div class="ppic-akreditasi-item clickable" id="<?php echo $unique_id; ?>_trigger">
                    <i class="<?php echo esc_attr( $atts['c1_icon'] ); ?>"></i>
                    <h4><?php echo esc_html( $atts['c1_title'] ); ?></h4>
                    <p><?php echo esc_html( $atts['c1_val'] ); ?></p>
                    <?php if ( ! empty( $atts['c1_note'] ) ) : ?>
                        <small class="akreditasi-note"><?php echo esc_html( $atts['c1_note'] ); ?></small>
                    <?php endif; ?>
                </div>

                <!-- Kartu 2: BPJS -->
                <div class="ppic-akreditasi-item">
                    <i class="<?php echo esc_attr( $atts['c2_icon'] ); ?>"></i>
                    <h4><?php echo esc_html( $atts['c2_title'] ); ?></h4>
                    <p><?php echo esc_html( $atts['c2_val'] ); ?></p>
                </div>

                <!-- Kartu 3: Jam Pelayanan -->
                <div class="ppic-akreditasi-item">
                    <i class="<?php echo esc_attr( $atts['c3_icon'] ); ?>"></i>
                    <h4><?php echo esc_html( $atts['c3_title1'] ); ?></h4>
                    <p><?php echo esc_html( $atts['c3_val1'] ); ?></p>
                    
                    <h4 class="mt-3"><?php echo esc_html( $atts['c3_title2'] ); ?></h4>
                    <p><?php echo esc_html( $atts['c3_val2'] ); ?></p>
                </div>

            </div>
        </div>

        <!-- MODAL POPUP SERTIFIKAT -->
        <div class="cert-popup" id="<?php echo $unique_id; ?>_popup">
            <div class="cert-content">
                <button class="cert-close" id="<?php echo $unique_id; ?>_close" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
                <img src="<?php echo esc_url( $popup_img_url ); ?>" alt="<?php echo esc_attr( $atts['popup_text'] ); ?>" />
                <div class="cert-label"><?php echo esc_html( $atts['popup_text'] ); ?></div>
            </div>
        </div>
    </section>

    <!-- JS Untuk Mengontrol Pop-up -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.getElementById('<?php echo $unique_id; ?>_trigger');
        const popup = document.getElementById('<?php echo $unique_id; ?>_popup');
        const closeBtn = document.getElementById('<?php echo $unique_id; ?>_close');

        if (trigger && popup && closeBtn) {
            // Tampilkan pop-up
            trigger.addEventListener('click', function() {
                popup.classList.add('show');
                document.body.style.overflow = 'hidden'; // Kunci background agar tidak bisa di-scroll
            });

            // Tutup pop-up via tombol silang
            closeBtn.addEventListener('click', function() {
                popup.classList.remove('show');
                document.body.style.overflow = '';
            });

            // Tutup pop-up dengan klik area gelap (luar gambar)
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
            
            // Tutup dengan tombol ESC pada keyboard
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape" && popup.classList.contains('show')) {
                    popup.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        }
    });
    </script>

    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_klinik_akreditasi_bpjs_element' );
function ppic_register_klinik_akreditasi_bpjs_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Klinik Akreditasi BPJS', 'ppic-custom-element' ),
            'base'     => 'ppic_klinik_akreditasi_bpjs',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-id',
            'params'   => array(
                // KARTU 1 (AKREDITASI)
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Kartu 1', 'ppic-custom-element' ),
                    'param_name'  => 'c1_icon',
                    'value'       => 'fas fa-certificate',
                    'group'       => __( 'Kartu Akreditasi', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Kartu 1', 'ppic-custom-element' ),
                    'param_name'  => 'c1_title',
                    'value'       => 'Nomor Akreditasi Klinik',
                    'group'       => __( 'Kartu Akreditasi', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Nilai / Nomor', 'ppic-custom-element' ),
                    'param_name'  => 'c1_val',
                    'value'       => 'YM.02.01/D/44761/2024',
                    'group'       => __( 'Kartu Akreditasi', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Catatan Kecil', 'ppic-custom-element' ),
                    'param_name'  => 'c1_note',
                    'value'       => '🖱 Klik untuk lihat sertifikat',
                    'group'       => __( 'Kartu Akreditasi', 'ppic-custom-element' ),
                ),
                
                // PENGATURAN POP-UP SERTIFIKAT
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Sertifikat (Untuk Pop-up)', 'ppic-custom-element' ),
                    'param_name'  => 'popup_image',
                    'group'       => __( 'Kartu Akreditasi', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Bawah Sertifikat', 'ppic-custom-element' ),
                    'param_name'  => 'popup_text',
                    'value'       => 'Sertifikat Akreditasi Klinik PPI Curug',
                    'group'       => __( 'Kartu Akreditasi', 'ppic-custom-element' ),
                ),

                // KARTU 2 (BPJS)
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Kartu 2', 'ppic-custom-element' ),
                    'param_name'  => 'c2_icon',
                    'value'       => 'fas fa-id-card',
                    'group'       => __( 'Kartu BPJS', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Kartu 2', 'ppic-custom-element' ),
                    'param_name'  => 'c2_title',
                    'value'       => 'Kode Faskes BPJS',
                    'group'       => __( 'Kartu BPJS', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Nilai / Kode', 'ppic-custom-element' ),
                    'param_name'  => 'c2_val',
                    'value'       => '36030300233',
                    'group'       => __( 'Kartu BPJS', 'ppic-custom-element' ),
                ),

                // KARTU 3 (JADWAL)
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Kartu 3', 'ppic-custom-element' ),
                    'param_name'  => 'c3_icon',
                    'value'       => 'fas fa-clock',
                    'group'       => __( 'Kartu Jadwal', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul 1 (Jadwal BPJS)', 'ppic-custom-element' ),
                    'param_name'  => 'c3_title1',
                    'value'       => 'Jam Pelayanan BPJS',
                    'group'       => __( 'Kartu Jadwal', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Waktu 1', 'ppic-custom-element' ),
                    'param_name'  => 'c3_val1',
                    'value'       => 'Senin–Jumat 08.00 – 16.00 WIB',
                    'group'       => __( 'Kartu Jadwal', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul 2 (Jadwal Taruna)', 'ppic-custom-element' ),
                    'param_name'  => 'c3_title2',
                    'value'       => 'Jam Pelayanan Taruna',
                    'group'       => __( 'Kartu Jadwal', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Waktu 2', 'ppic-custom-element' ),
                    'param_name'  => 'c3_val2',
                    'value'       => 'Setiap Hari 24 Jam',
                    'group'       => __( 'Kartu Jadwal', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                ),
            ),
        )
    );
}