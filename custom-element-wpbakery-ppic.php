<?php
/**
 * Plugin Name: Custom Element WP Bakery PPIC
 * Plugin URI:  https://ppicurug.ac.id/
 * Description: Plugin modular untuk elemen kustom WPBakery PPI Curug.
 * Version:     1.60.1
 * Author:      IT Team PPI Curug
 * License:     GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Definisikan path plugin agar mudah dipanggil
define( 'PPIC_WPB_DIR', plugin_dir_path( __FILE__ ) );
define( 'PPIC_WPB_URL', plugin_dir_url( __FILE__ ) );

// 1. Memanggil File CSS Eksternal
add_action( 'wp_enqueue_scripts', 'ppic_custom_elements_enqueue_styles' );
function ppic_custom_elements_enqueue_styles() {
    wp_enqueue_style(
        'ppic-font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        array(),
        '5.15.4'
    );

    wp_enqueue_style( 
        'ppic-custom-elements-style', 
        PPIC_WPB_URL . 'assets/style.css', 
        array(), 
        '1.60.1' 
    );
}

// 2. Memanggil (Include) File Elemen-Elemen Kustom
// Jika nanti kamu buat elemen baru, cukup tambahkan require_once-nya di sini
require_once PPIC_WPB_DIR . 'elements/ppic-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-accreditation.php';
require_once PPIC_WPB_DIR . 'elements/ppic-director-greeting.php';
require_once PPIC_WPB_DIR . 'elements/ppic-featured-programs.php';
require_once PPIC_WPB_DIR . 'elements/ppic-instagram-feed.php';
require_once PPIC_WPB_DIR . 'elements/ppic-stats.php';
require_once PPIC_WPB_DIR . 'elements/ppic-why-home.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-news-grid.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-explore-programs-hero.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-explore-rpl-programs.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-about.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-timeline.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-visi-misi.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-excellence.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-akademik-grid-home.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-courses.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-dosen-hero.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-dosen-directory.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-training-catalog-hero.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-training-catalog.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-gallery-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-gallery-main.php';
require_once PPIC_WPB_DIR . 'elements/ppic-leadership.php';
require_once PPIC_WPB_DIR . 'elements/ppic-main-function.php';
require_once PPIC_WPB_DIR . 'elements/ppic-raih-masa-depan.php';
require_once PPIC_WPB_DIR . 'elements/ppic-sipencatar-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-jalur-sipencatar.php';
require_once PPIC_WPB_DIR . 'elements/ppic-persyaratan-sipencatar.php';
require_once PPIC_WPB_DIR . 'elements/ppic-pendaftaran-sipencatar.php';
require_once PPIC_WPB_DIR . 'elements/ppic-biaya-sipencatar.php';
require_once PPIC_WPB_DIR . 'elements/ppic-faq-sipencatar.php';
require_once PPIC_WPB_DIR . 'elements/ppic-explore-study-programs.php';
require_once PPIC_WPB_DIR . 'elements/ppic-explore-global-training.php';
require_once PPIC_WPB_DIR . 'elements/ppic-ppid-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-ppid-mengenal.php';
require_once PPIC_WPB_DIR . 'elements/ppic-ppid-layanan-informasi.php';
require_once PPIC_WPB_DIR . 'elements/ppic-ppid-daftar-informasi.php';
require_once PPIC_WPB_DIR . 'elements/ppic-ppid-akuntabilitas-pelaporan.php';
require_once PPIC_WPB_DIR . 'elements/ppic-ppid-transparansi-responsif.php';
require_once PPIC_WPB_DIR . 'elements/ppic-ppid-butuh-informasi.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-stats.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-kehidupan-kampus.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-lokasi-konektivitas.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-fasilitas-sertifikasi-dll.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-prestasi.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-beasiswa-bantuan.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-serapan-jejak.php';
require_once PPIC_WPB_DIR . 'elements/ppic-kenapa-statistik-alumni.php';
require_once PPIC_WPB_DIR . 'elements/ppic-pelatihan-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-pelatihan-icao-tpp.php';
require_once PPIC_WPB_DIR . 'elements/ppic-pelatihan-icao-astc.php';
require_once PPIC_WPB_DIR . 'elements/ppic-pelatihan-katalog-unggulan.php';
require_once PPIC_WPB_DIR . 'elements/ppic-pelatihan-jadwal-mendatang.php';
require_once PPIC_WPB_DIR . 'elements/ppic-penelitian-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-penelitian-pusppm.php';
require_once PPIC_WPB_DIR . 'elements/ppic-penelitian-jurnal-publikasi.php';
require_once PPIC_WPB_DIR . 'elements/ppic-penelitian-e-repo.php';
require_once PPIC_WPB_DIR . 'elements/ppic-penelitian-e-library.php';
require_once PPIC_WPB_DIR . 'elements/ppic-penelitian-stats.php';