<?php
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ );
}

$GLOBALS['ppic_preview_attachment_map'] = array(
    1 => 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg',
    2 => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1200&q=80',
    3 => 'https://images.unsplash.com/photo-1517479149777-5f3b1511d5ad?auto=format&fit=crop&w=1200&q=80',
    4 => 'https://ppicurug.ac.id/wp-content/uploads/2026/02/WhatsApp-Image-2026-02-04-at-10.28.58.jpeg',
    5 => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=1200&q=80',
);

$GLOBALS['ppic_preview_attachment_files'] = array(
    9001 => __DIR__ . '/preview-data/dosen-sample.csv',
);

$GLOBALS['ppic_preview_posts'] = array(
    array(
        'ID' => 101,
        'title' => 'Taruna PPI Curug Raih Prestasi Internasional',
        'excerpt' => 'Prestasi baru diraih taruna PPI Curug melalui kompetisi aviasi internasional yang menonjolkan kesiapan teknis, disiplin, dan kepemimpinan.',
        'permalink' => 'https://ppicurug.ac.id/berita/taruna-ppic-curug-raih-prestasi-internasional/',
        'thumbnail' => 'https://images.unsplash.com/photo-1517479149777-5f3b1511d5ad?auto=format&fit=crop&w=900&q=80',
    ),
    array(
        'ID' => 102,
        'title' => 'Laboratorium Simulator Diperbarui',
        'excerpt' => 'Pembaruan fasilitas simulator dilakukan untuk memperkuat pengalaman belajar berbasis industri dan meningkatkan standar keselamatan pelatihan.',
        'permalink' => 'https://ppicurug.ac.id/berita/laboratorium-simulator-diperbarui/',
        'thumbnail' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=80',
    ),
    array(
        'ID' => 103,
        'title' => 'Pendaftaran Program RPL Resmi Dibuka',
        'excerpt' => 'Program Rekognisi Pembelajaran Lampau kembali dibuka untuk praktisi yang ingin melanjutkan kualifikasi akademik ke jenjang sarjana terapan.',
        'permalink' => 'https://ppicurug.ac.id/berita/pendaftaran-program-rpl-resmi-dibuka/',
        'thumbnail' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=900&q=80',
    ),
);

$GLOBALS['ppic_preview_current_post'] = null;
$GLOBALS['ppic_preview_shortcodes'] = array();

if ( ! function_exists( '__' ) ) {
    function __( $text, $domain = null ) {
        return $text;
    }
}

if ( ! function_exists( 'add_shortcode' ) ) {
    function add_shortcode( $tag, $callback ) {
        $GLOBALS['ppic_preview_shortcodes'][ $tag ] = $callback;
        return null;
    }
}

if ( ! function_exists( 'add_action' ) ) {
    function add_action( $hook, $callback ) {
        return null;
    }
}

if ( ! function_exists( 'vc_map' ) ) {
    function vc_map( $config ) {
        return $config;
    }
}

if ( ! function_exists( 'shortcode_atts' ) ) {
    function shortcode_atts( $pairs, $atts ) {
        return array_merge( $pairs, is_array( $atts ) ? $atts : array() );
    }
}

if ( ! function_exists( 'vc_build_link' ) ) {
    function vc_build_link( $value ) {
        $defaults = array(
            'url' => '',
            'title' => '',
            'target' => '',
            'rel' => '',
        );

        if ( is_array( $value ) ) {
            return array_merge( $defaults, $value );
        }

        if ( ! is_string( $value ) || '' === trim( $value ) ) {
            return $defaults;
        }

        if ( false === strpos( $value, '|' ) && false === strpos( $value, ':' ) ) {
            $defaults['url'] = trim( $value );
            return $defaults;
        }

        foreach ( explode( '|', $value ) as $segment ) {
            $parts = explode( ':', $segment, 2 );
            if ( 2 !== count( $parts ) ) {
                continue;
            }

            $key = trim( $parts[0] );
            $decoded = rawurldecode( trim( $parts[1] ) );

            if ( array_key_exists( $key, $defaults ) ) {
                $defaults[ $key ] = $decoded;
            }
        }

        return $defaults;
    }
}

