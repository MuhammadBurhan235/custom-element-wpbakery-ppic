<?php
/**
 * Plugin Name: Custom Element WP Bakery PPIC
 * Plugin URI:  https://ppicurug.ac.id/
 * Description: Plugin modular untuk elemen kustom WPBakery PPI Curug.
 * Version:     1.17.1
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
        '1.17.1' 
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
require_once PPIC_WPB_DIR . 'elements/ppic-why.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-news-grid.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-explore-programs.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-rpl-programs.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-about.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-timeline.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-visi-misi.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-excellence.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-akademik-grid-home.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-courses.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-dosen-hero.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-dosen-directory.php'; 
// require_once PPIC_WPB_DIR . 'elements/ppic-testimonial.php';