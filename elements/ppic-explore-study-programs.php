<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_explore_study_programs', 'ppic_explore_study_programs_render' );
function ppic_explore_study_programs_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => '10 Program Studi',
            'subtitle' => 'Kurikulum berbasis kompetensi, standar ICAO dan Kemenhub, dengan penekanan pada praktik industri serta pembentukan karakter profesional.',
            'programs' => '',
        ),
        $atts
    );

    $items = vc_param_group_parse_atts( $atts['programs'] );

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '';
    }

    ob_start(); ?>
    <section class="ppic-study-programs-section">
        <div class="ppic-sp-container">
            <h2 class="ppic-sp-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <p class="ppic-sp-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            
            <div class="ppic-sp-grid">
                <?php foreach ( $items as $item ) :
                    $badge = isset( $item['badge'] ) ? trim( $item['badge'] ) : '';
                    $title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                    $desc  = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                    $creds = isset( $item['creds'] ) ? trim( $item['creds'] ) : '';
                    $link  = isset( $item['link'] ) && !empty( $item['link'] ) ? trim( $item['link'] ) : '#';

                    if ( '' === $title && '' === $desc ) {
                        continue;
                    }
                    ?>
                    <div class="ppic-sp-card">
                        <?php if ( '' !== $badge ) : ?>
                            <div class="ppic-sp-badge"><?php echo esc_html( $badge ); ?></div>
                        <?php endif; ?>
                        
                        <h3><?php echo esc_html( $title ); ?></h3>
                        <p class="ppic-sp-desc"><?php echo esc_html( $desc ); ?></p>
                        
                        <?php if ( '' !== $creds ) : ?>
                            <div class="ppic-sp-credentials">
                                <?php
                                // Pecah berdasarkan koma
                                $cred_array = array_map( 'trim', explode( ',', $creds ) );
                                foreach ( $cred_array as $cred_item ) {
                                    if ( empty( $cred_item ) ) continue;
                                    
                                    // Logika deteksi ikon otomatis
                                    $icon = 'fas fa-id-card'; // Default lisensi
                                    if ( stripos( $cred_item, 'Ijazah' ) !== false ) {
                                        $icon = 'fas fa-graduation-cap';
                                    } elseif ( stripos( $cred_item, 'Serkom' ) !== false ) {
                                        $icon = 'fas fa-certificate';
                                    }
                                    
                                    echo '<span class="cred-pill"><i class="' . esc_attr( $icon ) . '"></i> ' . esc_html( $cred_item ) . '</span>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?php echo esc_url( $link ); ?>" class="ppic-sp-btn">
                            Detail Program <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_explore_study_programs_element' );
function ppic_register_explore_study_programs_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Data Dummy lengkap 10 Program Studi dengan URL Asli
    $dummy_data = array(
        array('badge' => 'DIV – PNB', 'title' => 'Penerbang', 'desc' => 'Program pilot profesional terintegrasi CASR Part 141. Memperoleh lisensi CPL/IR, PPL, Multi Engine, dan jam terbang terstandar dunia.', 'creds' => 'Ijazah DIV, Serkom, Lisensi PPL, Lisensi CPL, Class Rating ME, Instrument Rating', 'link' => 'https://web.ppicurug.ac.id/d4-pnb.php'),
        array('badge' => 'DIV – LLU', 'title' => 'Lalu Lintas Udara', 'desc' => 'Sarjana Terapan Air Traffic Controller (ATC). Menggunakan simulasi radar mutakhir, kurikulum ICAO TPP, siap memandu lalu lintas udara global.', 'creds' => 'Ijazah DIV, Serkom, Lisensi ATC', 'link' => 'https://sites.google.com/view/atcppic'),
        array('badge' => 'DIV – TLB', 'title' => 'Teknik Listrik Bandara', 'desc' => 'Sistem kelistrikan modern bandara: Airfield Lighting, Power Distribution, UPS, tenaga surya. Lulusan siap menjadi certified airport electrician.', 'creds' => 'Ijazah DIV, Serkom', 'link' => 'https://tlb.ppicurug.ac.id/'),
        array('badge' => 'DIV – TPU', 'title' => 'Teknik Pesawat Udara', 'desc' => 'Approved AMTO CASR Part 147. Konsentrasi Airframe & Powerplant. Peluang memperoleh lisensi teknisi pesawat bersertifikasi nasional.', 'creds' => 'Ijazah DIV, Serkom, Lisensi A1 (Airframe), Lisensi A3 (Piston Engine), Lisensi A4 (Gas Turbine Engine)', 'link' => 'https://tpu.ppicurug.ac.id/'),
        array('badge' => 'DIV – TNU', 'title' => 'Teknik Navigasi Udara', 'desc' => 'Pakar navigasi penerbangan: ILS, VOR/DME, GNSS, sistem pendaratan presisi. Menguasai telekomunikasi, radio navigation, dan teknologi satelit.', 'creds' => 'Ijazah DIV, Serkom, Lisensi CNS', 'link' => 'https://tnu.ppicurug.ac.id/'),
        array('badge' => 'DIII – PA', 'title' => 'Penerangan Aeronautika', 'desc' => 'Ahli madya AIS/Aeronautical Information Service. Mengelola NOTAM, pre-flight briefing, dan sistem informasi navigasi udara.', 'creds' => 'Ijazah DIII, Serkom, Lisensi AIS', 'link' => 'https://pa.ppicurug.ac.id/'),
        array('badge' => 'DIII – PKP', 'title' => 'Pertolongan Kecelakaan Pesawat', 'desc' => 'Kesiapsiagaan darurat bandara, teknik penyelamatan pesawat, pemadam kebakaran kategori tinggi, dan prosedur evakuasi (ARFF).', 'creds' => 'Ijazah DIII, Serkom', 'link' => 'https://pkp.ppicurug.ac.id/'),
        array('badge' => 'DIII – TMB', 'title' => 'Teknik Mekanikal Bandara', 'desc' => 'Perawatan & rekayasa sistem mekanikal bandara: Passenger Boarding Bridge, baggage handling, HVAC, escalator, dan peralatan GSE.', 'creds' => 'Ijazah DIII, Serkom', 'link' => 'https://web.ppicurug.ac.id/d3-tmb.php'),
        array('badge' => 'DIII – TBL', 'title' => 'Teknik Bangunan Landasan', 'desc' => 'Konstruksi & pemeliharaan infrastruktur bandara: Runway, Taxiway, Apron, Terminal. Fokus pada perkerasan khusus bandara.', 'creds' => 'Ijazah DIII, Serkom', 'link' => 'https://tbl.ppicurug.ac.id/'),
        array('badge' => 'DIII – OBU', 'title' => 'Operasi Bandar Udara', 'desc' => 'Manajemen operasional bandara terintegrasi: Ground Handling, Load Control, Flight Dispatch, Avsec, Cargo, Airport Management, Safety System.', 'creds' => 'Ijazah DIII, Serkom, Lisensi Basic Avsec, Lisensi Junior Avsec, Lisensi Dangerous Goods', 'link' => 'https://obu.ppicurug.ac.id/')
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Explore Study Programs', 'ppic-custom-element' ),
            'base'     => 'ppic_explore_study_programs',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-widgets-menus',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => '10 Program Studi',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Kurikulum berbasis kompetensi, standar ICAO dan Kemenhub, dengan penekanan pada praktik industri serta pembentukan karakter profesional.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Program Studi', 'ppic-custom-element' ),
                    'param_name' => 'programs',
                    'value'      => urlencode( wp_json_encode( $dummy_data ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Badge / Label Atas', 'ppic-custom-element' ),
                            'param_name'  => 'badge',
                            'description' => 'Contoh: DIV - PNB',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Nama Program', 'ppic-custom-element' ),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi Program', 'ppic-custom-element' ),
                            'param_name' => 'desc',
                        ),
                        array(
                            'type'        => 'textarea',
                            'heading'     => __( 'Credentials / Label Bawah', 'ppic-custom-element' ),
                            'param_name'  => 'creds',
                            'description' => 'Pisahkan dengan koma. Contoh: Ijazah DIV, Serkom, Lisensi PPL. (Ikon otomatis menyesuaikan kata kunci).',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Detail Program', 'ppic-custom-element' ),
                            'param_name' => 'link',
                            'description' => 'Masukkan link untuk tombol Detail Program.',
                        ),
                    ),
                ),
            ),
        )
    );
}