<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_sisfo_list', 'ppic_sisfo_list_render' );
function ppic_sisfo_list_render( $atts ) {
    
    // 1. DATA DUMMY BAWAAN LENGKAP (18 ITEM)[cite: 17]
    $dummy_systems = array(
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-graduation-cap', 'title' => 'SIAKAD', 'desc' => 'Sistem Informasi Akademik terpadu untuk KRS, KHS, jadwal kuliah, pembayaran, dan transkrip nilai.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-chalkboard-teacher', 'title' => 'LMS', 'desc' => 'Learning Management System untuk materi kuliah, tugas, kuis, forum diskusi, dan pengumpulan tugas online.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Administrasi', 'icon' => 'fas fa-user-plus', 'title' => 'Sipencatar', 'desc' => 'Sistem Penerimaan Calon Taruna/Taruni PPI Curug. Pendaftaran, seleksi, pengumuman, dan daftar ulang.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-book-open', 'title' => 'e-Library', 'desc' => 'Perpustakaan digital dengan koleksi buku, jurnal, skripsi, tesis, dan disertasi dalam format elektronik.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-archive', 'title' => 'e-Repository', 'desc' => 'Repositori institusi untuk menyimpan, mengelola, dan menyebarluaskan karya ilmiah civitas akademika.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-feather-alt', 'title' => 'Jurnal Langit Biru', 'desc' => 'Jurnal ilmiah penerbangan terakreditasi Sinta. Publikasi hasil penelitian dosen dan mahasiswa PPI Curug.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-info-circle', 'title' => 'PPID', 'desc' => 'Pejabat Pengelola Informasi dan Dokumentasi. Layanan informasi publik, permohonan data, dan dokumentasi.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-poll', 'title' => 'Survey Kepuasan', 'desc' => 'Sistem survei untuk mengukur kepuasan mahasiswa, orang tua, alumni, dan mitra terhadap layanan PPI Curug.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-exclamation-triangle', 'title' => 'Pengaduan Online', 'desc' => 'Saluran pengaduan untuk menyampaikan keluhan, saran, dan masukan secara anonim atau teridentifikasi.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Administrasi', 'icon' => 'fas fa-user-tie', 'title' => 'Tracer Study', 'desc' => 'Pelacakan alumni untuk mengetahui profil lulusan, masa tunggu kerja, relevansi kompetensi, dan kepuasan.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-building', 'title' => 'Sewa Aset & Fasilitas', 'desc' => 'Pemesanan dan penyewaan simulator, gedung, pesawat latih, asrama, laboratorium, dan fasilitas PPI Curug.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-heartbeat', 'title' => 'Klinik PPIC', 'desc' => 'Sistem pendaftaran dan rekam medis online untuk layanan kesehatan klinik PPI Curug, MCU, dan vaksinasi.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-calendar-alt', 'title' => 'Kalender Akademik', 'desc' => 'Informasi jadwal perkuliahan, ujian, libur, dan kegiatan akademik selama satu tahun ajaran penuh.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-chalkboard', 'title' => 'Profil Dosen & Instruktur', 'desc' => 'Database dosen dan instruktur PPI Curug beserta bidang keahlian, riwayat pendidikan, dan publikasi.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-microscope', 'title' => 'Pusat Penelitian', 'desc' => 'Portal penelitian dan pengabdian masyarakat, pengajuan hibah, laporan penelitian, dan kolaborasi riset.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-bullhorn', 'title' => 'Siaran Pers', 'desc' => 'Arsip siaran pers, pengumuman resmi, dan rilis media dari PPI Curug untuk publik dan mitra.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Administrasi', 'icon' => 'fas fa-handshake', 'title' => 'Ikatan Alumni', 'desc' => 'Portal alumni untuk berjejaring, berbagi informasi lowongan, kegiatan reuni, dan kontribusi untuk kampus.', 'link' => 'url:%23|||', 'status' => 'Online' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-images', 'title' => 'Galeri Digital', 'desc' => 'Koleksi foto dan video kegiatan akademik, kemahasiswaan, pelatihan, dan fasilitas PPI Curug.', 'link' => 'url:%23|||', 'status' => 'Online' ),
    );

    $atts = shortcode_atts(
        array(
            'items'    => urlencode( wp_json_encode( $dummy_systems ) ),
            'el_class' => '',
        ),
        $atts
    );

    $wrapper_class = 'ppic-sisfo-list-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse repeater data
    $system_items = vc_param_group_parse_atts( $atts['items'] );
    
    // Fallback logic
    $has_valid_item = false;
    if ( is_array( $system_items ) ) {
        foreach ( $system_items as $item ) {
            if ( ! empty( $item['title'] ) ) {
                $has_valid_item = true;
                break;
            }
        }
    }
    if ( ! $has_valid_item ) {
        $system_items = $dummy_systems;
    }

    ob_start(); ?>
    
    <section class="<?php echo $wrapper_class; ?>">
        <div class="ppic-sisfo-container">
            <div class="ppic-sisfo-grid">
                
                <?php foreach ( $system_items as $item ) : 
                    $badge  = isset( $item['badge'] ) ? trim( $item['badge'] ) : 'Aplikasi';
                    $icon   = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-desktop';
                    $title  = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                    $desc   = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                    $status = isset( $item['status'] ) ? trim( $item['status'] ) : 'Online';
                    
                    if ( empty( $title ) ) continue;

                    // Parse Link URL
                    $link_arr = vc_build_link( isset($item['link']) ? $item['link'] : '' );
                    $url      = !empty($link_arr['url']) ? esc_url($link_arr['url']) : '#';
                    $target   = !empty($link_arr['target']) ? ' target="'.esc_attr($link_arr['target']).'"' : '';
                    
                    // Set Status Color Indicator
                    $status_lower = strtolower($status);
                    $status_color = '#22c55e'; // Green default for Online
                    if ( strpos( $status_lower, 'maintenance' ) !== false || strpos( $status_lower, 'gangguan' ) !== false ) {
                        $status_color = '#f59e0b'; // Yellow/Orange
                    } else if ( strpos( $status_lower, 'offline' ) !== false || strpos( $status_lower, 'mati' ) !== false ) {
                        $status_color = '#ef4444'; // Red
                    }
                ?>
                    <div class="ppic-sisfo-card">
                        
                        <!-- Header / Kategori -->
                        <div class="sisfo-badge-wrap">
                            <span class="sisfo-badge"><?php echo esc_html( strtoupper($badge) ); ?></span>
                        </div>
                        
                        <!-- Ikon Aplikasi -->
                        <div class="sisfo-icon-wrap">
                            <i class="<?php echo esc_attr( $icon ); ?>"></i>
                        </div>
                        
                        <!-- Teks Info -->
                        <h3><?php echo esc_html( $title ); ?></h3>
                        <p><?php echo wp_kses_post( $desc ); ?></p>
                        
                        <!-- Footer (Status & Tombol) -->
                        <div class="sisfo-card-footer">
                            <span class="sisfo-status" style="color: <?php echo $status_color; ?>; background-color: <?php echo $status_color; ?>1A;">
                                <i class="fas fa-circle" style="font-size:8px; margin-right:6px;"></i> <?php echo esc_html( $status ); ?>
                            </span>
                            <a href="<?php echo $url; ?>" <?php echo $target; ?> class="btn-sisfo-open">
                                Buka Aplikasi <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
                
            </div>
        </div>
    </section>

    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_sisfo_list_element' );
