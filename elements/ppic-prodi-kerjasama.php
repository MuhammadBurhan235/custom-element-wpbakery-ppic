<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_kerjasama', 'ppic_prodi_kerjasama_render' );
function ppic_prodi_kerjasama_render( $atts ) {

    $dummy_mitra = array(
        array( 'title' => 'PT. Angkasa Pura Indonesia', 'desc' => 'Kerjasama dalam pemenuhan kebutuhan SDM dan pengembangan kompetensi di bidang jasa bandar udara.' ),
        array( 'title' => 'Kementerian Perhubungan RI', 'desc' => 'Regulasi dan standardisasi kompetensi personel bandar udara.' ),
        array( 'title' => 'Asosiasi Profesi & Ikatan Alumni', 'desc' => 'Jaringan profesional dan pengembangan karier.' ),
        array( 'title' => 'Program Studi Sejenis', 'desc' => 'Kolaborasi akademik dan riset di bidang mekanikal bandar udara.' ),
    );

    $atts = shortcode_atts(
        array(
            'section_id'   => 'kerjasama',
            'title'        => 'Kerja Sama Industri',
            'title_icon'   => 'fas fa-handshake',
            'subtitle'     => 'Program Studi TMB menjalin kemitraan strategis dengan berbagai industri penerbangan dan perusahaan terkait untuk mendukung pengembangan kompetensi mahasiswa.',
            
            // BAGIAN KIRI
            'mitra_title'  => 'Mitra Strategis',
            'mitra_intro'  => 'Program studi Teknik Mekanikal Bandar Udara melakukan kerjasama dengan industri dalam aspek <strong>Pemenuhan Kebutuhan dan Pengembangan Sumber Daya Manusia</strong> di bidang jasa bandar udara.',
            'mitra_items'  => urlencode( wp_json_encode( $dummy_mitra ) ),
            
            // BAGIAN KANAN (KARTU HIGHLIGHT)
            'logo_image'   => '',
            'logo_title'   => 'PT. Angkasa Pura Indonesia',
            'logo_desc'    => 'Mitra utama dalam pengembangan SDM jasa bandar udara',
            'logo_tags'    => 'PKL, Penelitian Terapan, Serapan Alumni',
            
            'el_class'     => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-kerjasama-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater mitra (Bagian Kiri)
    $mitra_list = vc_param_group_parse_atts( $atts['mitra_items'] );
    if ( ! is_array( $mitra_list ) ) $mitra_list = array();

    // Gambar Logo (Bagian Kanan)
    // Fallback menggunakan logo InJourney Airports dari mock up HTML
    $logo_url = 'https://lh3.googleusercontent.com/d/1vmE_rR_ac0MqZijOSI323zYg8kWOQFZM'; 
    if ( ! empty( $atts['logo_image'] ) ) {
        $img = wp_get_attachment_image_url( $atts['logo_image'], 'medium' );
        if ( $img ) $logo_url = $img;
    }

    // Parse Tags (Dipisah koma)
    $tags_array = array();
    if ( ! empty( $atts['logo_tags'] ) ) {
        $tags_raw = explode( ',', $atts['logo_tags'] );
        foreach ( $tags_raw as $tag ) {
            $tag = trim( $tag );
            if ( ! empty( $tag ) ) {
                $tags_array[] = $tag;
            }
        }
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-kerjasama-container">
            
            <h2 class="ppic-section-title">
                <?php if ( ! empty( $atts['title_icon'] ) ) : ?>
                    <i class="<?php echo esc_attr( $atts['title_icon'] ); ?>"></i> 
                <?php endif; ?>
                <?php echo esc_html( $atts['title'] ); ?>
            </h2>
            
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-section-sub">
                    <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                </p>
            <?php endif; ?>

            <div class="ppic-kerjasama-grid">
                
                <!-- KIRI: Daftar Mitra -->
                <div class="ppic-kerjasama-text">
                    <h4>
                        <i class="fas fa-building"></i> 
                        <?php echo esc_html( $atts['mitra_title'] ); ?>
                    </h4>
                    
                    <?php if ( ! empty( $atts['mitra_intro'] ) ) : ?>
                        <p class="kerjasama-intro"><?php echo wp_kses_post( $atts['mitra_intro'] ); ?></p>
                    <?php endif; ?>

                    <?php if ( ! empty( $mitra_list ) ) : ?>
                        <ul class="ppic-mitra-list">
                            <?php foreach ( $mitra_list as $item ) : 
                                $nama = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                                $desc = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                                if ( empty( $nama ) ) continue;
                            ?>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <div>
                                        <strong><?php echo esc_html( $nama ); ?></strong> 
                                        <?php if ( ! empty( $desc ) ) : ?>
                                            &mdash; <?php echo esc_html( $desc ); ?>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- KANAN: Highlight Card Mitra -->
                <div class="ppic-kerjasama-logo-card">
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $atts['logo_title'] ); ?>" loading="lazy" />
                    
                    <?php if ( ! empty( $atts['logo_title'] ) ) : ?>
                        <h5 class="logo-title"><?php echo esc_html( $atts['logo_title'] ); ?></h5>
                    <?php endif; ?>

                    <?php if ( ! empty( $atts['logo_desc'] ) ) : ?>
                        <p class="logo-desc"><?php echo esc_html( $atts['logo_desc'] ); ?></p>
                    <?php endif; ?>

                    <?php if ( ! empty( $tags_array ) ) : ?>
                        <div class="logo-tags">
                            <?php foreach ( $tags_array as $tag ) : ?>
                                <span class="tag-pill"><?php echo esc_html( $tag ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_kerjasama_element' );
function ppic_register_prodi_kerjasama_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_mitra = array(
        array( 'title' => 'PT. Angkasa Pura Indonesia', 'desc' => 'Kerjasama dalam pemenuhan kebutuhan SDM dan pengembangan kompetensi di bidang jasa bandar udara.' ),
        array( 'title' => 'Kementerian Perhubungan RI', 'desc' => 'Regulasi dan standardisasi kompetensi personel bandar udara.' ),
        array( 'title' => 'Asosiasi Profesi & Ikatan Alumni', 'desc' => 'Jaringan profesional dan pengembangan karier.' ),
        array( 'title' => 'Program Studi Sejenis', 'desc' => 'Kolaborasi akademik dan riset di bidang mekanikal bandar udara.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Kerjasama', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_kerjasama',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-groups',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'kerjasama',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Kerja Sama Industri',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-handshake',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Program Studi TMB menjalin kemitraan strategis dengan berbagai industri penerbangan dan perusahaan terkait untuk mendukung pengembangan kompetensi mahasiswa.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),

                // BAGIAN KIRI
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Daftar Mitra', 'ppic-custom-element' ),
                    'param_name'  => 'mitra_title',
                    'value'       => 'Mitra Strategis',
                    'group'       => __( 'Daftar Mitra (Kiri)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Pengantar Daftar Mitra', 'ppic-custom-element' ),
                    'param_name'  => 'mitra_intro',
                    'value'       => 'Program studi Teknik Mekanikal Bandar Udara melakukan kerjasama dengan industri dalam aspek <strong>Pemenuhan Kebutuhan dan Pengembangan Sumber Daya Manusia</strong> di bidang jasa bandar udara.',
                    'description' => __( 'Mendukung tag HTML &lt;strong&gt; untuk teks tebal.', 'ppic-custom-element' ),
                    'group'       => __( 'Daftar Mitra (Kiri)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Instansi / Mitra', 'ppic-custom-element' ),
                    'param_name'  => 'mitra_items',
                    'value'       => urlencode( wp_json_encode( $dummy_mitra ) ),
                    'group'       => __( 'Daftar Mitra (Kiri)', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textfield', 'heading' => 'Nama Instansi', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textarea', 'heading' => 'Keterangan Kerjasama', 'param_name' => 'desc'),
                    ),
                ),

                // BAGIAN KANAN (KARTU HIGHLIGHT)
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Logo Mitra Utama', 'ppic-custom-element' ),
                    'param_name'  => 'logo_image',
                    'group'       => __( 'Kartu Highlight (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Nama Mitra Utama', 'ppic-custom-element' ),
                    'param_name'  => 'logo_title',
                    'value'       => 'PT. Angkasa Pura Indonesia',
                    'group'       => __( 'Kartu Highlight (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Keterangan Singkat', 'ppic-custom-element' ),
                    'param_name'  => 'logo_desc',
                    'value'       => 'Mitra utama dalam pengembangan SDM jasa bandar udara',
                    'group'       => __( 'Kartu Highlight (Kanan)', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Label / Tag Fitur Kerjasama', 'ppic-custom-element' ),
                    'param_name'  => 'logo_tags',
                    'value'       => 'PKL, Penelitian Terapan, Serapan Alumni',
                    'description' => __( 'Pisahkan setiap label dengan tanda koma (,). Ini akan otomatis menjadi tombol oval abu-abu.', 'ppic-custom-element' ),
                    'group'       => __( 'Kartu Highlight (Kanan)', 'ppic-custom-element' ),
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