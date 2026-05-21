<?php
/**
 * Plugin Name: Custom Element WP Bakery PPIC
 * Plugin URI:  https://ppicurug.ac.id/
 * Description: Plugin modular untuk elemen kustom WPBakery PPI Curug.
 * Version:     1.0.9
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
        'ppic-custom-elements-style', 
        PPIC_WPB_URL . 'assets/style.css', 
        array(), 
        '1.0.9' 
    );
}

// 2. Memanggil (Include) File Elemen-Elemen Kustom
// Jika nanti kamu buat elemen baru, cukup tambahkan require_once-nya di sini
require_once PPIC_WPB_DIR . 'elements/ppic-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-clinic-hero.php';
require_once PPIC_WPB_DIR . 'elements/ppic-accreditation.php';
require_once PPIC_WPB_DIR . 'elements/ppic-director-greeting.php';
require_once PPIC_WPB_DIR . 'elements/ppic-featured-programs.php';
require_once PPIC_WPB_DIR . 'elements/ppic-instagram-feed.php';
require_once PPIC_WPB_DIR . 'elements/ppic-stats.php';
require_once PPIC_WPB_DIR . 'elements/ppic-why.php'; 
require_once PPIC_WPB_DIR . 'elements/ppic-news-grid.php'; 
// require_once PPIC_WPB_DIR . 'elements/ppic-testimonial.php';