<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_rental_kategori', 'ppic_rental_kategori_render' );
function ppic_rental_kategori_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'     => 'Apa Saja yang Bisa Disewa?',
            'desc'      => 'Beragam fasilitas dan aset premium PPI Curug tersedia untuk masyarakat umum, institusi, dan mitra industri. Klik tombol WhatsApp untuk menanyakan harga dan ketersediaan.',
            'wa_number' => '6287778229661', // Nomor default admin sewa
            'categories'=> '',
            'el_id'     => 'kategori',
            'el_class'  => '',
        ),
        $atts
    );

    // Fallback Data Dummy untuk 8 Kategori sesuai HTML dengan Link Asli
    $dummy_categories = array(
        array(
            'title'   => 'Simulator Penerbangan',
            'desc'    => 'Full Flight Simulator Boeing 737NG & Airbus A320, FTD, IPT, ATC simulator, dan trainer penerbangan kelas dunia.',
            'icon'    => 'fas fa-plane',
            'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/Simulator142.jpg',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa SIMULATOR PENERBANGAN. Mohon info harga dan ketersediaan.'
        ),
        array(
            'title'   => 'Gedung & Ruangan',
            'desc'    => 'Gedung Serbaguna (kapasitas 1000+), Auditorium, ruang kelas AC, ruang rapat, hanggar pesawat, dan lahan perkantoran.',
            'icon'    => 'fas fa-building',
            'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa GEDUNG & RUANGAN. Mohon info harga dan ketersediaan.'
        ),
        array(
            'title'   => 'Pesawat Latih',
            'desc'    => 'Piper Seneca, Archer, Warrior, Helikopter Bell 206. Opsi Dry (tanpa BBM/pilot) atau Wet (termasuk BBM & pilot).',
            'icon'    => 'fas fa-fighter-jet',
            'img_url' => 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa PESAWAT LATIH. Mohon info harga dan ketersediaan.'
        ),
        array(
            'title'   => 'Asrama & Olahraga',
            'desc'    => 'Asrama kapasitas 2-6 orang, lapangan sepak bola, basket, voli, bulutangkis, kolam renang, outbond.',
            'icon'    => 'fas fa-swimmer',
            'img_url' => 'https://lh3.googleusercontent.com/d/1qZG13kjmNYVXXlgDpcY1BN0-k26qJ5jb',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa ASRAMA & FASILITAS OLAHRAGA. Mohon info harga.'
        ),
        array(
            'title'   => 'Peralatan & Inspeksi',
            'desc'    => 'Weighing pesawat, IFR, swing compass, inspeksi 50/100 jam, overhaul, lab uji material, survey pemetaan.',
            'icon'    => 'fas fa-tools',
            'img_url' => 'https://aau.ac.id/wp-content/uploads/2025/02/IMG-20250220-WA0060-1024x682.jpg',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa PERALATAN & JASA INSPEKSI. Mohon info harga.'
        ),
        array(
            'title'   => 'Klinik & Kesehatan',
            'desc'    => 'Medical Check Up, poli umum & gigi, ambulance, vaksinasi, tindakan medis, dan layanan kesehatan terpadu.',
            'icon'    => 'fas fa-stethoscope',
            'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa KLINIK & LAYANAN KESEHATAN. Mohon info harga.'
        ),
        array(
            'title'   => 'Transportasi',
            'desc'    => 'Sewa bus AC kapasitas 14, 27, 46 seat untuk kegiatan di dalam kota (termasuk BBM terbatas).',
            'icon'    => 'fas fa-bus',
            'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa SARANA TRANSPORTASI (bus). Mohon info harga.'
        ),
        array(
            'title'   => 'Penelitian & Publikasi',
            'desc'    => 'Publikasi jurnal Sinta, review naskah, HAKI, pemanfaatan paten, jasa tenaga ahli.',
            'icon'    => 'fas fa-flask',
            'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg',
            'wa_msg'  => 'Halo PPI Curug, saya mau tanya info sewa PENELITIAN & PUBLIKASI. Mohon info harga.'
        ),
    );

    // Parsing Param Group Kategoris
    $categories_data = vc_param_group_parse_atts( $atts['categories'] );
    if ( empty( $categories_data ) || ! is_array( $categories_data ) ) {
        $categories_data = $dummy_categories;
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'kategori-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    $no_wa = preg_replace('/[^0-9]/', '', $atts['wa_number']); // Bersihkan format nomor WA

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container kategori-container">
            
            <!-- Header Section -->
            <div class="kategori-header">
                <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <!-- Grid Kategori -->
            <div class="category-grid">
                <?php foreach ( $categories_data as $cat ) : 
                    $title   = isset( $cat['title'] ) ? $cat['title'] : '';
                    $desc    = isset( $cat['desc'] ) ? $cat['desc'] : '';
                    $icon    = isset( $cat['icon'] ) ? $cat['icon'] : 'fas fa-box';
                    $wa_msg  = isset( $cat['wa_msg'] ) ? $cat['wa_msg'] : 'Halo PPI Curug, saya ingin bertanya tentang layanan sewa.';
                    
                    // URL WhatsApp Dinamis
                    $wa_url = 'https://wa.me/' . $no_wa . '?text=' . rawurlencode( $wa_msg );

                    // Prioritaskan gambar dari Media Library, jika kosong pakai fallback URL aslinya
                    $img_src = isset( $cat['img_url'] ) ? $cat['img_url'] : '';
                    if ( ! empty( $cat['image'] ) ) {
                        $lib_src = wp_get_attachment_image_url( $cat['image'], 'large' );
                        if ( $lib_src ) $img_src = $lib_src;
                    }

                    if ( empty( $title ) ) continue;
                ?>
                    <div class="category-card">
                        <div class="card-img">
                            <?php if ( ! empty( $img_src ) ) : ?>
                                <img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
                            <?php else : ?>
                                <div class="card-img-placeholder">PPI Curug</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <div class="card-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            <p><?php echo esc_html( $desc ); ?></p>
                            
                            <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" class="btn-wa" rel="noopener noreferrer">
                                <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
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

add_action( 'vc_before_init', 'ppic_register_rental_kategori_element' );
function ppic_register_rental_kategori_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_categories_vc = array(
        array( 'title' => 'Simulator Penerbangan', 'desc' => 'Full Flight Simulator Boeing 737NG & Airbus A320, FTD, IPT, ATC simulator, dan trainer penerbangan kelas dunia.', 'icon' => 'fas fa-plane', 'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/Simulator142.jpg', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa SIMULATOR PENERBANGAN. Mohon info harga dan ketersediaan.' ),
        array( 'title' => 'Gedung & Ruangan', 'desc' => 'Gedung Serbaguna (kapasitas 1000+), Auditorium, ruang kelas AC, ruang rapat, hanggar pesawat, dan lahan perkantoran.', 'icon' => 'fas fa-building', 'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa GEDUNG & RUANGAN. Mohon info harga dan ketersediaan.' ),
        array( 'title' => 'Pesawat Latih', 'desc' => 'Piper Seneca, Archer, Warrior, Helikopter Bell 206. Opsi Dry (tanpa BBM/pilot) atau Wet (termasuk BBM & pilot).', 'icon' => 'fas fa-fighter-jet', 'img_url' => 'https://web.ppicurug.ac.id/img/d4-pnb/pnb_pesawat.jpg', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa PESAWAT LATIH. Mohon info harga dan ketersediaan.' ),
        array( 'title' => 'Asrama & Olahraga', 'desc' => 'Asrama kapasitas 2-6 orang, lapangan sepak bola, basket, voli, bulutangkis, kolam renang, outbond.', 'icon' => 'fas fa-swimmer', 'img_url' => 'https://lh3.googleusercontent.com/d/1qZG13kjmNYVXXlgDpcY1BN0-k26qJ5jb', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa ASRAMA & FASILITAS OLAHRAGA. Mohon info harga.' ),
        array( 'title' => 'Peralatan & Inspeksi', 'desc' => 'Weighing pesawat, IFR, swing compass, inspeksi 50/100 jam, overhaul, lab uji material, survey pemetaan.', 'icon' => 'fas fa-tools', 'img_url' => 'https://aau.ac.id/wp-content/uploads/2025/02/IMG-20250220-WA0060-1024x682.jpg', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa PERALATAN & JASA INSPEKSI. Mohon info harga.' ),
        array( 'title' => 'Klinik & Kesehatan', 'desc' => 'Medical Check Up, poli umum & gigi, ambulance, vaksinasi, tindakan medis, dan layanan kesehatan terpadu.', 'icon' => 'fas fa-stethoscope', 'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa KLINIK & LAYANAN KESEHATAN. Mohon info harga.' ),
        array( 'title' => 'Transportasi', 'desc' => 'Sewa bus AC kapasitas 14, 27, 46 seat untuk kegiatan di dalam kota (termasuk BBM terbatas).', 'icon' => 'fas fa-bus', 'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa SARANA TRANSPORTASI (bus). Mohon info harga.' ),
        array( 'title' => 'Penelitian & Publikasi', 'desc' => 'Publikasi jurnal Sinta, review naskah, HAKI, pemanfaatan paten, jasa tenaga ahli.', 'icon' => 'fas fa-flask', 'img_url' => 'https://ppicurug.ac.id/wp-content/uploads/2024/08/kelas.jpg', 'wa_msg' => 'Halo PPI Curug, saya mau tanya info sewa PENELITIAN & PUBLIKASI. Mohon info harga.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Rental - Kategori Sewa', 'ppic-custom-element' ),
            'base'     => 'ppic_rental_kategori',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-grid-view',
            'params'   => array(
                // HEADER
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Apa Saja yang Bisa Disewa?',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Beragam fasilitas dan aset premium PPI Curug tersedia untuk masyarakat umum, institusi, dan mitra industri. Klik tombol WhatsApp untuk menanyakan harga dan ketersediaan.',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Nomor WhatsApp Admin', 'ppic-custom-element' ),
                    'param_name'  => 'wa_number',
                    'value'       => '6287778229661',
                    'description' => __( 'Gunakan kode negara, contoh: 62812345678', 'ppic-custom-element' ),
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),

                // DAFTAR KATEGORI
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Kategori Fasilitas', 'ppic-custom-element' ),
                    'param_name' => 'categories',
                    'value'      => urlencode( wp_json_encode( $dummy_categories_vc ) ),
                    'group'      => __( 'Kategori Layanan', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'attach_image', 'heading' => 'Gambar Fasilitas', 'param_name' => 'image', 'description' => 'Kosongkan jika ingin memakai URL fallback bawaan html.'),
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon', 'value' => 'fas fa-box', 'description' => 'Contoh: fas fa-plane, fas fa-building'),
                        array('type' => 'textfield', 'heading' => 'Nama Layanan/Fasilitas', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textarea', 'heading' => 'Deskripsi Fasilitas', 'param_name' => 'desc'),
                        array('type' => 'textarea', 'heading' => 'Template Pesan WhatsApp', 'param_name' => 'wa_msg', 'description' => 'Pesan otomatis yang akan muncul saat user menekan tombol WA.'),
                    ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'kategori',
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