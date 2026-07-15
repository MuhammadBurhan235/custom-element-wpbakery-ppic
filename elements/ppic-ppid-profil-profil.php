<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_profil_profil', 'ppic_ppid_profil_profil_render' );
function ppic_ppid_profil_profil_render( $atts, $content = null ) {
    $atts = shortcode_atts(
        array(
            // KARTU KIRI (PROFIL)
            'c1_title'    => 'Profil PPID',
            
            // KARTU KANAN (DASAR HUKUM)
            'c2_title'    => 'Dasar Hukum Utama',
            'c2_bullets'  => '',
            'c2_btn_text' => 'Lihat Selengkapnya',
            'c2_btn_url'  => 'url:https%3A%2F%2Fppicurug.ac.id%2Fdasar-hukum-dan-peraturan-perundang-undangan%2F|target:_blank',
            
            // UMUM
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    // Default Teks Profil Kiri jika konten WYSIWYG kosong
    $profil_content = ! empty( $content ) ? wpb_js_remove_wpautop( $content, true ) : '<p>Keterbukaan informasi merupakan salah satu pilar penting untuk mendukung pelaksanaan tata pemerintahan yang baik (<em>good governance</em>). Penerapan keterbukaan informasi publik di Indonesia didasari atas dasar hukum Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik (UU KIP).</p><p>Sebagai institusi pelayanan publik yang memiliki core business di bidang pendidikan, Politeknik Penerbangan Indonesia Curug (PPI Curug) wajib memberikan akses terhadap keterbukaan informasi publik.</p><p>PPI Curug sebagai Unit Pelaksana Teknis di bawah naungan Kementerian Perhubungan wajib memberikan pelayanan informasi yang efisien, akurat, dan tidak menyesatkan.</p><p>Sebagai bentuk komitmen, PPI Curug telah membentuk Pejabat Pengelola Informasi dan Dokumentasi (PPID) untuk Tahun 2024 melalui Surat Keputusan: KP. PPIC 167 Tahun 2024 tentang PPID Politeknik Penerbangan Indonesia Curug.</p>';

    // Default Fallback Data Dasar Hukum
    $dummy_bullets = array(
        array( 'text' => 'Undang-Undang No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik' ),
        array( 'text' => 'Peraturan Menteri Perhubungan No. PM 46 Tahun 2018' ),
        array( 'text' => 'Keputusan Menteri Perhubungan No. KP 117 Tahun 2022 tentang SOP PPID' ),
    );

    // Parsing Param Groups (Daftar Dasar Hukum)
    $bullets_data = vc_param_group_parse_atts( $atts['c2_bullets'] );
    if ( empty( $bullets_data ) || ! is_array( $bullets_data ) ) {
        $bullets_data = $dummy_bullets;
    }

    // Parsing URL Tombol
    $link_btn = ( '||' !== $atts['c2_btn_url'] ) ? vc_build_link( $atts['c2_btn_url'] ) : '';
    $a_href   = ! empty( $link_btn['url'] ) ? $link_btn['url'] : '#';
    $a_target = ! empty( $link_btn['target'] ) ? ' target="' . esc_attr( trim( $link_btn['target'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-profil-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-profil-container">
            <div class="profil-grid">
                
                <!-- CARD 1: PROFIL PPID (Kiri) -->
                <div class="profil-card card-light">
                    <h2 class="profil-title"><?php echo esc_html( $atts['c1_title'] ); ?></h2>
                    <div class="profil-text-content">
                        <?php echo $profil_content; ?>
                    </div>
                </div>

                <!-- CARD 2: DASAR HUKUM (Kanan) -->
                <div class="profil-card card-dark">
                    <div class="card-dark-top">
                        <div class="profil-icon icon-yellow-transparent">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h3 class="card-dark-title"><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                        
                        <ul class="profil-check-list">
                            <?php foreach ( $bullets_data as $bullet ) : 
                                $text = isset( $bullet['text'] ) ? $bullet['text'] : '';
                                if ( empty( $text ) ) continue;
                            ?>
                                <li>
                                    <i class="fas fa-check"></i>
                                    <span><?php echo wp_kses_post( $text ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <?php if ( ! empty( $atts['c2_btn_text'] ) ) : ?>
                        <div class="card-dark-footer">
                            <a href="<?php echo esc_url( $a_href ); ?>" class="btn-cta-kuning"<?php echo $a_target; ?>>
                                <?php echo esc_html( $atts['c2_btn_text'] ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_profil_profil_element' );
function ppic_register_ppid_profil_profil_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_bullets = array(
        array( 'text' => 'Undang-Undang No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik' ),
        array( 'text' => 'Peraturan Menteri Perhubungan No. PM 46 Tahun 2018' ),
        array( 'text' => 'Keputusan Menteri Perhubungan No. KP 117 Tahun 2022 tentang SOP PPID' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Profil - Penjelasan', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_profil_profil',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-text-page',
            'params'   => array(
                // KARTU 1 (KIRI)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu Kiri', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Profil PPID',
                    'group'      => __( 'Kartu Kiri (Profil)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea_html',
                    'heading'    => __( 'Isi Teks Profil', 'ppic-custom-element' ),
                    'param_name' => 'content',
                    'value'      => '<p>Keterbukaan informasi merupakan salah satu pilar penting untuk mendukung pelaksanaan tata pemerintahan yang baik (<em>good governance</em>). Penerapan keterbukaan informasi publik di Indonesia didasari atas dasar hukum Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik (UU KIP).</p><p>Sebagai institusi pelayanan publik yang memiliki core business di bidang pendidikan, Politeknik Penerbangan Indonesia Curug (PPI Curug) wajib memberikan akses terhadap keterbukaan informasi publik.</p><p>PPI Curug sebagai Unit Pelaksana Teknis di bawah naungan Kementerian Perhubungan wajib memberikan pelayanan informasi yang efisien, akurat, dan tidak menyesatkan.</p><p>Sebagai bentuk komitmen, PPI Curug telah membentuk Pejabat Pengelola Informasi dan Dokumentasi (PPID) untuk Tahun 2024 melalui Surat Keputusan: KP. PPIC 167 Tahun 2024 tentang PPID Politeknik Penerbangan Indonesia Curug.</p>',
                    'group'      => __( 'Kartu Kiri (Profil)', 'ppic-custom-element' ),
                ),

                // KARTU 2 (KANAN)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu Kanan', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Dasar Hukum Utama',
                    'group'      => __( 'Kartu Kanan (Hukum)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Dasar Hukum', 'ppic-custom-element' ),
                    'param_name' => 'c2_bullets',
                    'value'      => urlencode( wp_json_encode( $dummy_bullets ) ),
                    'group'      => __( 'Kartu Kanan (Hukum)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Teks Aturan', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Aksi', 'ppic-custom-element' ),
                    'param_name' => 'c2_btn_text',
                    'value'      => 'Lihat Selengkapnya',
                    'group'      => __( 'Kartu Kanan (Hukum)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'c2_btn_url',
                    'value'      => 'url:https%3A%2F%2Fppicurug.ac.id%2Fdasar-hukum-dan-peraturan-perundang-undangan%2F|target:_blank',
                    'group'      => __( 'Kartu Kanan (Hukum)', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
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