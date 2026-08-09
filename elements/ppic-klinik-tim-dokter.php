<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_klinik_tim_dokter', 'ppic_klinik_tim_dokter_render' );
function ppic_klinik_tim_dokter_render( $atts ) {
    
    // 1. DATA DUMMY SEBAGAI FALLBACK
    $dummy_doctors = array(
        array( 
            'fallback_img' => 'https://placehold.co/300x300/003153/ffffff?text=dr+Herry',
            'name'         => 'dr. Herry Sumarsono', 
            'specialty'    => 'Dokter Umum', 
            'schedule'     => 'Rabu Pagi' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/300x300/1a4a76/ffffff?text=dr+Dwi',
            'name'         => 'dr. Dwi Utari', 
            'specialty'    => 'Dokter Umum', 
            'schedule'     => 'Senin Siang, Kamis Pagi' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/300x300/0b1a2f/ffffff?text=dr+Paulina',
            'name'         => 'dr. Paulina Suwandhi', 
            'specialty'    => 'Dokter Umum', 
            'schedule'     => 'Selasa Pagi, Jumat Siang' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/300x300/003153/ffffff?text=dr+Sardi',
            'name'         => 'dr. Sardi', 
            'specialty'    => 'Dokter Umum', 
            'schedule'     => 'Rabu Siang, Kamis Siang, Jumat Pagi' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/300x300/1a4a76/ffffff?text=dr+Siti',
            'name'         => 'dr. Siti Riani', 
            'specialty'    => 'Dokter Umum', 
            'schedule'     => 'Senin Pagi' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/300x300/0b1a2f/ffffff?text=dr+Billy',
            'name'         => 'dr. Billy Abdul Latif', 
            'specialty'    => 'Dokter Umum', 
            'schedule'     => 'Selasa Siang' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/300x300/1a4a76/ffffff?text=drg+Nurul',
            'name'         => 'drg. Nurul Urfa Farida', 
            'specialty'    => 'Dokter Gigi', 
            'schedule'     => 'Senin Pagi, Selasa Pagi, Rabu Siang, Kamis Siang, Jumat Pagi' 
        ),
        array( 
            'fallback_img' => 'https://placehold.co/300x300/003153/ffffff?text=drg+Pratiwi',
            'name'         => 'drg. Pratiwi Veterin Apriyani', 
            'specialty'    => 'Dokter Gigi', 
            'schedule'     => 'Senin Siang, Selasa Siang, Rabu Pagi, Kamis Pagi, Jumat Siang' 
        ),
    );

    $atts = shortcode_atts(
        array(
            'section_id'   => 'dokter',
            'title_pre'    => 'Tim',
            'title_hl'     => 'Dokter',
            'title_post'   => 'Klinik',
            'subtitle'     => 'Tenaga medis profesional yang berdedikasi memberikan pelayanan terbaik untuk Anda.',
            'items'        => urlencode( wp_json_encode( $dummy_doctors ) ),
            
            // Info Jadwal Bawah
            'sch_pagi'     => '08.00 – 12.00',
            'sch_siang'    => '13.00 – 16.00',
            
            'el_class'     => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-doctor-section section-block alt' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater dokter
    $doctor_items = vc_param_group_parse_atts( $atts['items'] );
    
    // Logika Fallback
    $has_valid_item = false;
    if ( is_array( $doctor_items ) ) {
        foreach ( $doctor_items as $item ) {
            if ( ! empty( $item['name'] ) ) {
                $has_valid_item = true;
                break;
            }
        }
    }
    if ( ! $has_valid_item ) {
        $doctor_items = $dummy_doctors;
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-doctor-container">
            
            <div class="ppic-doctor-header">
                <h2 class="ppic-doctor-title">
                    <?php echo esc_html( $atts['title_pre'] ); ?> 
                    <span><?php echo esc_html( $atts['title_hl'] ); ?></span> 
                    <?php echo esc_html( $atts['title_post'] ); ?>
                </h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-doctor-subtitle">
                        <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="ppic-doctor-grid">
                <?php foreach ( $doctor_items as $item ) : 
                    $name      = isset( $item['name'] ) ? trim( $item['name'] ) : '';
                    $specialty = isset( $item['specialty'] ) ? trim( $item['specialty'] ) : '';
                    $schedule  = isset( $item['schedule'] ) ? trim( $item['schedule'] ) : '';
                    
                    if ( empty( $name ) ) continue;

                    // Pengaturan Gambar
                    $img_url = isset( $item['fallback_img'] ) ? $item['fallback_img'] : 'https://placehold.co/300x300/003153/ffffff?text=' . urlencode(str_replace(' ', '+', $name));
                    if ( ! empty( $item['image'] ) ) {
                        $uploaded_img = wp_get_attachment_image_url( $item['image'], 'medium_large' );
                        if ( $uploaded_img ) {
                            $img_url = $uploaded_img;
                        }
                    }
                ?>
                    <div class="ppic-doctor-card">
                        <div class="doctor-photo-wrap">
                            <img class="doctor-photo" src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy" />
                        </div>
                        <div class="doctor-info">
                            <h4><?php echo esc_html( $name ); ?></h4>
                            
                            <?php if ( ! empty( $specialty ) ) : ?>
                                <div class="specialty"><?php echo esc_html( $specialty ); ?></div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $schedule ) ) : ?>
                                <div class="detail">
                                    <i class="fas fa-calendar-alt"></i> <?php echo esc_html( $schedule ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Schedule Info Bawah -->
            <?php if ( ! empty( $atts['sch_pagi'] ) || ! empty( $atts['sch_siang'] ) ) : ?>
            <div class="ppic-schedule-bottom-wrap">
                <div class="schedule-info-box">
                    <p>
                        <i class="fas fa-clock"></i> 
                        <?php if ( ! empty( $atts['sch_pagi'] ) ) : ?>
                            <span class="label">Pagi</span> : <?php echo esc_html( $atts['sch_pagi'] ); ?>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $atts['sch_pagi'] ) && ! empty( $atts['sch_siang'] ) ) : ?>
                            <span class="divider">|</span>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $atts['sch_siang'] ) ) : ?>
                            <span class="label">Siang</span> : <?php echo esc_html( $atts['sch_siang'] ); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_klinik_tim_dokter_element' );
