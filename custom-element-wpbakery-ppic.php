<?php
/**
 * Plugin Name: Custom Element WP Bakery PPIC
 * Plugin URI:  https://ppicurug.ac.id/
 * Description: Plugin modular untuk elemen kustom WPBakery PPI Curug.
 * Version:     1.14.6
 * Author:      IT Team PPI Curug
 * License:     GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Definisikan path plugin agar mudah dipanggil
define( 'PPIC_WPB_DIR', plugin_dir_path( __FILE__ ) );
define( 'PPIC_WPB_URL', plugin_dir_url( __FILE__ ) );

function ppic_custom_elements_shortcodes() {
    return array(
        'ppic_hero_section',
        'ppic_accreditation_section',
        'ppic_director_greeting',
        'ppic_featured_programs',
        'ppic_instagram_feed',
        'ppic_stats_section',
        'ppic_why_section',
        'ppic_news_grid',
        'ppic_explore_programs',
        'ppic_rpl_programs',
        'ppic_about_section',
        'ppic_timeline_section',
        'ppic_visi_misi_section',
        'ppic_excellence_section',
        'ppic_akademik_grid_home',
        'ppic_courses',
    );
}

// 1. Register style lalu enqueue hanya saat shortcode dipakai.
add_action( 'wp_enqueue_scripts', 'ppic_custom_elements_register_styles' );
function ppic_custom_elements_register_styles() {
    wp_register_style( 
        'ppic-custom-elements-style', 
        PPIC_WPB_URL . 'assets/style.css', 
        array(), 
        '1.14.6' 
    );
}

add_filter( 'the_posts', 'ppic_custom_elements_maybe_enqueue_styles', 10, 2 );
function ppic_custom_elements_maybe_enqueue_styles( $posts, $query ) {
    if ( is_admin() || empty( $posts ) || ! $query instanceof WP_Query ) {
        return $posts;
    }

    if ( wp_style_is( 'ppic-custom-elements-style', 'enqueued' ) ) {
        return $posts;
    }

    $shortcodes = ppic_custom_elements_shortcodes();

    foreach ( $posts as $post ) {
        if ( empty( $post->post_content ) ) {
            continue;
        }

        foreach ( $shortcodes as $shortcode_tag ) {
            if ( has_shortcode( $post->post_content, $shortcode_tag ) ) {
                wp_enqueue_style( 'ppic-custom-elements-style' );
                return $posts;
            }
        }
    }

    return $posts;
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
// require_once PPIC_WPB_DIR . 'elements/ppic-testimonial.php';