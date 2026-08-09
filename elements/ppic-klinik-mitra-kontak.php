<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_klinik_mitra_kontak', 'ppic_klinik_mitra_kontak_render' );
function ppic_klinik_mitra_kontak_render( $atts ) {
    
    // 1. DATA DUMMY MITRA (Fallback)
    $dummy_mitra = array(
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1RxRmMDvcX7smVrWp0cqFl-CdgqjvOD4r', 'title' => 'BPJS Kesehatan' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1z6wZ7QJ7DOOXVHihI8ndtf7seIcaPiKB', 'title' => 'Inhealth' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1hEvnlv7iZXJVsootAjkLNBDTmifx66Uw', 'title' => 'PT. Taradita Alam Hijau' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1jfjc1Hs50bqBOzODixYDcCcSkmi_V5cn', 'title' => 'PT. Graha Alam Industri' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1BP8Z_ojXfNfbVv5fT2xaleIDc2qtM2Az', 'title' => 'Puskesmas Legok' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1tdBSgVGLaf3282m6yCzFxclInDYI8lNw', 'title' => 'Jejaring Bidan Lyeha' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1V7U8LVTZAVaWjuFp7bbpEkvucHQDMWhU', 'title' => 'Laboratorium Citrama' ),
    );

    $atts = shortcode_atts(
        array(
            // BAGIAN MITRA
            'm_id'       => 'kerjasama',
            'm_title_1'  => 'Mitra',
            'm_title_2'  => 'Kerjasama',
            'm_title_3'  => 'Klinik',
            'm_sub'      => 'Klinik PPI Curug bekerja sama dengan berbagai lembaga untuk memberikan layanan kesehatan yang terpercaya.',
            'm_items'    => urlencode( wp_json_encode( $dummy_mitra ) ),
            
            // BAGIAN KONTAK & CTA
            'c_id'       => 'kontak',
            'c_title'    => 'Hubungi Kami',
            'c_desc'     => 'Untuk informasi lebih lanjut, jadwal praktik, atau membuat janji temu, silakan hubungi kami melalui kontak di bawah ini.',
            'c_phone'    => '+62 851-2478-0866',
            'c_email'    => 'klinik@ppicurug.ac.id',
            'c_ig'       => '@klinik_ppicurug',
            'c_ig_url'   => 'https://instagram.com/klinik_ppicurug',
            
            // BAGIAN JAM OPERASIONAL
            'j_bpjs'     => 'Senin–Jumat, 08.00 – 16.00 WIB',
            'j_taruna'   => 'Pelayanan 24 jam, Setiap Hari',
            
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_class = 'ppic-mitra-kontak-wrapper' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    
    // Parse Mitra
    $mitra_list = vc_param_group_parse_atts( $atts['m_items'] );
    $has_valid_mitra = false;
    if ( is_array( $mitra_list ) ) {
        foreach ( $mitra_list as $m ) {
            if ( ! empty( $m['title'] ) ) { $has_valid_mitra = true; break; }
        }
    }
    if ( ! $has_valid_mitra ) $mitra_list = $dummy_mitra;

    ob_start(); ?>
    
    <div class="<?php echo $wrapper_class; ?>">
        
        <!-- SECTION 1: MITRA KERJASAMA -->
        <section id="<?php echo esc_attr( $atts['m_id'] ); ?>" class="ppic-mitra-section">
            <div class="ppic-mitra-container">
                <div class="ppic-mitra-header">
                    <h2>
                        <?php echo esc_html( $atts['m_title_1'] ); ?> 
                        <span><?php echo esc_html( $atts['m_title_2'] ); ?></span> 
                        <?php echo esc_html( $atts['m_title_3'] ); ?>
                    </h2>
                    <?php if ( ! empty( $atts['m_sub'] ) ) : ?>
                        <p><?php echo esc_html( $atts['m_sub'] ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="ppic-mitra-grid">
                    <?php foreach ( $mitra_list as $item ) : 
                        $title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                        if ( empty( $title ) ) continue;

                        $img_url = isset( $item['fallback_img'] ) ? $item['fallback_img'] : 'https://via.placeholder.com/150x80/f1f5f9/002b49?text=' . urlencode($title);
                        if ( ! empty( $item['image'] ) ) {
                            $up_img = wp_get_attachment_image_url( $item['image'], 'medium' );
                            if ( $up_img ) $img_url = $up_img;
                        }
                    ?>
                        <div class="ppic-mitra-card">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
                            <span><?php echo esc_html( $title ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- SECTION 2: KONTAK (CTA) -->
        <section id="<?php echo esc_attr( $atts['c_id'] ); ?>" class="ppic-kontak-section">
            <div class="ppic-kontak-container">
                
                <h2 class="ppic-kontak-title">
                    <i class="fas fa-phone-alt"></i> <?php echo esc_html( $atts['c_title'] ); ?>
                </h2>
                
                <?php if ( ! empty( $atts['c_desc'] ) ) : ?>
                    <p class="ppic-kontak-desc"><?php echo esc_html( $atts['c_desc'] ); ?></p>
                <?php endif; ?>

                <div class="ppic-kontak-info">
                    <?php if ( ! empty( $atts['c_phone'] ) ) : ?>
                        <div class="info-item"><i class="fas fa-phone"></i> <?php echo esc_html( $atts['c_phone'] ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $atts['c_email'] ) ) : ?>
                        <div class="info-item"><i class="fas fa-envelope"></i> <?php echo esc_html( $atts['c_email'] ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $atts['c_ig'] ) ) : ?>
                        <div class="info-item">
                            <a href="<?php echo esc_url( $atts['c_ig_url'] ); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-instagram"></i> <?php echo esc_html( $atts['c_ig'] ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Jam Operasional Panel -->
                <?php if ( ! empty( $atts['j_bpjs'] ) || ! empty( $atts['j_taruna'] ) ) : ?>
                <div class="ppic-jam-panel">
                    <div class="jam-header">
                        <i class="fas fa-clock"></i> JAM OPERASIONAL
                    </div>
                    <div class="jam-grid">
                        <?php if ( ! empty( $atts['j_bpjs'] ) ) : ?>
                            <div class="jam-label">BPJS</div>
                            <div class="jam-val"><?php echo esc_html( $atts['j_bpjs'] ); ?></div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $atts['j_taruna'] ) ) : ?>
                            <div class="jam-label">Taruna/i</div>
                            <div class="jam-val"><?php echo esc_html( $atts['j_taruna'] ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </section>

    </div>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_klinik_mitra_kontak' );
function ppic_register_klinik_mitra_kontak() {
    if ( ! function_exists( 'vc_map' ) ) { return; }

    $dummy_mitra = array(
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1RxRmMDvcX7smVrWp0cqFl-CdgqjvOD4r', 'title' => 'BPJS Kesehatan' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1z6wZ7QJ7DOOXVHihI8ndtf7seIcaPiKB', 'title' => 'Inhealth' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1hEvnlv7iZXJVsootAjkLNBDTmifx66Uw', 'title' => 'PT. Taradita Alam Hijau' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1jfjc1Hs50bqBOzODixYDcCcSkmi_V5cn', 'title' => 'PT. Graha Alam Industri' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1BP8Z_ojXfNfbVv5fT2xaleIDc2qtM2Az', 'title' => 'Puskesmas Legok' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1tdBSgVGLaf3282m6yCzFxclInDYI8lNw', 'title' => 'Jejaring Bidan Lyeha' ),
        array( 'fallback_img' => 'https://lh3.googleusercontent.com/d/1V7U8LVTZAVaWjuFp7bbpEkvucHQDMWhU', 'title' => 'Laboratorium Citrama' ),
    );

    vc_map( array(
        'name'     => __( 'PPIC Klinik Mitra & Kontak', 'ppic-custom-element' ),
        'base'     => 'ppic_klinik_mitra_kontak',
        'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
        'icon'     => 'dashicons dashicons-networking',
        'params'   => array(
            // TAB MITRA
            array('type' => 'textfield', 'heading' => 'Section ID Mitra', 'param_name' => 'm_id', 'value' => 'kerjasama', 'group' => 'Mitra'),
            array('type' => 'textfield', 'heading' => 'Judul Awal', 'param_name' => 'm_title_1', 'value' => 'Mitra', 'group' => 'Mitra'),
            array('type' => 'textfield', 'heading' => 'Judul Tengah (Kuning)', 'param_name' => 'm_title_2', 'value' => 'Kerjasama', 'group' => 'Mitra'),
            array('type' => 'textfield', 'heading' => 'Judul Akhir', 'param_name' => 'm_title_3', 'value' => 'Klinik', 'group' => 'Mitra'),
            array('type' => 'textarea', 'heading' => 'Subtitle', 'param_name' => 'm_sub', 'value' => 'Klinik PPI Curug bekerja sama dengan berbagai lembaga untuk memberikan layanan kesehatan yang terpercaya.', 'group' => 'Mitra'),
            array(
                'type' => 'param_group', 'heading' => 'Daftar Mitra', 'param_name' => 'm_items', 'value' => urlencode(wp_json_encode($dummy_mitra)), 'group' => 'Mitra',
                'params' => array(
                    array('type' => 'attach_image', 'heading' => 'Logo Mitra', 'param_name' => 'image'),
                    array('type' => 'textfield', 'heading' => 'Fallback URL (Abaikan jika upload logo)', 'param_name' => 'fallback_img'),
                    array('type' => 'textfield', 'heading' => 'Nama Mitra', 'param_name' => 'title', 'admin_label' => true),
                )
            ),
            
            // TAB KONTAK
            array('type' => 'textfield', 'heading' => 'Section ID Kontak', 'param_name' => 'c_id', 'value' => 'kontak', 'group' => 'Kontak & CTA'),
            array('type' => 'textfield', 'heading' => 'Judul Utama', 'param_name' => 'c_title', 'value' => 'Hubungi Kami', 'group' => 'Kontak & CTA'),
            array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'c_desc', 'value' => 'Untuk informasi lebih lanjut, jadwal praktik, atau membuat janji temu, silakan hubungi kami melalui kontak di bawah ini.', 'group' => 'Kontak & CTA'),
            array('type' => 'textfield', 'heading' => 'No HP / WhatsApp', 'param_name' => 'c_phone', 'value' => '+62 851-2478-0866', 'group' => 'Kontak & CTA'),
            array('type' => 'textfield', 'heading' => 'Email', 'param_name' => 'c_email', 'value' => 'klinik@ppicurug.ac.id', 'group' => 'Kontak & CTA'),
            array('type' => 'textfield', 'heading' => 'Teks Instagram', 'param_name' => 'c_ig', 'value' => '@klinik_ppicurug', 'group' => 'Kontak & CTA'),
            array('type' => 'textfield', 'heading' => 'URL Instagram', 'param_name' => 'c_ig_url', 'value' => 'https://instagram.com/klinik_ppicurug', 'group' => 'Kontak & CTA'),
            
            // TAB JADWAL
            array('type' => 'textfield', 'heading' => 'Jadwal BPJS', 'param_name' => 'j_bpjs', 'value' => 'Senin–Jumat, 08.00 – 16.00 WIB', 'group' => 'Jam Operasional'),
            array('type' => 'textfield', 'heading' => 'Jadwal Taruna', 'param_name' => 'j_taruna', 'value' => 'Pelayanan 24 jam, Setiap Hari', 'group' => 'Jam Operasional'),
        )
    ));
}