if ( ! function_exists( 'vc_param_group_parse_atts' ) ) {
    function vc_param_group_parse_atts( $value ) {
        if ( is_array( $value ) ) {
            return $value;
        }

        if ( ! is_string( $value ) || '' === trim( $value ) ) {
            return array();
        }

        $decoded = json_decode( urldecode( $value ), true );
        return is_array( $decoded ) ? $decoded : array();
    }
}

if ( ! function_exists( 'wp_json_encode' ) ) {
    function wp_json_encode( $value ) {
        return json_encode( $value );
    }
}

if ( ! function_exists( 'wp_get_attachment_image_src' ) ) {
    function wp_get_attachment_image_src( $attachment_id, $size ) {
        $map = isset( $GLOBALS['ppic_preview_attachment_map'] ) ? $GLOBALS['ppic_preview_attachment_map'] : array();
        $attachment_id = absint( $attachment_id );

        if ( isset( $map[ $attachment_id ] ) ) {
            return array( $map[ $attachment_id ], 1200, 900, true );
        }

        return false;
    }
}

if ( ! function_exists( 'absint' ) ) {
    function absint( $maybeint ) {
        return abs( intval( $maybeint ) );
    }
}

if ( ! function_exists( 'wp_unique_id' ) ) {
    function wp_unique_id( $prefix = '' ) {
        static $id_counter = 0;
        $id_counter++;
        return $prefix . $id_counter;
    }
}

if ( ! function_exists( 'home_url' ) ) {
    function home_url( $path = '' ) {
        return 'http://127.0.0.1:8080/' . ltrim( (string) $path, '/' );
    }
}

if ( ! function_exists( 'wp_kses_post' ) ) {
    function wp_kses_post( $text ) {
        return strip_tags( (string) $text, '<span><br><strong><em><b><i>' );
    }
}

if ( ! function_exists( 'wp_strip_all_tags' ) ) {
    function wp_strip_all_tags( $text ) {
        return trim( strip_tags( (string) $text ) );
    }
}

if ( ! function_exists( 'esc_html' ) ) {
    function esc_html( $text ) {
        return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
    }
}

if ( ! function_exists( 'esc_attr' ) ) {
    function esc_attr( $text ) {
        return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
    }
}

if ( ! function_exists( 'esc_url' ) ) {
    function esc_url( $url ) {
        return filter_var( (string) $url, FILTER_SANITIZE_URL );
    }
}

if ( ! function_exists( 'sanitize_email' ) ) {
    function sanitize_email( $email ) {
        $email = filter_var( (string) $email, FILTER_SANITIZE_EMAIL );
        return filter_var( $email, FILTER_VALIDATE_EMAIL ) ? $email : '';
    }
}

if ( ! function_exists( 'wp_unslash' ) ) {
    function wp_unslash( $value ) {
        return is_string( $value ) ? stripslashes( $value ) : $value;
    }
}

if ( ! function_exists( 'wp_rand' ) ) {
    function wp_rand( $min = 0, $max = 0 ) {
        return mt_rand( (int) $min, (int) $max );
    }
}

if ( ! function_exists( 'get_attached_file' ) ) {
    function get_attached_file( $attachment_id ) {
        $map = isset( $GLOBALS['ppic_preview_attachment_files'] ) ? $GLOBALS['ppic_preview_attachment_files'] : array();
        $attachment_id = absint( $attachment_id );

        return isset( $map[ $attachment_id ] ) ? $map[ $attachment_id ] : '';
    }
}

if ( ! function_exists( 'wp_enqueue_media' ) ) {
    function wp_enqueue_media() {
        return null;
    }
}

