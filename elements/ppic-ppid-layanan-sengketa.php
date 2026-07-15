<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_layanan_sengketa', 'ppic_ppid_layanan_sengketa_render' );
function ppic_ppid_layanan_sengketa_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Prosedur Pengajuan Sengketa',
            'desc'        => 'Jika keberatan tidak ditanggapi atau ditolak, pemohon dapat mengajukan penyelesaian sengketa ke Komisi Informasi',
            
            // GAMBAR KIRI
            'img_diagram' => '',
            
            // KARTU 1 (KANAN ATAS)
            'c1_title'    => 'Mekanisme Sengketa',
            'c1_steps'    => '',
            
            // KARTU 2 (KANAN BAWAH)
            'c2_title'    => 'Komisi Informasi Provinsi Banten',
            'c2_address'  => "<strong>Alamat:</strong><br />Jl. Syekh Nawawi Al-Bantani Blok F Nomor 1<br />Dinas Perhubungan, Komunikasi dan Informatika Lantai II<br />Kawasan Pusat Pemerintahan Provinsi Banten (KP3B), Curug, Serang, Banten",
            
            // UMUM
            'el_id'       => 'sengketa',
            'el_class'    => '',
        ),
        $atts
    );

    // Default Fallback Data untuk Mekanisme Sengketa (Angka)
    $dummy_steps = array(
        array( 'text' => 'Pemohon mengajukan permohonan sengketa ke <strong>Komisi Informasi Provinsi Banten</strong>' ),
        array( 'text' => 'Sengketa diajukan paling lambat <strong>14 hari kerja</strong> setelah menerima tanggapan dari Atasan PPID' ),
        array( 'text' => 'Permohonan diajukan secara tertulis dengan melampirkan bukti keberatan yang telah diajukan' ),
        array( 'text' => 'Komisi Informasi akan memproses melalui jalur mediasi atau ajudikasi nonlitigasi' ),
        array( 'text' => 'Proses penyelesaian sengketa paling lambat <strong>100 hari kerja</strong> sejak permohonan diterima' ),
    );

    // Parsing Param Groups
    $steps_data = vc_param_group_parse_atts( $atts['c1_steps'] );
    if ( empty( $steps_data ) || ! is_array( $steps_data ) ) {
        $steps_data = $dummy_steps;
    }

    // Parsing Gambar Kiri (Dengan Fallback Google Drive)
    $img_url = 'https://lh3.googleusercontent.com/d/1VNi3JGrJ2-2Dvm6psoRZJboajMkEVyNP';
    if ( ! empty( $atts['img_diagram'] ) ) {
        $img_src = wp_get_attachment_image_url( $atts['img_diagram'], 'full' );
        if ( $img_src ) $img_url = $img_src;
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-sengketa-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-sengketa-container">
            
            <!-- Header Section -->
            <div class="ppic-sengketa-header">
                <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <div class="title-accent"></div>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <!-- Grid 2 Kolom -->
            <div class="sengketa-grid">
                
                <!-- KOLOM KIRI: Gambar Diagram -->
                <div class="sengketa-image-col">
                    <div class="sengketa-image-wrapper">
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="Diagram Prosedur Sengketa" loading="lazy" />
                    </div>
                </div>

                <!-- KOLOM KANAN: Stack Kartu -->
                <div class="sengketa-stack-col">
                    
                    <!-- Kartu 1: Mekanisme Sengketa -->
                    <div class="sengketa-card">
                        <div class="sengketa-icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <h3 class="sengketa-card-title"><?php echo esc_html( $atts['c1_title'] ); ?></h3>
                        
                        <ul class="sengketa-steps">
                            <?php $no = 1; foreach ( $steps_data as $step ) : 
                                $text = isset( $step['text'] ) ? $step['text'] : '';
                                if( empty($text) ) continue;
                            ?>
                                <li>
                                    <span class="step-number"><?php echo $no++; ?></span>
                                    <span class="step-text"><?php echo wp_kses_post( $text ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Kartu 2: Info Komisi Informasi -->
                    <div class="sengketa-card">
                        <div class="sengketa-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="sengketa-card-title"><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                        
                        <?php if ( ! empty( $atts['c2_address'] ) ) : ?>
                            <p class="sengketa-address">
                                <?php echo wp_kses_post( $atts['c2_address'] ); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_layanan_sengketa_element' );
function ppic_register_ppid_layanan_sengketa_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_steps = array(
        array( 'text' => 'Pemohon mengajukan permohonan sengketa ke <strong>Komisi Informasi Provinsi Banten</strong>' ),
        array( 'text' => 'Sengketa diajukan paling lambat <strong>14 hari kerja</strong> setelah menerima tanggapan dari Atasan PPID' ),
        array( 'text' => 'Permohonan diajukan secara tertulis dengan melampirkan bukti keberatan yang telah diajukan' ),
        array( 'text' => 'Komisi Informasi akan memproses melalui jalur mediasi atau ajudikasi nonlitigasi' ),
        array( 'text' => 'Proses penyelesaian sengketa paling lambat <strong>100 hari kerja</strong> sejak permohonan diterima' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Layanan - Prosedur Sengketa', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_layanan_sengketa',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-megaphone',
            'params'   => array(
                // HEADER TEKS
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Prosedur Pengajuan Sengketa',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi/Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Jika keberatan tidak ditanggapi atau ditolak, pemohon dapat mengajukan penyelesaian sengketa ke Komisi Informasi',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),

                // GAMBAR DIAGRAM KIRI
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Diagram Sengketa', 'ppic-custom-element' ),
                    'param_name'  => 'img_diagram',
                    'description' => __( 'Kosongkan jika ingin menggunakan gambar default.', 'ppic-custom-element' ),
                    'group'       => __( 'Gambar Kiri', 'ppic-custom-element' ),
                ),

                // KARTU 1 (MEKANISME)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Mekanisme Sengketa',
                    'group'      => __( 'Kartu 1 (Mekanisme)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Langkah (Mekanisme)', 'ppic-custom-element' ),
                    'param_name' => 'c1_steps',
                    'value'      => urlencode( wp_json_encode( $dummy_steps ) ),
                    'description'=> __( 'Gunakan tag &lt;strong&gt; untuk teks tebal.', 'ppic-custom-element' ),
                    'group'      => __( 'Kartu 1 (Mekanisme)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Teks Langkah', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),

                // KARTU 2 (ALAMAT)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Komisi Informasi Provinsi Banten',
                    'group'      => __( 'Kartu 2 (Alamat)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea_html',
                    'heading'    => __( 'Detail Alamat', 'ppic-custom-element' ),
                    'param_name' => 'c2_address',
                    'value'      => "<strong>Alamat:</strong><br />Jl. Syekh Nawawi Al-Bantani Blok F Nomor 1<br />Dinas Perhubungan, Komunikasi dan Informatika Lantai II<br />Kawasan Pusat Pemerintahan Provinsi Banten (KP3B), Curug, Serang, Banten",
                    'description'=> __( 'Gunakan tag HTML dasar seperti &lt;br&gt; untuk enter dan &lt;strong&gt; untuk tebal.', 'ppic-custom-element' ),
                    'group'      => __( 'Kartu 2 (Alamat)', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'sengketa',
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