function ppic_register_sisfo_list_element() {
    if ( ! function_exists( 'vc_map' ) ) { return; }

    $dummy_systems = array(
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-graduation-cap', 'title' => 'SIAKAD', 'desc' => 'Sistem Informasi Akademik terpadu untuk KRS, KHS, jadwal kuliah, pembayaran, dan transkrip nilai.' ),
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-chalkboard-teacher', 'title' => 'LMS', 'desc' => 'Learning Management System untuk materi kuliah, tugas, kuis, forum diskusi, dan pengumpulan tugas online.' ),
        array( 'badge' => 'Administrasi', 'icon' => 'fas fa-user-plus', 'title' => 'Sipencatar', 'desc' => 'Sistem Penerimaan Calon Taruna/Taruni PPI Curug. Pendaftaran, seleksi, pengumuman, dan daftar ulang.' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-book-open', 'title' => 'e-Library', 'desc' => 'Perpustakaan digital dengan koleksi buku, jurnal, skripsi, tesis, dan disertasi dalam format elektronik.' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-archive', 'title' => 'e-Repository', 'desc' => 'Repositori institusi untuk menyimpan, mengelola, dan menyebarluaskan karya ilmiah civitas akademika.' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-feather-alt', 'title' => 'Jurnal Langit Biru', 'desc' => 'Jurnal ilmiah penerbangan terakreditasi Sinta. Publikasi hasil penelitian dosen dan mahasiswa PPI Curug.' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-info-circle', 'title' => 'PPID', 'desc' => 'Pejabat Pengelola Informasi dan Dokumentasi. Layanan informasi publik, permohonan data, dan dokumentasi.' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-poll', 'title' => 'Survey Kepuasan', 'desc' => 'Sistem survei untuk mengukur kepuasan mahasiswa, orang tua, alumni, dan mitra terhadap layanan PPI Curug.' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-exclamation-triangle', 'title' => 'Pengaduan Online', 'desc' => 'Saluran pengaduan untuk menyampaikan keluhan, saran, dan masukan secara anonim atau teridentifikasi.' ),
        array( 'badge' => 'Administrasi', 'icon' => 'fas fa-user-tie', 'title' => 'Tracer Study', 'desc' => 'Pelacakan alumni untuk mengetahui profil lulusan, masa tunggu kerja, relevansi kompetensi, dan kepuasan.' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-building', 'title' => 'Sewa Aset & Fasilitas', 'desc' => 'Pemesanan dan penyewaan simulator, gedung, pesawat latih, asrama, laboratorium, dan fasilitas PPI Curug.' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-heartbeat', 'title' => 'Klinik PPIC', 'desc' => 'Sistem pendaftaran dan rekam medis online untuk layanan kesehatan klinik PPI Curug, MCU, dan vaksinasi.' ),
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-calendar-alt', 'title' => 'Kalender Akademik', 'desc' => 'Informasi jadwal perkuliahan, ujian, libur, dan kegiatan akademik selama satu tahun ajaran penuh.' ),
        array( 'badge' => 'Akademik', 'icon' => 'fas fa-chalkboard', 'title' => 'Profil Dosen & Instruktur', 'desc' => 'Database dosen dan instruktur PPI Curug beserta bidang keahlian, riwayat pendidikan, dan publikasi.' ),
        array( 'badge' => 'Penelitian', 'icon' => 'fas fa-microscope', 'title' => 'Pusat Penelitian', 'desc' => 'Portal penelitian dan pengabdian masyarakat, pengajuan hibah, laporan penelitian, dan kolaborasi riset.' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-bullhorn', 'title' => 'Siaran Pers', 'desc' => 'Arsip siaran pers, pengumuman resmi, dan rilis media dari PPI Curug untuk publik dan mitra.' ),
        array( 'badge' => 'Administrasi', 'icon' => 'fas fa-handshake', 'title' => 'Ikatan Alumni', 'desc' => 'Portal alumni untuk berjejaring, berbagi informasi lowongan, kegiatan reuni, dan kontribusi untuk kampus.' ),
        array( 'badge' => 'Layanan Publik', 'icon' => 'fas fa-images', 'title' => 'Galeri Digital', 'desc' => 'Koleksi foto dan video kegiatan akademik, kemahasiswaan, pelatihan, dan fasilitas PPI Curug.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Sisfo List (Grid)', 'ppic-custom-element' ),
            'base'     => 'ppic_sisfo_list',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-screenoptions',
            'params'   => array(
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Sistem & Aplikasi', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_systems ) ),
                    'params'      => array(
                        array('type' => 'textfield', 'heading' => 'Nama Aplikasi', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Label Kategori (Cth: Akademik, Penelitian)', 'param_name' => 'badge', 'value' => 'Akademik'),
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon', 'value' => 'fas fa-desktop'),
                        array('type' => 'textarea', 'heading' => 'Deskripsi Singkat', 'param_name' => 'desc'),
                        array('type' => 'textfield', 'heading' => 'Status Server', 'param_name' => 'status', 'value' => 'Online', 'description' => 'Isi dengan: Online, Maintenance, atau Offline. Warna indikator akan otomatis menyesuaikan.'),
                        array('type' => 'vc_link', 'heading' => 'Tautan / URL Aplikasi', 'param_name' => 'link'),
                    ),
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