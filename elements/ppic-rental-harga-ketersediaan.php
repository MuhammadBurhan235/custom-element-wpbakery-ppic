<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_rental_harga_ketersediaan', 'ppic_rental_harga_ketersediaan_render' );
function ppic_rental_harga_ketersediaan_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // KONTEN TEKS
            'title'          => 'Butuh informasi harga dan ketersediaan?',
            'desc'           => 'Daftar tarif lengkap berdasarkan SK Direktur KP-PPIC 257 Tahun 2025.<br/>Hubungi tim layanan aset kami.',
            
            // TOMBOL PDF
            'btn_pdf_text'   => 'Lihat Daftar Harga Lengkap (PDF)',
            'btn_pdf_url'    => 'url:%23|target:_blank',
            
            // TOMBOL WHATSAPP
            'btn_wa_text'    => 'Hubungi via WhatsApp',
            'wa_number'      => '6287778229661',
            'wa_msg'         => 'Halo PPI Curug, saya mau tanya info sewa aset secara umum.',
            
            // TOMBOL EMAIL
            'btn_email_text' => 'Email: dpu@ppicurug.ac.id',
            'email_address'  => 'dpu@ppicurug.ac.id',
            
            // INFO KONTAK BAWAH
            'phone_text'     => 'Telepon: +62 877-7822-9661',
            'address_text'   => 'Jl. Raya Curug, Tangerang, Banten 15810',
            
            // UMUM
            'el_id'          => '',
            'el_class'       => '',
        ),
        $atts
    );

    // Parsing Link PDF
    $link_pdf = ( '||' !== $atts['btn_pdf_url'] ) ? vc_build_link( $atts['btn_pdf_url'] ) : '';
    $a_href_pdf   = ! empty( $link_pdf['url'] ) ? $link_pdf['url'] : '#';
    $a_target_pdf = ! empty( $link_pdf['target'] ) ? ' target="' . esc_attr( trim( $link_pdf['target'] ) ) . '"' : '';

    // URL WhatsApp Dinamis
    $no_wa  = preg_replace('/[^0-9]/', '', $atts['wa_number']);
    $wa_url = 'https://wa.me/' . $no_wa . '?text=' . rawurlencode( $atts['wa_msg'] );

    // URL Mailto
    $email_url = 'mailto:' . sanitize_email( $atts['email_address'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'rental-cta-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container">
            <div class="rental-cta-box">
                
                <h2 class="cta-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <p class="cta-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                
                <div class="cta-buttons-wrapper">
                    <!-- Tombol PDF (Kuning) -->
                    <?php if ( ! empty( $atts['btn_pdf_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $a_href_pdf ); ?>" class="btn-cta-pdf"<?php echo $a_target_pdf; ?>>
                            <i class="fas fa-file-invoice"></i> <?php echo esc_html( $atts['btn_pdf_text'] ); ?>
                        </a>
                    <?php endif; ?>

                    <!-- Tombol WhatsApp (Biru Navy) -->
                    <?php if ( ! empty( $atts['btn_wa_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn-cta-navy">
                            <i class="fab fa-whatsapp"></i> <?php echo esc_html( $atts['btn_wa_text'] ); ?>
                        </a>
                    <?php endif; ?>

                    <!-- Tombol Email (Biru Navy) -->
                    <?php if ( ! empty( $atts['btn_email_text'] ) ) : ?>
                        <a href="<?php echo esc_url( $email_url ); ?>" class="btn-cta-navy">
                            <i class="fas fa-envelope"></i> <?php echo esc_html( $atts['btn_email_text'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Info Footer -->
                <div class="cta-footer-info">
                    <?php if ( ! empty( $atts['phone_text'] ) ) : ?>
                        <span><i class="fas fa-phone-alt"></i> <?php echo esc_html( $atts['phone_text'] ); ?></span>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $atts['phone_text'] ) && ! empty( $atts['address_text'] ) ) : ?>
                        <span class="separator">|</span>
                    <?php endif; ?>

                    <?php if ( ! empty( $atts['address_text'] ) ) : ?>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $atts['address_text'] ); ?></span>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_rental_harga_ketersediaan_element' );
function ppic_register_rental_harga_ketersediaan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Rental - Harga & Kontak', 'ppic-custom-element' ),
            'base'     => 'ppic_rental_harga_ketersediaan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-phone',
            'params'   => array(
                // TEKS UTAMA
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Butuh informasi harga dan ketersediaan?',
                    'group'      => __( 'Konten', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea_html',
                    'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Daftar tarif lengkap berdasarkan SK Direktur KP-PPIC 257 Tahun 2025.<br/>Hubungi tim layanan aset kami.',
                    'group'      => __( 'Konten', 'ppic-custom-element' ),
                ),

                // PDF
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol PDF', 'ppic-custom-element' ),
                    'param_name' => 'btn_pdf_text',
                    'value'      => 'Lihat Daftar Harga Lengkap (PDF)',
                    'group'      => __( 'Tombol PDF', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL File PDF', 'ppic-custom-element' ),
                    'param_name' => 'btn_pdf_url',
                    'value'      => 'url:%23|target:_blank',
                    'group'      => __( 'Tombol PDF', 'ppic-custom-element' ),
                ),

                // WHATSAPP
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol WhatsApp', 'ppic-custom-element' ),
                    'param_name' => 'btn_wa_text',
                    'value'      => 'Hubungi via WhatsApp',
                    'group'      => __( 'Tombol WhatsApp', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Nomor WhatsApp', 'ppic-custom-element' ),
                    'param_name'  => 'wa_number',
                    'value'       => '6287778229661',
                    'description' => __( 'Gunakan kode negara, contoh: 62812345678.', 'ppic-custom-element' ),
                    'group'       => __( 'Tombol WhatsApp', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Pesan Default WhatsApp', 'ppic-custom-element' ),
                    'param_name' => 'wa_msg',
                    'value'      => 'Halo PPI Curug, saya mau tanya info sewa aset secara umum.',
                    'group'      => __( 'Tombol WhatsApp', 'ppic-custom-element' ),
                ),

                // EMAIL
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Email', 'ppic-custom-element' ),
                    'param_name' => 'btn_email_text',
                    'value'      => 'Email: dpu@ppicurug.ac.id',
                    'group'      => __( 'Tombol Email', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Alamat Email Tujuan', 'ppic-custom-element' ),
                    'param_name' => 'email_address',
                    'value'      => 'dpu@ppicurug.ac.id',
                    'group'      => __( 'Tombol Email', 'ppic-custom-element' ),
                ),

                // INFO BAWAH
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Telepon', 'ppic-custom-element' ),
                    'param_name' => 'phone_text',
                    'value'      => 'Telepon: +62 877-7822-9661',
                    'group'      => __( 'Info Footer', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Alamat', 'ppic-custom-element' ),
                    'param_name' => 'address_text',
                    'value'      => 'Jl. Raya Curug, Tangerang, Banten 15810',
                    'group'      => __( 'Info Footer', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                ),
            ),
        )
    );
}