function ppic_register_klinik_tim_dokter_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_doctors = array(
        array( 'fallback_img' => 'https://placehold.co/300x300/003153/ffffff?text=dr+Herry', 'name' => 'dr. Herry Sumarsono', 'specialty' => 'Dokter Umum', 'schedule' => 'Rabu Pagi' ),
        array( 'fallback_img' => 'https://placehold.co/300x300/1a4a76/ffffff?text=dr+Dwi', 'name' => 'dr. Dwi Utari', 'specialty' => 'Dokter Umum', 'schedule' => 'Senin Siang, Kamis Pagi' ),
        array( 'fallback_img' => 'https://placehold.co/300x300/0b1a2f/ffffff?text=dr+Paulina', 'name' => 'dr. Paulina Suwandhi', 'specialty' => 'Dokter Umum', 'schedule' => 'Selasa Pagi, Jumat Siang' ),
        array( 'fallback_img' => 'https://placehold.co/300x300/003153/ffffff?text=dr+Sardi', 'name' => 'dr. Sardi', 'specialty' => 'Dokter Umum', 'schedule' => 'Rabu Siang, Kamis Siang, Jumat Pagi' ),
        array( 'fallback_img' => 'https://placehold.co/300x300/1a4a76/ffffff?text=dr+Siti', 'name' => 'dr. Siti Riani', 'specialty' => 'Dokter Umum', 'schedule' => 'Senin Pagi' ),
        array( 'fallback_img' => 'https://placehold.co/300x300/0b1a2f/ffffff?text=dr+Billy', 'name' => 'dr. Billy Abdul Latif', 'specialty' => 'Dokter Umum', 'schedule' => 'Selasa Siang' ),
        array( 'fallback_img' => 'https://placehold.co/300x300/1a4a76/ffffff?text=drg+Nurul', 'name' => 'drg. Nurul Urfa Farida', 'specialty' => 'Dokter Gigi', 'schedule' => 'Senin Pagi, Selasa Pagi, Rabu Siang, Kamis Siang, Jumat Pagi' ),
        array( 'fallback_img' => 'https://placehold.co/300x300/003153/ffffff?text=drg+Pratiwi', 'name' => 'drg. Pratiwi Veterin Apriyani', 'specialty' => 'Dokter Gigi', 'schedule' => 'Senin Siang, Selasa Siang, Rabu Pagi, Kamis Pagi, Jumat Siang' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Klinik Tim Dokter', 'ppic-custom-element' ),
            'base'     => 'ppic_klinik_tim_dokter',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-groups',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'dokter',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul Awal (Hitam)', 'ppic-custom-element' ),
                    'param_name'  => 'title_pre',
                    'value'       => 'Tim',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul Tengah (Kuning)', 'ppic-custom-element' ),
                    'param_name'  => 'title_hl',
                    'value'       => 'Dokter',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Judul Akhir (Hitam)', 'ppic-custom-element' ),
                    'param_name'  => 'title_post',
                    'value'       => 'Klinik',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Subtitle', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Tenaga medis profesional yang berdedikasi memberikan pelayanan terbaik untuk Anda.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                
                // DATA DOKTER
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Dokter', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_doctors ) ),
                    'group'       => __( 'Daftar Dokter', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'attach_image', 'heading' => 'Foto Dokter', 'param_name' => 'image', 'description' => 'Gunakan rasio gambar 1:1 (KOTAK) untuk hasil terbaik.'),
                        array('type' => 'textfield', 'heading' => 'Fallback Image URL (Abaikan jika upload foto)', 'param_name' => 'fallback_img'),
                        array('type' => 'textfield', 'heading' => 'Nama Lengkap & Gelar', 'param_name' => 'name', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Spesialisasi (Misal: Dokter Umum)', 'param_name' => 'specialty'),
                        array('type' => 'textfield', 'heading' => 'Jadwal Praktik Singkat', 'param_name' => 'schedule'),
                    ),
                ),

                // INFO JADWAL
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Keterangan Jadwal Pagi', 'ppic-custom-element' ),
                    'param_name'  => 'sch_pagi',
                    'value'       => '08.00 – 12.00',
                    'group'       => __( 'Info Jadwal Bawah', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Keterangan Jadwal Siang', 'ppic-custom-element' ),
                    'param_name'  => 'sch_siang',
                    'value'       => '13.00 – 16.00',
                    'group'       => __( 'Info Jadwal Bawah', 'ppic-custom-element' ),
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