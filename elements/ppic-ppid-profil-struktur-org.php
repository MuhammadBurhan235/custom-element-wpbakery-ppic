<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_profil_struktur', 'ppic_ppid_profil_struktur_render' );
function ppic_ppid_profil_struktur_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Struktur Organisasi PPID',
            'desc'        => 'Berdasarkan Surat Keputusan KP. PPIC 167 Tahun 2024 tentang PPID Politeknik Penerbangan Indonesia Curug',
            'profiles'    => '',
            'btn_text'    => 'Lihat Bagan Struktur Organisasi',
            'chart_image' => '',
            'el_id'       => 'struktur-organisasi',
            'el_class'    => '',
        ),
        $atts
    );

    // Fallback Data Dummy untuk 3 Profil Utama
    $dummy_profiles = array(
        array( 
            'role'      => 'Manager Informasi', 
            'name'      => 'Ryeska Fajar Kusuma', 
            'position'  => 'Kepala Bagian Keuangan dan Umum', 
            'image_url' => 'https://lh3.googleusercontent.com/d/1ARkbd5Od4SCU0Uun_5TPc3qiHrRv7T4K' 
        ),
        array( 
            'role'      => 'PPID Pelaksana UPT', 
            'name'      => 'Capt. Megi H. Helmiadi', 
            'position'  => 'Direktur PPI Curug', 
            'image_url' => 'https://lh3.googleusercontent.com/d/1nbcqS6CA9IfMeq_YKKCL8U11cbyAESrV' 
        ),
        array( 
            'role'      => 'Manager Dokumentasi', 
            'name'      => 'Ichyu Machmiyana, S.ST., MS.SMA.', 
            'position'  => 'Koordinator Pengembangan Usaha dan Humas', 
            'image_url' => 'https://lh3.googleusercontent.com/d/1GdEhJxcuSCQWAXIMmfrl40FrKaPMcQ6n' 
        ),
    );

    // Parsing Param Group Profil
    $profiles_data = vc_param_group_parse_atts( $atts['profiles'] );
    if ( empty( $profiles_data ) || ! is_array( $profiles_data ) ) {
        $profiles_data = $dummy_profiles;
    }

    // Parsing Gambar Bagan (Fallback ke Google Drive)
    $chart_url = 'https://lh3.googleusercontent.com/d/1G4FHNkd33qGvPKj67MULN0-hG0aBcd3g';
    if ( ! empty( $atts['chart_image'] ) ) {
        $img_src = wp_get_attachment_image_url( $atts['chart_image'], 'full' );
        if ( $img_src ) $chart_url = $img_src;
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-struktur-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // ID unik untuk JS Accordion
    $unique_id = uniqid();

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-struktur-container">
            
            <!-- Header Section -->
            <div class="ppic-struktur-header">
                <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <div class="title-accent"></div>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <!-- Grid 3 Kolom untuk Profil -->
            <div class="struktur-grid">
                <?php foreach ( $profiles_data as $profile ) : 
                    $role     = isset( $profile['role'] ) ? $profile['role'] : '';
                    $name     = isset( $profile['name'] ) ? $profile['name'] : '';
                    $position = isset( $profile['position'] ) ? $profile['position'] : '';
                    
                    // Prioritaskan gambar dari Media Library, jika tidak ada pakai fallback URL
                    $img_profile = isset( $profile['image_url'] ) ? $profile['image_url'] : '';
                    if ( ! empty( $profile['image'] ) ) {
                        $lib_src = wp_get_attachment_image_url( $profile['image'], 'large' );
                        if ( $lib_src ) $img_profile = $lib_src;
                    }
                ?>
                    <div class="struktur-card">
                        <div class="profile-photo-wrapper">
                            <?php if ( ! empty( $img_profile ) ) : ?>
                                <img src="<?php echo esc_url( $img_profile ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy" />
                            <?php else : ?>
                                <div class="photo-placeholder"><i class="fas fa-user"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-role"><?php echo esc_html( $role ); ?></h3>
                            <div class="profile-name"><?php echo esc_html( $name ); ?></div>
                            <div class="profile-position"><?php echo esc_html( $position ); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Accordion Button untuk Bagan Organisasi -->
            <button class="struktur-accordion-btn" id="toggleBtn-<?php echo esc_attr( $unique_id ); ?>">
                <span><?php echo esc_html( $atts['btn_text'] ); ?></span>
                <i class="fas fa-chevron-down transition-icon" id="toggleIcon-<?php echo esc_attr( $unique_id ); ?>"></i>
            </button>

            <!-- Kontainer Gambar Horizontal (Default Sembunyi) -->
            <div class="struktur-image-collapse" id="structureImg-<?php echo esc_attr( $unique_id ); ?>">
                <div class="struktur-image-card">
                    <img src="<?php echo esc_url( $chart_url ); ?>" alt="Bagan Struktur Organisasi" loading="lazy" />
                </div>
            </div>

        </div>
    </section>

    <!-- Script Accordion Khusus -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('toggleBtn-<?php echo esc_js( $unique_id ); ?>');
            var icon = document.getElementById('toggleIcon-<?php echo esc_js( $unique_id ); ?>');
            var imgContainer = document.getElementById('structureImg-<?php echo esc_js( $unique_id ); ?>');
            
            if (btn && imgContainer) {
                btn.addEventListener('click', function() {
                    imgContainer.classList.toggle('show');
                    
                    if (imgContainer.classList.contains('show')) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    } else {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            }
        });
    </script>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_profil_struktur_element' );
function ppic_register_ppid_profil_struktur_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Dummy untuk placeholder WPBakery backend
    $dummy_profiles_vc = array(
        array( 'role' => 'Manager Informasi', 'name' => 'Ryeska Fajar Kusuma', 'position' => 'Kepala Bagian Keuangan dan Umum' ),
        array( 'role' => 'PPID Pelaksana UPT', 'name' => 'Capt. Megi H. Helmiadi', 'position' => 'Direktur PPI Curug' ),
        array( 'role' => 'Manager Dokumentasi', 'name' => 'Ichyu Machmiyana, S.ST., MS.SMA.', 'position' => 'Koordinator Pengembangan Usaha dan Humas' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Profil - Struktur Org', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_profil_struktur',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-groups',
            'params'   => array(
                // HEADER TEKS
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Struktur Organisasi PPID',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi/Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Berdasarkan Surat Keputusan KP. PPIC 167 Tahun 2024 tentang PPID Politeknik Penerbangan Indonesia Curug',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),

                // KARTU PROFIL (PARAM GROUP)
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Profil Pengurus', 'ppic-custom-element' ),
                    'param_name' => 'profiles',
                    'value'      => urlencode( wp_json_encode( $dummy_profiles_vc ) ),
                    'group'      => __( 'Kartu Profil', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'attach_image', 'heading' => 'Foto Profil', 'param_name' => 'image', 'description' => 'Kosongkan untuk pakai gambar bawaan.'),
                        array('type' => 'textfield', 'heading' => 'Peran (Role)', 'param_name' => 'role', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Nama Lengkap', 'param_name' => 'name', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Jabatan Instansi', 'param_name' => 'position'),
                    ),
                ),

                // BAGAN ORGANISASI (AKORDION)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Accordion', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Lihat Bagan Struktur Organisasi',
                    'group'      => __( 'Bagan Struktur', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Bagan Struktur', 'ppic-custom-element' ),
                    'param_name'  => 'chart_image',
                    'description' => __( 'Kosongkan jika ingin menggunakan gambar bawaan dari Google Drive.', 'ppic-custom-element' ),
                    'group'       => __( 'Bagan Struktur', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'struktur-organisasi',
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