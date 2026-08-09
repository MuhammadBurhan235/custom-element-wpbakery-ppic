<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_sertifikasi', 'ppic_prodi_sertifikasi_render' );
function ppic_prodi_sertifikasi_render( $atts ) {
    
    // 1. PINDAHKAN DATA DUMMY KE SINI SEBAGAI FALLBACK FRONTEND
    $dummy_jenis = array(
        array( 'text' => '<strong>Ijazah Diploma III</strong> — diakui oleh Kementerian Pendidikan Tinggi, Sains, dan Teknologi' ),
        array( 'text' => '<strong>Sertifikat Kompetensi (Serkom)</strong> — diakui oleh Kementerian Perhubungan RI' ),
        array( 'text' => '<strong>Lisensi Personel Bandar Udara</strong> — sesuai regulasi Permenhub No. 37 Tahun 2021' ),
    );

    $atts = shortcode_atts(
        array(
            'section_id'     => 'sertifikasi',
            'title'          => 'Sertifikasi',
            'title_icon'     => 'fas fa-certificate',
            'subtitle'       => 'Lulusan TMB tidak hanya memperoleh ijazah akademik, tetapi juga sertifikat kompetensi yang diakui oleh Kementerian Perhubungan sebagai persyaratan personel bandar udara.',
            
            // KOLOM KIRI (Jenis Sertifikasi)
            'jenis_title'    => 'Jenis Sertifikasi',
            'jenis_items'    => urlencode( wp_json_encode( $dummy_jenis ) ), // 2. SET DEFAULT VALUE
            'jenis_note'     => 'Sertifikat kompetensi menjadi <strong>syarat wajib</strong> bagi personel bandar udara di Indonesia.',
            
            // KOLOM KANAN (Gambar Sertifikat)
            'cert_image'     => '',
            'cert_caption'   => 'Sertifikat Akreditasi UNGGUL – LAM-TEKNIK',
            'cert_note'      => 'Program Studi Teknik Mekanikal Bandar Udara, pada Program Diploma Tiga Politeknik Penerbangan Indonesia Curug, Tangerang <strong>Terakreditasi UNGGUL</strong>',
            
            // BAGIAN BAWAH (Approval / Persetujuan)
            'approval_title' => 'Persetujuan',
            'app_image'      => '',
            'app_desc'       => '<strong>Program Studi Teknik Mekanikal Bandar Udara</strong> telah mendapatkan persetujuan dari Direktorat Jenderal Perhubungan Udara sebagai program studi yang memenuhi standar kompetensi personel bandar udara sesuai dengan regulasi yang berlaku.',
            'app_no'         => 'DJUA.123/2025',
            'app_date'       => '31 Desember 2027',
            
            'el_class'       => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-sertif-section section-block alt' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse Jenis Sertifikasi
    $jenis_items = vc_param_group_parse_atts( $atts['jenis_items'] );
    
    // 3. LOGIKA FALLBACK JIKA KOSONG
    $has_valid_item = false;
    if ( is_array( $jenis_items ) ) {
        foreach ( $jenis_items as $item ) {
            if ( ! empty( $item['text'] ) ) {
                $has_valid_item = true;
                break;
            }
        }
    }
    if ( ! $has_valid_item ) {
        $jenis_items = $dummy_jenis;
    }

    // Gambar Sertifikat Utama
    $cert_img_url = 'https://lh3.googleusercontent.com/d/1Tod-AK35VZOa3WZ6lwOsGmukFQNX1Dgf'; // Fallback
    if ( ! empty( $atts['cert_image'] ) ) {
        $img = wp_get_attachment_image_url( $atts['cert_image'], 'large' );
        if ( $img ) $cert_img_url = $img;
    }

    // Gambar Persetujuan
    $app_img_url = 'https://lh3.googleusercontent.com/d/1Uur5cNBBw6Z-XiZ5hGqiyZ8Yz6GQ8ae7'; // Fallback
    if ( ! empty( $atts['app_image'] ) ) {
        $img2 = wp_get_attachment_image_url( $atts['app_image'], 'medium' );
        if ( $img2 ) $app_img_url = $img2;
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-sertif-container">
            
            <h2 class="ppic-section-title">
                <?php if ( ! empty( $atts['title_icon'] ) ) : ?>
                    <i class="<?php echo esc_attr( $atts['title_icon'] ); ?>"></i> 
                <?php endif; ?>
                <?php echo esc_html( $atts['title'] ); ?>
            </h2>
            
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-section-sub">
                    <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                </p>
            <?php endif; ?>

            <div class="ppic-sertif-grid">
                
                <!-- KOLOM KIRI: Daftar Jenis Sertifikasi -->
                <div class="ppic-sertif-list-card">
                    <h4>
                        <i class="fas fa-award"></i> 
                        <?php echo esc_html( $atts['jenis_title'] ); ?>
                    </h4>
                    
                    <?php if ( ! empty( $jenis_items ) ) : ?>
                        <ul class="ppic-sertif-list">
                            <?php foreach ( $jenis_items as $item ) : 
                                $text = isset( $item['text'] ) ? trim( $item['text'] ) : '';
                                if ( empty( $text ) ) continue;
                            ?>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <div><?php echo wp_kses_post( $text ); ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ( ! empty( $atts['jenis_note'] ) ) : ?>
                        <div class="ppic-sertif-note-box">
                            <p>
                                <i class="fas fa-info-circle"></i>
                                <?php echo wp_kses_post( $atts['jenis_note'] ); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- KOLOM KANAN: Gambar Sertifikat -->
                <div class="ppic-sertif-image-card">
                    <img src="<?php echo esc_url( $cert_img_url ); ?>" alt="Sertifikat Dokumen" class="ppic-cert-img" loading="lazy" />
                    
                    <?php if ( ! empty( $atts['cert_caption'] ) ) : ?>
                        <div class="ppic-cert-caption">
                            <i class="fas fa-certificate"></i> <?php echo esc_html( $atts['cert_caption'] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $atts['cert_note'] ) ) : ?>
                        <div class="ppic-cert-img-note">
                            <p>
                                <i class="fas fa-check-circle"></i>
                                <?php echo wp_kses_post( $atts['cert_note'] ); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- BAGIAN BAWAH: Persetujuan / Approval -->
            <div class="ppic-approval-card">
                <h4><i class="fas fa-check-double"></i> <?php echo esc_html( $atts['approval_title'] ); ?></h4>
                <div class="ppic-approval-grid">
                    <div class="ppic-approval-img-wrap">
                        <img src="<?php echo esc_url( $app_img_url ); ?>" alt="Persetujuan Dokumen" loading="lazy" />
                    </div>
                    <div class="ppic-approval-text">
                        <p class="app-desc"><?php echo wp_kses_post( $atts['app_desc'] ); ?></p>
                        
                        <?php if ( ! empty( $atts['app_no'] ) ) : ?>
                            <p class="app-meta">
                                <i class="fas fa-file-signature"></i>
                                Nomor Persetujuan: <strong><?php echo esc_html( $atts['app_no'] ); ?></strong>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $atts['app_date'] ) ) : ?>
                            <p class="app-meta">
                                <i class="fas fa-calendar-alt"></i>
                                Berlaku hingga: <strong><?php echo esc_html( $atts['app_date'] ); ?></strong>
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

add_action( 'vc_before_init', 'ppic_register_prodi_sertifikasi_element' );
function ppic_register_prodi_sertifikasi_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_jenis = array(
        array( 'text' => '<strong>Ijazah Diploma III</strong> — diakui oleh Kementerian Pendidikan Tinggi, Sains, dan Teknologi' ),
        array( 'text' => '<strong>Sertifikat Kompetensi (Serkom)</strong> — diakui oleh Kementerian Perhubungan RI' ),
        array( 'text' => '<strong>Lisensi Personel Bandar Udara</strong> — sesuai regulasi Permenhub No. 37 Tahun 2021' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Sertifikasi', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_sertifikasi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-media-document',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'sertifikasi',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Sertifikasi',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-certificate',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Lulusan TMB tidak hanya memperoleh ijazah akademik, tetapi juga sertifikat kompetensi yang diakui oleh Kementerian Perhubungan sebagai persyaratan personel bandar udara.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),

                // JENIS SERTIFIKASI (KIRI)
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Kolom Kiri', 'ppic-custom-element' ),
                    'param_name'  => 'jenis_title',
                    'value'       => 'Jenis Sertifikasi',
                    'group'       => __( 'Daftar Sertifikasi (Kiri)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Jenis Sertifikasi', 'ppic-custom-element' ),
                    'param_name'  => 'jenis_items',
                    'value'       => urlencode( wp_json_encode( $dummy_jenis ) ),
                    'group'       => __( 'Daftar Sertifikasi (Kiri)', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textarea', 'heading' => 'Teks Info', 'param_name' => 'text'),
                    ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Kotak Catatan (Bawah List)', 'ppic-custom-element' ),
                    'param_name'  => 'jenis_note',
                    'value'       => 'Sertifikat kompetensi menjadi <strong>syarat wajib</strong> bagi personel bandar udara di Indonesia.',
                    'group'       => __( 'Daftar Sertifikasi (Kiri)', 'ppic-custom-element' ),
                ),

                // GAMBAR SERTIFIKAT (KANAN)
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Sertifikat Utama', 'ppic-custom-element' ),
                    'param_name'  => 'cert_image',
                    'group'       => __( 'Gambar Sertifikat (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Caption Gambar', 'ppic-custom-element' ),
                    'param_name'  => 'cert_caption',
                    'value'       => 'Sertifikat Akreditasi UNGGUL – LAM-TEKNIK',
                    'group'       => __( 'Gambar Sertifikat (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Kotak Catatan (Bawah Gambar)', 'ppic-custom-element' ),
                    'param_name'  => 'cert_note',
                    'value'       => 'Program Studi Teknik Mekanikal Bandar Udara, pada Program Diploma Tiga Politeknik Penerbangan Indonesia Curug, Tangerang <strong>Terakreditasi UNGGUL</strong>',
                    'group'       => __( 'Gambar Sertifikat (Kanan)', 'ppic-custom-element' ),
                ),

                // APPROVAL (BAWAH)
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Bagian', 'ppic-custom-element' ),
                    'param_name'  => 'approval_title',
                    'value'       => 'Persetujuan',
                    'group'       => __( 'Approval (Bawah)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar / Logo Approval', 'ppic-custom-element' ),
                    'param_name'  => 'app_image',
                    'group'       => __( 'Approval (Bawah)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Deskripsi Approval', 'ppic-custom-element' ),
                    'param_name'  => 'app_desc',
                    'value'       => '<strong>Program Studi Teknik Mekanikal Bandar Udara</strong> telah mendapatkan persetujuan dari Direktorat Jenderal Perhubungan Udara sebagai program studi yang memenuhi standar kompetensi personel bandar udara sesuai dengan regulasi yang berlaku.',
                    'group'       => __( 'Approval (Bawah)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Nomor Persetujuan', 'ppic-custom-element' ),
                    'param_name'  => 'app_no',
                    'value'       => 'DJUA.123/2025',
                    'group'       => __( 'Approval (Bawah)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Tanggal Berlaku', 'ppic-custom-element' ),
                    'param_name'  => 'app_date',
                    'value'       => '31 Desember 2027',
                    'group'       => __( 'Approval (Bawah)', 'ppic-custom-element' ),
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