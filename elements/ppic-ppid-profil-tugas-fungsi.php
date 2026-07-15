<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_profil_tugas_fungsi', 'ppic_ppid_profil_tugas_fungsi_render' );
function ppic_ppid_profil_tugas_fungsi_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Tugas dan Fungsi PPID',
            'desc'        => 'PPID PPI Curug memiliki tugas untuk menyimpan dan mengelola informasi publik yang berkaitan dengan penyelenggaraan Politeknik Penerbangan Indonesia Curug sebagai Badan Publik Unit Penyelenggara Teknis di bawah Kementerian Perhubungan.',
            
            // KARTU 1 (TUGAS)
            'c1_title'    => 'Tugas PPID',
            'c1_items'    => '',
            
            // KARTU 2 (FUNGSI)
            'c2_title'    => 'Fungsi PPID',
            'c2_items'    => '',
            
            // UMUM
            'el_id'       => 'tugas-fungsi',
            'el_class'    => '',
        ),
        $atts
    );

    // Default Fallback Data untuk Daftar Tugas
    $dummy_tugas = array(
        array( 'text' => 'Menyimpan dan mengelola informasi publik' ),
        array( 'text' => 'Menyediakan pelayanan informasi publik yang cepat, tepat, dan sederhana' ),
        array( 'text' => 'Melakukan pengujian konsekuensi atas informasi yang dikecualikan' ),
        array( 'text' => 'Melakukan pengklasifikasian informasi publik' ),
        array( 'text' => 'Membuat laporan layanan informasi publik secara berkala' ),
    );

    // Default Fallback Data untuk Daftar Fungsi
    $dummy_fungsi = array(
        array( 'text' => 'Pengumpulan, pendokumentasian, dan pengamanan informasi publik' ),
        array( 'text' => 'Pelayanan informasi publik sesuai dengan ketentuan peraturan perundang-undangan' ),
        array( 'text' => 'Pengujian konsekuensi informasi publik yang dikecualikan' ),
        array( 'text' => 'Koordinasi dengan PPID Pembantu dalam penyediaan informasi' ),
    );

    // Parsing Param Groups
    $tugas_data = vc_param_group_parse_atts( $atts['c1_items'] );
    $fungsi_data = vc_param_group_parse_atts( $atts['c2_items'] );

    if ( empty( $tugas_data ) || ! is_array( $tugas_data ) ) $tugas_data = $dummy_tugas;
    if ( empty( $fungsi_data ) || ! is_array( $fungsi_data ) ) $fungsi_data = $dummy_fungsi;

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-tugas-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-tugas-container">
            
            <!-- Header Section -->
            <div class="ppic-tugas-header">
                <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="section-sub"><?php echo esc_html( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <!-- Grid 2 Kolom -->
            <div class="tugas-grid">
                
                <!-- CARD 1: TUGAS PPID -->
                <div class="tugas-card">
                    <div class="tugas-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <h3 class="tugas-title"><?php echo esc_html( $atts['c1_title'] ); ?></h3>
                    
                    <ul class="tugas-list">
                        <?php foreach ( $tugas_data as $item ) : 
                            $text = isset( $item['text'] ) ? $item['text'] : '';
                            if ( empty( $text ) ) continue;
                        ?>
                            <li><?php echo wp_kses_post( $text ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- CARD 2: FUNGSI PPID -->
                <div class="tugas-card">
                    <div class="tugas-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="tugas-title"><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                    
                    <ul class="tugas-list">
                        <?php foreach ( $fungsi_data as $item ) : 
                            $text = isset( $item['text'] ) ? $item['text'] : '';
                            if ( empty( $text ) ) continue;
                        ?>
                            <li><?php echo wp_kses_post( $text ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_profil_tugas_fungsi_element' );
function ppic_register_ppid_profil_tugas_fungsi_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_tugas = array(
        array( 'text' => 'Menyimpan dan mengelola informasi publik' ),
        array( 'text' => 'Menyediakan pelayanan informasi publik yang cepat, tepat, dan sederhana' ),
        array( 'text' => 'Melakukan pengujian konsekuensi atas informasi yang dikecualikan' ),
        array( 'text' => 'Melakukan pengklasifikasian informasi publik' ),
        array( 'text' => 'Membuat laporan layanan informasi publik secara berkala' ),
    );

    $dummy_fungsi = array(
        array( 'text' => 'Pengumpulan, pendokumentasian, dan pengamanan informasi publik' ),
        array( 'text' => 'Pelayanan informasi publik sesuai dengan ketentuan peraturan perundang-undangan' ),
        array( 'text' => 'Pengujian konsekuensi informasi publik yang dikecualikan' ),
        array( 'text' => 'Koordinasi dengan PPID Pembantu dalam penyediaan informasi' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Profil - Tugas & Fungsi', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_profil_tugas_fungsi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-clipboard',
            'params'   => array(
                // HEADER TEKS
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Tugas dan Fungsi PPID',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi/Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'PPID PPI Curug memiliki tugas untuk menyimpan dan mengelola informasi publik yang berkaitan dengan penyelenggaraan Politeknik Penerbangan Indonesia Curug sebagai Badan Publik Unit Penyelenggara Teknis di bawah Kementerian Perhubungan.',
                    'group'      => __( 'Header', 'ppic-custom-element' ),
                ),

                // KARTU 1 (TUGAS)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu Kiri', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Tugas PPID',
                    'group'      => __( 'Kartu Kiri (Tugas)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Tugas', 'ppic-custom-element' ),
                    'param_name' => 'c1_items',
                    'value'      => urlencode( wp_json_encode( $dummy_tugas ) ),
                    'group'      => __( 'Kartu Kiri (Tugas)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Poin Tugas', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),

                // KARTU 2 (FUNGSI)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu Kanan', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Fungsi PPID',
                    'group'      => __( 'Kartu Kanan (Fungsi)', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Fungsi', 'ppic-custom-element' ),
                    'param_name' => 'c2_items',
                    'value'      => urlencode( wp_json_encode( $dummy_fungsi ) ),
                    'group'      => __( 'Kartu Kanan (Fungsi)', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Poin Fungsi', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'tugas-fungsi',
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