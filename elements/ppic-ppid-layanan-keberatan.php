<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_layanan_keberatan', 'ppic_ppid_layanan_keberatan_render' );
function ppic_ppid_layanan_keberatan_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Prosedur Pengajuan Keberatan',
            'desc'        => 'Apabila permintaan informasi ditolak, tidak dipenuhi, atau tidak sesuai ketentuan, pemohon dapat mengajukan keberatan',
            
            // GAMBAR KIRI
            'img_diagram' => '',
            
            // KARTU 1 (KANAN ATAS)
            'c1_title'    => 'Mekanisme Keberatan',
            'c1_steps'    => '',
            
            // KARTU 2 (KANAN BAWAH)
            'c2_title'    => 'Persyaratan Keberatan',
            'c2_bullets'  => '',
            'c2_btn_text' => 'Formulir Keberatan',
            'c2_btn_url'  => 'url:%23|target:_blank',
            
            // UMUM
            'el_id'       => 'keberatan',
            'el_class'    => '',
        ),
        $atts
    );

    // Default Fallback Data untuk Mekanisme (Angka)
    $dummy_steps = array(
        array( 'text' => 'Pemohon mengajukan keberatan secara tertulis kepada Atasan PPID PPI Curug' ),
        array( 'text' => 'Keberatan diajukan paling lambat <strong>30 hari kerja</strong> setelah menerima tanggapan tertulis dari PPID' ),
        array( 'text' => 'Keberatan harus mencantumkan alasan yang jelas dan melampirkan bukti permohonan sebelumnya' ),
        array( 'text' => 'Atasan PPID wajib memberikan tanggapan tertulis paling lambat <strong>30 hari kerja</strong> sejak keberatan diterima' ),
    );

    // Default Fallback Data untuk Persyaratan (Bullet)
    $dummy_bullets = array(
        array( 'text' => 'Surat keberatan yang ditujukan kepada Atasan PPID' ),
        array( 'text' => 'Fotokopi identitas diri yang masih berlaku' ),
        array( 'text' => 'Salinan surat tanggapan dari PPID atas permohonan informasi yang diajukan' ),
        array( 'text' => 'Kronologi lengkap kejadian dan bukti pendukung lainnya' ),
    );

    // Parsing Param Groups
    $steps_data   = vc_param_group_parse_atts( $atts['c1_steps'] );
    $bullets_data = vc_param_group_parse_atts( $atts['c2_bullets'] );

    if ( empty( $steps_data ) || ! is_array( $steps_data ) ) $steps_data = $dummy_steps;
    if ( empty( $bullets_data ) || ! is_array( $bullets_data ) ) $bullets_data = $dummy_bullets;

    // Parsing URL Tombol Formulir
    $link_btn = ( '||' !== $atts['c2_btn_url'] ) ? vc_build_link( $atts['c2_btn_url'] ) : '';
    $a_href   = ! empty( $link_btn['url'] ) ? $link_btn['url'] : '#';
    $a_target = ! empty( $link_btn['target'] ) ? ' target="' . esc_attr( trim( $link_btn['target'] ) ) . '"' : '';

    // Parsing Gambar Kiri (Dengan Fallback)
    $img_url = 'https://lh3.googleusercontent.com/d/1yqv_2j8rFK-UJ86LokHAXcrbxSW75YXf';
    if ( ! empty( $atts['img_diagram'] ) ) {
        $img_src = wp_get_attachment_image_url( $atts['img_diagram'], 'full' );
        if ( $img_src ) $img_url = $img_src;
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-keberatan-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-keberatan-container">
            
            <div class="ppic-keberatan-header">
                <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <div class="title-accent"></div>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="keberatan-grid">
                
                <div class="keberatan-image-col">
                    <div class="keberatan-image-wrapper">
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="Diagram Prosedur Keberatan" loading="lazy" />
                    </div>
                </div>

                <div class="keberatan-stack-col">
                    
                    <div class="keberatan-card">
                        <div class="keberatan-icon">
                            <i class="fas fa-scale-balanced"></i>
                        </div>
                        <h3 class="keberatan-card-title"><?php echo esc_html( $atts['c1_title'] ); ?></h3>
                        
                        <ul class="keberatan-steps">
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

                    <div class="keberatan-card">
                        <div class="keberatan-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h3 class="keberatan-card-title"><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                        
                        <ul class="keberatan-bullets">
                            <?php foreach ( $bullets_data as $bullet ) : 
                                $text = isset( $bullet['text'] ) ? $bullet['text'] : '';
                                if( empty($text) ) continue;
                            ?>
                                <li><?php echo wp_kses_post( $text ); ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if ( ! empty( $atts['c2_btn_text'] ) ) : ?>
                            <div class="keberatan-btn-wrapper">
                                <a href="<?php echo esc_url( $a_href ); ?>" class="btn-cta-kuning"<?php echo $a_target; ?>>
                                    <?php echo esc_html( $atts['c2_btn_text'] ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_layanan_keberatan_element' );
function ppic_register_ppid_layanan_keberatan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_steps = array(
        array( 'text' => 'Pemohon mengajukan keberatan secara tertulis kepada Atasan PPID PPI Curug' ),
        array( 'text' => 'Keberatan diajukan paling lambat <strong>30 hari kerja</strong> setelah menerima tanggapan tertulis dari PPID' ),
        array( 'text' => 'Keberatan harus mencantumkan alasan yang jelas dan melampirkan bukti permohonan sebelumnya' ),
        array( 'text' => 'Atasan PPID wajib memberikan tanggapan tertulis paling lambat <strong>30 hari kerja</strong> sejak keberatan diterima' ),
    );

    $dummy_bullets = array(
        array( 'text' => 'Surat keberatan yang ditujukan kepada Atasan PPID' ),
        array( 'text' => 'Fotokopi identitas diri yang masih berlaku' ),
        array( 'text' => 'Salinan surat tanggapan dari PPID atas permohonan informasi yang diajukan' ),
        array( 'text' => 'Kronologi lengkap kejadian dan bukti pendukung lainnya' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Layanan - Prosedur Keberatan', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_layanan_keberatan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-hammer',
            'params'   => array(
                // HEADER TEKS
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Prosedur Pengajuan Keberatan',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi/Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Apabila permintaan informasi ditolak, tidak dipenuhi, atau tidak sesuai ketentuan, pemohon dapat mengajukan keberatan',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),

                // GAMBAR DIAGRAM KIRI
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Diagram Prosedur', 'ppic-custom-element' ),
                    'param_name'  => 'img_diagram',
                    'description' => __( 'Kosongkan jika ingin menggunakan gambar default.', 'ppic-custom-element' ),
                    'group'       => __( 'Gambar Kiri', 'ppic-custom-element' ),
                ),

                // KARTU 1 (MEKANISME)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Mekanisme Keberatan',
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

                // KARTU 2 (PERSYARATAN)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Persyaratan Keberatan',
                    'group'      => __( 'Kartu 2 (Persyaratan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Poin Persyaratan', 'ppic-custom-element' ),
                    'param_name' => 'c2_bullets',
                    'value'      => urlencode( wp_json_encode( $dummy_bullets ) ),
                    'group'      => __( 'Kartu 2 (Persyaratan)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Teks Poin', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Aksi', 'ppic-custom-element' ),
                    'param_name' => 'c2_btn_text',
                    'value'      => 'Formulir Keberatan',
                    'group'      => __( 'Kartu 2 (Persyaratan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'c2_btn_url',
                    'value'      => 'url:%23|target:_blank',
                    'group'      => __( 'Kartu 2 (Persyaratan)', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'keberatan',
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