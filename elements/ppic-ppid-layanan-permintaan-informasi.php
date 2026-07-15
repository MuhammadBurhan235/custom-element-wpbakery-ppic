<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_permintaan_info', 'ppic_ppid_permintaan_info_render' );
function ppic_ppid_permintaan_info_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // KARTU 1: PERMINTAAN INFORMASI
            'c1_title'   => 'Permintaan Informasi',
            'c1_desc'    => 'Masyarakat dapat mengajukan permintaan informasi publik secara tertulis melalui surat, email, atau mengunjungi langsung kantor PPID PPI Curug. Setiap permintaan akan diproses sesuai ketentuan yang berlaku.',
            'c1_steps'   => '',
            
            // KARTU 2: INFORMASI PENTING
            'c2_title'   => 'Informasi Penting',
            'c2_notes'   => '',
            'c2_qr'      => '',
            'c2_qr_text' => 'Scan untuk akses cepat',
            'c2_btn_text'=> 'Ajukan Permintaan Sekarang',
            'c2_btn_url' => 'url:https%3A%2F%2Fdocs.google.com%2Fforms|target:_blank',
            
            // UMUM
            'el_id'      => 'permintaan',
            'el_class'   => '',
        ),
        $atts
    );

    // Parsing Param Groups
    $steps_data = vc_param_group_parse_atts( $atts['c1_steps'] );
    $notes_data = vc_param_group_parse_atts( $atts['c2_notes'] );

    // Fallback Data Langkah-langkah
    if ( empty( $steps_data ) || ! is_array( $steps_data ) ) {
        $steps_data = array(
            array( 'text' => 'Isi formulir permintaan informasi (unduh di website atau ambil di kantor)' ),
            array( 'text' => 'Sertakan salinan identitas diri (KTP/SIM/Paspor) yang masih berlaku' ),
            array( 'text' => 'Kirimkan ke petugas PPID melalui email atau datang langsung ke kantor' ),
            array( 'text' => 'Petugas akan memberikan tanda bukti penerimaan permohonan' ),
            array( 'text' => 'Informasi akan diberikan paling lambat 10 hari kerja setelah permohonan diterima' ),
        );
    }

    // Fallback Data Catatan Penting
    if ( empty( $notes_data ) || ! is_array( $notes_data ) ) {
        $notes_data = array(
            array( 'text' => 'Permintaan informasi dikenakan biaya sesuai Standar Biaya Layanan (fotokopi, rekam, atau pengiriman)' ),
            array( 'text' => 'Informasi yang dikecualikan (sesuai UU KIP Pasal 17) tidak dapat diberikan' ),
            array( 'text' => 'Jika permintaan ditolak, pemohon berhak mengajukan keberatan' ),
        );
    }

    // Parsing Link Tombol CTA (Dengan Fallback yang kuat)
    $link_cta = vc_build_link( $atts['c2_btn_url'] );
    $a_href   = ! empty( $link_cta['url'] ) ? $link_cta['url'] : 'https://docs.google.com/forms';
    $a_target = ! empty( $link_cta['target'] ) ? ' target="' . esc_attr( trim( $link_cta['target'] ) ) . '"' : ' target="_blank"';

    // Parsing Gambar QR Code (Menggunakan Placeholder agar tidak pecah/mati)
    $qr_url = 'https://placehold.co/150x150/e2e8f0/64748b?text=QR+Code';
    if ( ! empty( $atts['c2_qr'] ) ) {
        $qr_src = wp_get_attachment_image_url( $atts['c2_qr'], 'full' );
        if ( $qr_src ) {
            $qr_url = $qr_src;
        }
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-permintaan-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-permintaan-container">
            <div class="permintaan-grid">
                
                <div class="permintaan-card card-light">
                    <div class="permintaan-icon icon-yellow">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="permintaan-title"><?php echo esc_html( $atts['c1_title'] ); ?></h3>
                    <p class="permintaan-desc"><?php echo esc_html( $atts['c1_desc'] ); ?></p>
                    
                    <ul class="permintaan-steps">
                        <?php $no = 1; foreach ( $steps_data as $step ) : 
                            $text = isset( $step['text'] ) ? $step['text'] : '';
                            if( empty($text) ) continue;
                        ?>
                            <li>
                                <span class="step-number"><?php echo $no++; ?></span>
                                <span class="step-text"><?php echo esc_html( $text ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="permintaan-card card-dark">
                    <div class="card-dark-content">
                        <div class="permintaan-icon icon-yellow-transparent">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="permintaan-title text-white"><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                        
                        <ul class="permintaan-notes">
                            <?php foreach ( $notes_data as $note ) : 
                                $text = isset( $note['text'] ) ? $note['text'] : '';
                                if( empty($text) ) continue;
                            ?>
                                <li><?php echo esc_html( $text ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="card-dark-footer">
                        <div class="qr-box">
                            <img src="<?php echo esc_url( $qr_url ); ?>" alt="QR Code" loading="lazy" />
                            <p><?php echo esc_html( $atts['c2_qr_text'] ); ?></p>
                        </div>
                        
                        <?php if ( ! empty( $atts['c2_btn_text'] ) ) : ?>
                            <a href="<?php echo esc_url( $a_href ); ?>" class="btn-cta-kuning"<?php echo $a_target; ?>>
                                <?php echo esc_html( $atts['c2_btn_text'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_permintaan_info_element' );
function ppic_register_ppid_permintaan_info_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_steps = array(
        array( 'text' => 'Isi formulir permintaan informasi (unduh di website atau ambil di kantor)' ),
        array( 'text' => 'Sertakan salinan identitas diri (KTP/SIM/Paspor) yang masih berlaku' ),
        array( 'text' => 'Kirimkan ke petugas PPID melalui email atau datang langsung ke kantor' ),
        array( 'text' => 'Petugas akan memberikan tanda bukti penerimaan permohonan' ),
        array( 'text' => 'Informasi akan diberikan paling lambat 10 hari kerja setelah permohonan diterima' ),
    );

    $dummy_notes = array(
        array( 'text' => 'Permintaan informasi dikenakan biaya sesuai Standar Biaya Layanan (fotokopi, rekam, atau pengiriman)' ),
        array( 'text' => 'Informasi yang dikecualikan (sesuai UU KIP Pasal 17) tidak dapat diberikan' ),
        array( 'text' => 'Jika permintaan ditolak, pemohon berhak mengajukan keberatan' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Layanan - Permintaan Informasi', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_permintaan_info',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-media-document',
            'params'   => array(
                // KARTU 1 (KIRI)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Permintaan Informasi',
                    'group'      => __( 'Kartu 1 (Kiri)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'c1_desc',
                    'value'      => 'Masyarakat dapat mengajukan permintaan informasi publik secara tertulis melalui surat, email, atau mengunjungi langsung kantor PPID PPI Curug. Setiap permintaan akan diproses sesuai ketentuan yang berlaku.',
                    'group'      => __( 'Kartu 1 (Kiri)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Langkah-langkah', 'ppic-custom-element' ),
                    'param_name' => 'c1_steps',
                    'value'      => urlencode( wp_json_encode( $dummy_steps ) ),
                    'group'      => __( 'Kartu 1 (Kiri)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Teks Langkah', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),

                // KARTU 2 (KANAN)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Informasi Penting',
                    'group'      => __( 'Kartu 2 (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Catatan', 'ppic-custom-element' ),
                    'param_name' => 'c2_notes',
                    'value'      => urlencode( wp_json_encode( $dummy_notes ) ),
                    'group'      => __( 'Kartu 2 (Kanan)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Teks Catatan', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar QR Code', 'ppic-custom-element' ),
                    'param_name'  => 'c2_qr',
                    'description' => __( 'Pilih gambar QR Code dari Media Library.', 'ppic-custom-element' ),
                    'group'       => __( 'Kartu 2 (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Bawah QR', 'ppic-custom-element' ),
                    'param_name' => 'c2_qr_text',
                    'value'      => 'Scan untuk akses cepat',
                    'group'      => __( 'Kartu 2 (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Aksi', 'ppic-custom-element' ),
                    'param_name' => 'c2_btn_text',
                    'value'      => 'Ajukan Permintaan Sekarang',
                    'group'      => __( 'Kartu 2 (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tombol (Formulir)', 'ppic-custom-element' ),
                    'param_name' => 'c2_btn_url',
                    'value'      => 'url:https%3A%2F%2Fdocs.google.com%2Fforms|target:_blank',
                    'description'=> __( 'Masukkan URL Form pengajuan permintaan informasi di sini.', 'ppic-custom-element' ),
                    'group'      => __( 'Kartu 2 (Kanan)', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'permintaan',
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