if ( ! function_exists( 'get_the_ID' ) ) {
    function get_the_ID() {
        return isset( $GLOBALS['ppic_preview_current_post']['ID'] ) ? $GLOBALS['ppic_preview_current_post']['ID'] : 0;
    }
}

if ( ! function_exists( 'get_the_title' ) ) {
    function get_the_title( $post_id = null ) {
        if ( null === $post_id && isset( $GLOBALS['ppic_preview_current_post']['title'] ) ) {
            return $GLOBALS['ppic_preview_current_post']['title'];
        }

        foreach ( $GLOBALS['ppic_preview_posts'] as $post ) {
            if ( $post['ID'] === $post_id ) {
                return $post['title'];
            }
        }

        return '';
    }
}

if ( ! function_exists( 'the_title' ) ) {
    function the_title() {
        echo esc_html( get_the_title() );
    }
}

if ( ! function_exists( 'the_title_attribute' ) ) {
    function the_title_attribute() {
        echo esc_attr( get_the_title() );
    }
}

if ( ! function_exists( 'get_the_excerpt' ) ) {
    function get_the_excerpt() {
        return isset( $GLOBALS['ppic_preview_current_post']['excerpt'] ) ? $GLOBALS['ppic_preview_current_post']['excerpt'] : '';
    }
}

if ( ! function_exists( 'get_permalink' ) ) {
    function get_permalink( $post_id = null ) {
        if ( null === $post_id && isset( $GLOBALS['ppic_preview_current_post']['permalink'] ) ) {
            return $GLOBALS['ppic_preview_current_post']['permalink'];
        }

        foreach ( $GLOBALS['ppic_preview_posts'] as $post ) {
            if ( $post['ID'] === $post_id ) {
                return $post['permalink'];
            }
        }

        return '#';
    }
}

if ( ! function_exists( 'the_permalink' ) ) {
    function the_permalink() {
        echo esc_url( get_permalink() );
    }
}

if ( ! function_exists( 'get_the_post_thumbnail_url' ) ) {
    function get_the_post_thumbnail_url( $post_id, $size = 'large' ) {
        foreach ( $GLOBALS['ppic_preview_posts'] as $post ) {
            if ( $post['ID'] === $post_id ) {
                return $post['thumbnail'];
            }
        }

        return '';
    }
}

if ( ! function_exists( 'wp_trim_words' ) ) {
    function wp_trim_words( $text, $num_words = 55, $more = '...' ) {
        $words = preg_split( '/\s+/', trim( (string) $text ) );
        if ( count( $words ) <= $num_words ) {
            return trim( (string) $text );
        }

        return implode( ' ', array_slice( $words, 0, $num_words ) ) . $more;
    }
}

if ( ! function_exists( 'wp_reset_postdata' ) ) {
    function wp_reset_postdata() {
        $GLOBALS['ppic_preview_current_post'] = null;
    }
}

if ( ! class_exists( 'WP_Query' ) ) {
    class WP_Query {
        private $posts = array();
        private $index = -1;

        public function __construct( $args = array() ) {
            $count = isset( $args['posts_per_page'] ) ? intval( $args['posts_per_page'] ) : count( $GLOBALS['ppic_preview_posts'] );
            $this->posts = array_slice( $GLOBALS['ppic_preview_posts'], 0, max( 0, $count ) );
        }

        public function have_posts() {
            return ( $this->index + 1 ) < count( $this->posts );
        }

        public function the_post() {
            $this->index++;

            if ( isset( $this->posts[ $this->index ] ) ) {
                $GLOBALS['ppic_preview_current_post'] = $this->posts[ $this->index ];
            }
        }
    }
}

if ( ! function_exists( 'ppic_preview_param_group' ) ) {
    function ppic_preview_param_group( $items ) {
        return urlencode( json_encode( $items ) );
    }
}

foreach ( glob( __DIR__ . '/elements/*.php' ) as $element_file ) {
    require_once $element_file;
}