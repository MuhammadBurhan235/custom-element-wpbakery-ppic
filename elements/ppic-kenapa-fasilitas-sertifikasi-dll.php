<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_mosaic', 'ppic_kenapa_mosaic_render' );
function ppic_kenapa_mosaic_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // --- FASILITAS ---
            'fas_id'       => 'fasilitas',
            'fas_class'    => '',
            'fas_badge'    => 'Fasilitas Lengkap',
            'fas_title'    => 'Fasilitas Modern',
            'fas_desc'     => 'Dari pesawat latih hingga laboratorium canggih, PPI Curug mempersiapkan taruna dengan standar global. Fasilitas kami dirancang untuk mendukung pembelajaran teori dan praktik secara maksimal.',
            'fas_features' => '',
            'fas_img'      => '',
            'fas_img_url'  => 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=800&q=80',

            // --- SERTIFIKASI ---
            'ser_id'       => 'sertifikasi',
            'ser_class'    => '',
            'ser_badge'    => 'Diakui Dunia',
            'ser_title'    => 'Sertifikasi Internasional',
            'ser_desc'     => 'Satu-satunya ICAO Aviation Security Training Centre (ICAO ASTC) di Indonesia. Program TPU menuju sertifikasi EASA Part 147.',
            'ser_features' => '',
            'ser_quote'    => '“Standar pendidikan dan pelatihan kami memenuhi regulasi nasional dan diakui oleh dunia internasional.”',
            'ser_img'      => '',
            'ser_img_url'  => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80',

            // --- AKREDITASI ---
            'akr_id'       => 'akreditasi',
            'akr_class'    => '',
            'akr_badge'    => 'Terakreditasi Nasional',
            'akr_title'    => 'Akreditasi Unggul',
            'akr_desc'     => 'PPI Curug telah meraih akreditasi <strong>"Baik Sekali"</strong> berdasarkan Keputusan BAN-PT No 31/SK/BAN-PT/Akred/PT/I/2021. Proses audit rutin oleh BAN-PT dan LAM Teknik.',
            'akr_features' => '', // <--- INI SEBELUMNYA TERLEWAT
            'akr_img'      => '',
            'akr_img_url'  => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80',

            // --- KEBERAGAMAN ---
            'keb_id'       => 'keberagaman',
            'keb_class'    => '',
            'keb_badge'    => 'Bhinneka Tunggal Ika',
            'keb_title'    => 'Keberagaman yang Memperkuat',
            'keb_desc'     => 'Taruna dari 34 provinsi dengan latar belakang suku, agama, budaya beragam. Fasilitas ibadah lengkap: Masjid, Gereja, Pura.',
            'keb_icons'    => '',
            'keb_features' => '',
            'keb_quote'    => '“Cewama Eka Tayai — Mengabdi Untuk Kesatuan. Perbedaan adalah kekuatan, kebersamaan fondasi utama.”',
            'keb_img'      => '',
            'keb_img_url'  => 'https://images.unsplash.com/photo-1529070538774-1843cb1665e8?w=800&q=80',
        ),
        $atts
    );

    if ( ! function_exists( 'ppic_get_mosaic_img_url' ) ) {
        function ppic_get_mosaic_img_url( $id, $fallback ) {
            if ( ! empty( $id ) ) {
                $src = wp_get_attachment_image_src( $id, 'large' );
                if ( $src ) return $src[0];
            }
            return $fallback;
        }
    }

    $img_fas = ppic_get_mosaic_img_url( $atts['fas_img'], $atts['fas_img_url'] );
    $img_ser = ppic_get_mosaic_img_url( $atts['ser_img'], $atts['ser_img_url'] );
    $img_akr = ppic_get_mosaic_img_url( $atts['akr_img'], $atts['akr_img_url'] );
    $img_keb = ppic_get_mosaic_img_url( $atts['keb_img'], $atts['keb_img_url'] );

    ob_start(); ?>
    <div class="ppic-mosaic-wrapper">
        <div class="ppic-mosaic-container">

            <div id="<?php echo esc_attr( $atts['fas_id'] ); ?>" class="ppic-mosaic-section <?php echo esc_attr( $atts['fas_class'] ); ?>">
                <div class="mosaic-text-card">
                    <?php if ( ! empty( $atts['fas_badge'] ) ) : ?><div class="mini-badge"><?php echo esc_html( $atts['fas_badge'] ); ?></div><?php endif; ?>
                    <h2 class="mosaic-title"><?php echo esc_html( $atts['fas_title'] ); ?></h2>
                    <p><?php echo wp_kses_post( $atts['fas_desc'] ); ?></p>
                    
                    <?php $fas_feats = vc_param_group_parse_atts( $atts['fas_features'] ); ?>
                    <?php if ( ! empty( $fas_feats ) && is_array( $fas_feats ) ) : ?>
                        <ul class="feature-list">
                            <?php foreach ( $fas_feats as $f ) : ?>
                                <li><i class="<?php echo esc_attr( ! empty( $f['icon'] ) ? $f['icon'] : 'fas fa-check' ); ?>" aria-hidden="true"></i> <span><?php echo esc_html( ! empty( $f['text'] ) ? $f['text'] : '' ); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="mosaic-image-card">
                    <img src="<?php echo esc_url( $img_fas ); ?>" alt="<?php echo esc_attr( $atts['fas_title'] ); ?>" loading="lazy">
                </div>
            </div>

            <div id="<?php echo esc_attr( $atts['ser_id'] ); ?>" class="ppic-mosaic-section is-reverse <?php echo esc_attr( $atts['ser_class'] ); ?>">
                <div class="mosaic-image-card">
                    <img src="<?php echo esc_url( $img_ser ); ?>" alt="<?php echo esc_attr( $atts['ser_title'] ); ?>" loading="lazy">
                </div>
                <div class="mosaic-text-card">
                    <?php if ( ! empty( $atts['ser_badge'] ) ) : ?><div class="mini-badge"><?php echo esc_html( $atts['ser_badge'] ); ?></div><?php endif; ?>
                    <h2 class="mosaic-title"><?php echo esc_html( $atts['ser_title'] ); ?></h2>
                    <p><?php echo wp_kses_post( $atts['ser_desc'] ); ?></p>

                    <?php $ser_feats = vc_param_group_parse_atts( $atts['ser_features'] ); ?>
                    <?php if ( ! empty( $ser_feats ) && is_array( $ser_feats ) ) : ?>
                        <ul class="feature-list">
                            <?php foreach ( $ser_feats as $sf ) : ?>
                                <li><i class="<?php echo esc_attr( ! empty( $sf['icon'] ) ? $sf['icon'] : 'fas fa-check' ); ?>" aria-hidden="true"></i> <span><?php echo esc_html( ! empty( $sf['text'] ) ? $sf['text'] : '' ); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ( ! empty( $atts['ser_quote'] ) ) : ?>
                        <div class="quote-mini">
                            <i class="fas fa-award" aria-hidden="true"></i>
                            <span><?php echo esc_html( $atts['ser_quote'] ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="<?php echo esc_attr( $atts['akr_id'] ); ?>" class="ppic-mosaic-section <?php echo esc_attr( $atts['akr_class'] ); ?>">
                <div class="mosaic-text-card">
                    <?php if ( ! empty( $atts['akr_badge'] ) ) : ?><div class="mini-badge"><?php echo esc_html( $atts['akr_badge'] ); ?></div><?php endif; ?>
                    <h2 class="mosaic-title"><?php echo esc_html( $atts['akr_title'] ); ?></h2>
                    <p><?php echo wp_kses_post( $atts['akr_desc'] ); ?></p>

                    <?php $akr_feats = vc_param_group_parse_atts( $atts['akr_features'] ); ?>
                    <?php if ( ! empty( $akr_feats ) && is_array( $akr_feats ) ) : ?>
                        <ul class="feature-list">
                            <?php foreach ( $akr_feats as $af ) : ?>
                                <li><i class="<?php echo esc_attr( ! empty( $af['icon'] ) ? $af['icon'] : 'fas fa-check' ); ?>" aria-hidden="true"></i> <span><?php echo esc_html( ! empty( $af['text'] ) ? $af['text'] : '' ); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="mosaic-image-card">
                    <img src="<?php echo esc_url( $img_akr ); ?>" alt="<?php echo esc_attr( $atts['akr_title'] ); ?>" loading="lazy">
                </div>
            </div>

            <div id="<?php echo esc_attr( $atts['keb_id'] ); ?>" class="ppic-mosaic-section is-reverse <?php echo esc_attr( $atts['keb_class'] ); ?>">
                <div class="mosaic-image-card">
                    <img src="<?php echo esc_url( $img_keb ); ?>" alt="<?php echo esc_attr( $atts['keb_title'] ); ?>" loading="lazy">
                </div>
                <div class="mosaic-text-card">
                    <?php if ( ! empty( $atts['keb_badge'] ) ) : ?><div class="mini-badge"><?php echo esc_html( $atts['keb_badge'] ); ?></div><?php endif; ?>
                    <h2 class="mosaic-title"><?php echo esc_html( $atts['keb_title'] ); ?></h2>
                    <p><?php echo wp_kses_post( $atts['keb_desc'] ); ?></p>

                    <?php $keb_icons = vc_param_group_parse_atts( $atts['keb_icons'] ); ?>
                    <?php if ( ! empty( $keb_icons ) && is_array( $keb_icons ) ) : ?>
                        <div class="mosaic-keberagaman-icons">
                            <?php foreach ( $keb_icons as $ki ) : ?>
                                <div class="icon-item">
                                    <i class="<?php echo esc_attr( ! empty( $ki['icon'] ) ? $ki['icon'] : 'fas fa-star' ); ?>" aria-hidden="true"></i>
                                    <span><?php echo esc_html( ! empty( $ki['label'] ) ? $ki['label'] : '' ); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php $keb_feats = vc_param_group_parse_atts( $atts['keb_features'] ); ?>
                    <?php if ( ! empty( $keb_feats ) && is_array( $keb_feats ) ) : ?>
                        <ul class="feature-list" style="margin-top: 25px;">
                            <?php foreach ( $keb_feats as $kf ) : ?>
                                <li><i class="<?php echo esc_attr( ! empty( $kf['icon'] ) ? $kf['icon'] : 'fas fa-check' ); ?>" aria-hidden="true"></i> <span><?php echo esc_html( ! empty( $kf['text'] ) ? $kf['text'] : '' ); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ( ! empty( $atts['keb_quote'] ) ) : ?>
                        <div class="quote-mini">
                            <i class="fas fa-quote-left" aria-hidden="true"></i>
                            <span><?php echo esc_html( $atts['keb_quote'] ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_kenapa_mosaic_element' );
function ppic_register_kenapa_mosaic_element() {
    if ( ! function_exists( 'vc_map' ) ) return;

    vc_map( array(
        'name'     => __( 'PPIC Kenapa - Mosaic', 'ppic-custom-element' ),
        'base'     => 'ppic_kenapa_mosaic',
        'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
        'icon'     => 'dashicons dashicons-layout',
        'params'   => array(
            
            /* ================= FASILITAS ================= */
            array('type' => 'textfield', 'heading' => 'Element ID (Auto-scroll #)', 'param_name' => 'fas_id', 'value' => 'fasilitas', 'group' => '1. Fasilitas'),
            array('type' => 'textfield', 'heading' => 'Extra Class Name', 'param_name' => 'fas_class', 'group' => '1. Fasilitas'),
            array('type' => 'textfield', 'heading' => 'Badge Kuning', 'param_name' => 'fas_badge', 'value' => 'Fasilitas Lengkap', 'group' => '1. Fasilitas'),
            array('type' => 'textfield', 'heading' => 'Judul', 'param_name' => 'fas_title', 'value' => 'Fasilitas Modern', 'group' => '1. Fasilitas', 'admin_label' => true),
            array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'fas_desc', 'value' => 'Dari pesawat latih hingga laboratorium canggih, PPI Curug mempersiapkan taruna dengan standar global. Fasilitas kami dirancang untuk mendukung pembelajaran teori dan praktik secara maksimal.', 'group' => '1. Fasilitas'),
            array(
                'type' => 'param_group', 'heading' => 'Daftar Fitur', 'param_name' => 'fas_features', 'group' => '1. Fasilitas',
                'value' => urlencode(wp_json_encode(array(
                    array('icon' => 'fas fa-plane', 'text' => '47 pesawat latih untuk program penerbang & pelatihan teknis'),
                    array('icon' => 'fas fa-building', 'text' => 'Hanggar TPU dengan fasilitas MRO skala nasional'),
                    array('icon' => 'fas fa-desktop', 'text' => 'Full Flight Simulator, ATC 360°, laboratorium CNS'),
                    array('icon' => 'fas fa-microchip', 'text' => 'Lab AGL, Tanah Aspal Beton, Baggage Screening, PKP-PK Mockup'),
                    array('icon' => 'fas fa-dumbbell', 'text' => 'Lapangan sepak bola, basket, kolam renang, pusat kebugaran'),
                    array('icon' => 'fas fa-bed', 'text' => 'Sistem boarding school 24 jam dengan pembinaan karakter'),
                ))),
                'params' => array(
                    array('type' => 'textfield', 'heading' => 'Icon Class', 'param_name' => 'icon'),
                    array('type' => 'textfield', 'heading' => 'Teks Fitur', 'param_name' => 'text', 'admin_label' => true),
                )
            ),
            array('type' => 'attach_image', 'heading' => 'Gambar Visual', 'param_name' => 'fas_img', 'group' => '1. Fasilitas'),

            /* ================= SERTIFIKASI ================= */
            array('type' => 'textfield', 'heading' => 'Element ID (Auto-scroll #)', 'param_name' => 'ser_id', 'value' => 'sertifikasi', 'group' => '2. Sertifikasi'),
            array('type' => 'textfield', 'heading' => 'Extra Class Name', 'param_name' => 'ser_class', 'group' => '2. Sertifikasi'),
            array('type' => 'textfield', 'heading' => 'Badge Kuning', 'param_name' => 'ser_badge', 'value' => 'Diakui Dunia', 'group' => '2. Sertifikasi'),
            array('type' => 'textfield', 'heading' => 'Judul', 'param_name' => 'ser_title', 'value' => 'Sertifikasi Internasional', 'group' => '2. Sertifikasi', 'admin_label' => true),
            array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'ser_desc', 'value' => 'Satu-satunya ICAO Aviation Security Training Centre (ICAO ASTC) di Indonesia. Program TPU menuju sertifikasi EASA Part 147.', 'group' => '2. Sertifikasi'),
            array(
                'type' => 'param_group', 'heading' => 'Daftar Fitur', 'param_name' => 'ser_features', 'group' => '2. Sertifikasi',
                'value' => urlencode(wp_json_encode(array(
                    array('icon' => 'fas fa-globe-asia', 'text' => 'ICAO ASTC – satu-satunya di Indonesia'),
                    array('icon' => 'fas fa-passport', 'text' => 'Persiapan EASA Part 147 untuk TPU'),
                    array('icon' => 'fas fa-chalkboard', 'text' => 'Kurikulum selaras ICAO Trainair Plus'),
                    array('icon' => 'fas fa-check-double', 'text' => 'Diakui 180+ Negara Anggota ICAO')
                ))),
                'params' => array(
                    array('type' => 'textfield', 'heading' => 'Icon Class', 'param_name' => 'icon'),
                    array('type' => 'textfield', 'heading' => 'Teks Fitur', 'param_name' => 'text', 'admin_label' => true),
                )
            ),
            array('type' => 'textarea', 'heading' => 'Teks Quote/Kutipan (Bawah)', 'param_name' => 'ser_quote', 'value' => '“Standar pendidikan dan pelatihan kami memenuhi regulasi nasional dan diakui oleh dunia internasional.”', 'group' => '2. Sertifikasi'),
            array('type' => 'attach_image', 'heading' => 'Gambar Visual', 'param_name' => 'ser_img', 'group' => '2. Sertifikasi'),

            /* ================= AKREDITASI ================= */
            array('type' => 'textfield', 'heading' => 'Element ID (Auto-scroll #)', 'param_name' => 'akr_id', 'value' => 'akreditasi', 'group' => '3. Akreditasi'),
            array('type' => 'textfield', 'heading' => 'Extra Class Name', 'param_name' => 'akr_class', 'group' => '3. Akreditasi'),
            array('type' => 'textfield', 'heading' => 'Badge Kuning', 'param_name' => 'akr_badge', 'value' => 'Terakreditasi Nasional', 'group' => '3. Akreditasi'),
            array('type' => 'textfield', 'heading' => 'Judul', 'param_name' => 'akr_title', 'value' => 'Akreditasi Unggul', 'group' => '3. Akreditasi', 'admin_label' => true),
            array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'akr_desc', 'value' => 'PPI Curug telah meraih akreditasi <strong>"Baik Sekali"</strong> berdasarkan Keputusan BAN-PT No 31/SK/BAN-PT/Akred/PT/I/2021. Proses audit rutin oleh BAN-PT dan LAM Teknik.', 'group' => '3. Akreditasi'),
            array(
                'type' => 'param_group', 'heading' => 'Daftar Fitur', 'param_name' => 'akr_features', 'group' => '3. Akreditasi',
                'value' => urlencode(wp_json_encode(array(
                    array('icon' => 'fas fa-trophy', 'text' => 'Akreditasi "Baik Sekali" dari BAN-PT'),
                    array('icon' => 'fas fa-cogs', 'text' => 'Diaudit secara berkala oleh LAM Teknik'),
                    array('icon' => 'fas fa-check-circle', 'text' => 'Seluruh program studi terakreditasi nasional'),
                    array('icon' => 'fas fa-chart-line', 'text' => 'Komitmen peningkatan mutu berkelanjutan')
                ))),
                'params' => array(
                    array('type' => 'textfield', 'heading' => 'Icon Class', 'param_name' => 'icon'),
                    array('type' => 'textfield', 'heading' => 'Teks Fitur', 'param_name' => 'text', 'admin_label' => true),
                )
            ),
            array('type' => 'attach_image', 'heading' => 'Gambar Visual', 'param_name' => 'akr_img', 'group' => '3. Akreditasi'),

            /* ================= KEBERAGAMAN ================= */
            array('type' => 'textfield', 'heading' => 'Element ID (Auto-scroll #)', 'param_name' => 'keb_id', 'value' => 'keberagaman', 'group' => '4. Keberagaman'),
            array('type' => 'textfield', 'heading' => 'Extra Class Name', 'param_name' => 'keb_class', 'group' => '4. Keberagaman'),
            array('type' => 'textfield', 'heading' => 'Badge Kuning', 'param_name' => 'keb_badge', 'value' => 'Bhinneka Tunggal Ika', 'group' => '4. Keberagaman'),
            array('type' => 'textfield', 'heading' => 'Judul', 'param_name' => 'keb_title', 'value' => 'Keberagaman yang Memperkuat', 'group' => '4. Keberagaman', 'admin_label' => true),
            array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'keb_desc', 'value' => 'Taruna dari 34 provinsi dengan latar belakang suku, agama, budaya beragam. Fasilitas ibadah lengkap: Masjid, Gereja, Pura.', 'group' => '4. Keberagaman'),
            array(
                'type' => 'param_group', 'heading' => 'Ikon Rumah Ibadah', 'param_name' => 'keb_icons', 'group' => '4. Keberagaman',
                'value' => urlencode(wp_json_encode(array(
                    array('icon' => 'fas fa-mosque', 'label' => 'Masjid'),
                    array('icon' => 'fas fa-church', 'label' => 'Gereja'),
                    array('icon' => 'fas fa-gopuram', 'label' => 'Pura')
                ))),
                'params' => array(
                    array('type' => 'textfield', 'heading' => 'Icon Class', 'param_name' => 'icon'),
                    array('type' => 'textfield', 'heading' => 'Nama / Label', 'param_name' => 'label', 'admin_label' => true),
                )
            ),
            array(
                'type' => 'param_group', 'heading' => 'Daftar Fitur Tambahan', 'param_name' => 'keb_features', 'group' => '4. Keberagaman',
                'value' => urlencode(wp_json_encode(array(
                    array('icon' => 'fas fa-hands-praying', 'text' => 'Fasilitas tempat ibadah yang lengkap bagi seluruh civitas'),
                    array('icon' => 'fas fa-globe', 'text' => 'Mahasiswa berasal dari seluruh penjuru nusantara'),
                    array('icon' => 'fas fa-heart', 'text' => 'Budaya saling menghormati')
                ))),
                'params' => array(
                    array('type' => 'textfield', 'heading' => 'Icon Class', 'param_name' => 'icon'),
                    array('type' => 'textfield', 'heading' => 'Teks Fitur', 'param_name' => 'text', 'admin_label' => true),
                )
            ),
            array('type' => 'textarea', 'heading' => 'Teks Quote/Kutipan (Bawah)', 'param_name' => 'keb_quote', 'value' => '“Cewama Eka Tayai — Mengabdi Untuk Kesatuan. Perbedaan adalah kekuatan, kebersamaan fondasi utama.”', 'group' => '4. Keberagaman'),
            array('type' => 'attach_image', 'heading' => 'Gambar Visual', 'param_name' => 'keb_img', 'group' => '4. Keberagaman'),
        )
    ) );
}