<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_alur_permintaan', 'ppic_ppid_alur_permintaan_render' );
function ppic_ppid_alur_permintaan_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Alur Permintaan Informasi',
            'desc'        => 'Proses permintaan informasi publik yang disederhanakan untuk kemudahan akses masyarakat',
            
            // KARTU 1 (KIRI)
            'c1_title'    => 'Tahapan Alur Permintaan Informasi',
            'c1_steps'    => '',
            
            // KARTU 2 (KANAN)
            'c2_title'    => 'Waktu Penyelesaian',
            'c2_bullets'  => '',
            
            // GAMBAR DIAGRAM BAWAH
            'alur_image'  => '',
            
            // UMUM
            'el_id'       => 'alur',
            'el_class'    => '',
        ),
        $atts
    );

    // Default Fallback Data untuk Langkah-langkah
    $dummy_steps = array(
        array( 'text' => 'Pemohon mengajukan permintaan informasi melalui formulir (online/offline)' ),
        array( 'text' => 'Petugas PPID melakukan verifikasi kelengkapan berkas' ),
        array( 'text' => 'Pemohon menerima bukti penerimaan permohonan (berisi nomor registrasi)' ),
        array( 'text' => 'PPID melakukan pencarian dan pengumpulan informasi yang diminta' ),
        array( 'text' => 'Apabila diperlukan biaya tambahan, pemohon diberitahukan terlebih dahulu' ),
        array( 'text' => 'Informasi disampaikan kepada pemohon (maksimal 10 hari kerja)' ),
    );

    // Default Fallback Data untuk Bullet Points
    $dummy_bullets = array(
        array( 'text' => 'Permintaan informasi sederhana: <strong>10 hari kerja</strong>' ),
        array( 'text' => 'Permintaan informasi kompleks: <strong>7 hari kerja tambahan</strong> (dengan pemberitahuan tertulis)' ),
        array( 'text' => 'Informasi yang dikecualikan: <strong>diberitahukan secara tertulis disertai alasan</strong>' ),
    );

    // Parsing Param Groups
    $steps_data   = vc_param_group_parse_atts( $atts['c1_steps'] );
    $bullets_data = vc_param_group_parse_atts( $atts['c2_bullets'] );

    if ( empty( $steps_data ) || ! is_array( $steps_data ) ) $steps_data = $dummy_steps;
    if ( empty( $bullets_data ) || ! is_array( $bullets_data ) ) $bullets_data = $dummy_bullets;

    // Parsing Gambar Bawah (Fallback langsung ke link lh3 Google Drive)
    $img_url = 'https://lh3.googleusercontent.com/d/1xBN8PPaNtixi-9c94r986lYghLd-eWd5';
    if ( ! empty( $atts['alur_image'] ) ) {
        $img_src = wp_get_attachment_image_url( $atts['alur_image'], 'full' );
        if ( $img_src ) $img_url = $img_src;
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-alur-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-alur-container">
            
            <div class="ppic-alur-header">
                <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <div class="title-accent"></div>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="alur-grid">
                
                <div class="alur-card">
                    <div class="alur-icon icon-transparent">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="alur-title"><?php echo esc_html( $atts['c1_title'] ); ?></h3>
                    
                    <ul class="alur-steps">
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

                <div class="alur-card">
                    <div class="alur-icon icon-solid">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="alur-title"><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                    
                    <ul class="alur-bullets">
                        <?php foreach ( $bullets_data as $bullet ) : 
                            $text = isset( $bullet['text'] ) ? $bullet['text'] : '';
                            if( empty($text) ) continue;
                        ?>
                            <li><?php echo wp_kses_post( $text ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>

            <div class="alur-image-wrapper">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="Diagram Alur Permintaan Informasi" loading="lazy" />
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_alur_permintaan_element' );
function ppic_register_ppid_alur_permintaan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_steps = array(
        array( 'text' => 'Pemohon mengajukan permintaan informasi melalui formulir (online/offline)' ),
        array( 'text' => 'Petugas PPID melakukan verifikasi kelengkapan berkas' ),
        array( 'text' => 'Pemohon menerima bukti penerimaan permohonan (berisi nomor registrasi)' ),
        array( 'text' => 'PPID melakukan pencarian dan pengumpulan informasi yang diminta' ),
        array( 'text' => 'Apabila diperlukan biaya tambahan, pemohon diberitahukan terlebih dahulu' ),
        array( 'text' => 'Informasi disampaikan kepada pemohon (maksimal 10 hari kerja)' ),
    );

    $dummy_bullets = array(
        array( 'text' => 'Permintaan informasi sederhana: <strong>10 hari kerja</strong>' ),
        array( 'text' => 'Permintaan informasi kompleks: <strong>7 hari kerja tambahan</strong> (dengan pemberitahuan tertulis)' ),
        array( 'text' => 'Informasi yang dikecualikan: <strong>diberitahukan secara tertulis disertai alasan</strong>' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Layanan - Alur Permintaan', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_alur_permintaan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-networking',
            'params'   => array(
                // HEADER TEKS
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Alur Permintaan Informasi',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi/Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Proses permintaan informasi publik yang disederhanakan untuk kemudahan akses masyarakat',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),

                // KARTU 1
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Tahapan Alur Permintaan Informasi',
                    'group'      => __( 'Kartu 1 (Tahapan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Langkah-langkah', 'ppic-custom-element' ),
                    'param_name' => 'c1_steps',
                    'value'      => urlencode( wp_json_encode( $dummy_steps ) ),
                    'group'      => __( 'Kartu 1 (Tahapan)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Teks Langkah', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),

                // KARTU 2
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Waktu Penyelesaian',
                    'group'      => __( 'Kartu 2 (Waktu)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Poin Waktu', 'ppic-custom-element' ),
                    'param_name' => 'c2_bullets',
                    'value'      => urlencode( wp_json_encode( $dummy_bullets ) ),
                    'description'=> __( 'Anda dapat menggunakan tag &lt;strong&gt; untuk menebalkan teks.', 'ppic-custom-element' ),
                    'group'      => __( 'Kartu 2 (Waktu)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Teks Poin', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),

                // GAMBAR DIAGRAM BAWAH
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Bagan Alur', 'ppic-custom-element' ),
                    'param_name'  => 'alur_image',
                    'description' => __( 'Kosongkan jika ingin menggunakan gambar default (dari Google Drive).', 'ppic-custom-element' ),
                    'group'       => __( 'Gambar Bawah', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'alur',
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