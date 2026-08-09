<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_testimoni', 'ppic_prodi_testimoni_render' );
function ppic_prodi_testimoni_render( $atts ) {

    $dummy_testimoni = array(
        array( 'quote' => 'Kurikulum TMB sangat aplikatif. Saya langsung bisa bekerja di bandara setelah lulus karena selama kuliah kami banyak praktik langsung dengan peralatan nyata. Fasilitas workshop dan lab sangat mendukung.', 'name' => 'Rahmat Ardiansyah', 'role' => 'Teknisi Mekanikal, Bandara Soekarno-Hatta', 'avatar' => 'RA' ),
        array( 'quote' => 'Dosen-dosen TMB bukan hanya pengajar, tapi juga praktisi yang berpengalaman. Mereka membimbing kami tidak hanya dari sisi teori, tapi juga etos kerja dan kedisiplinan yang sangat dibutuhkan di industri penerbangan.', 'name' => 'Siti Nurjanah', 'role' => 'Auditor Keselamatan, PT. Angkasa Pura Indonesia', 'avatar' => 'SN' ),
        array( 'quote' => 'Saya sangat bersyukur memilih TMB. Selain ijazah, saya juga mendapatkan sertifikat kompetensi yang diakui Kemenhub. Ini menjadi nilai tambah yang sangat berarti saat melamar pekerjaan.', 'name' => 'Dwiki Putra', 'role' => 'Teknisi Alat Berat, Bandara Kertajati', 'avatar' => 'DP' ),
        array( 'quote' => 'Pengalaman praktik di General Workshop dan laboratorium AC, Water & Pump, serta PLC sangat membekali saya. Saya merasa percaya diri menghadapi tantangan di dunia kerja karena sudah terbiasa dengan peralatan industri.', 'name' => 'Muhammad Alif', 'role' => 'Insinyur Pemeliharaan, Bandara Juanda', 'avatar' => 'MA' ),
    );
    
    $atts = shortcode_atts(
        array(
            'section_id' => 'testimoni',
            'title'      => 'Testimoni Alumni',
            'title_icon' => 'fas fa-comment-dots',
            'subtitle'   => 'Apa kata para alumni TMB tentang pengalaman mereka di Program Studi Teknik Mekanikal Bandar Udara.',
            'items'      => urlencode( wp_json_encode( $dummy_testimoni ) ),
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-testimoni-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater testimoni
    $testimoni_items = vc_param_group_parse_atts( $atts['items'] );
    if ( ! is_array( $testimoni_items ) ) {
        $testimoni_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-testimoni-container">
            
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

            <div class="ppic-testimoni-grid">
                <?php if ( ! empty( $testimoni_items ) ) : ?>
                    <?php foreach ( $testimoni_items as $item ) : 
                        $quote  = isset( $item['quote'] ) ? trim( $item['quote'] ) : '';
                        $name   = isset( $item['name'] ) ? trim( $item['name'] ) : '';
                        $role   = isset( $item['role'] ) ? trim( $item['role'] ) : '';
                        $avatar = isset( $item['avatar'] ) ? trim( $item['avatar'] ) : '??';
                        
                        if ( empty( $name ) ) continue;
                    ?>
                        <div class="ppic-testimoni-card">
                            <p class="quote">"<?php echo esc_html( $quote ); ?>"</p>
                            <div class="author">
                                <div class="avatar"><?php echo esc_html( $avatar ); ?></div>
                                <div class="info">
                                    <div class="name"><?php echo esc_html( $name ); ?></div>
                                    <div class="role"><?php echo esc_html( $role ); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="grid-column: 1 / -1; text-align:center; color:#64748b;">Data testimoni belum ditambahkan.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_testimoni_element' );
function ppic_register_prodi_testimoni_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_testimoni = array(
        array( 'quote' => 'Kurikulum TMB sangat aplikatif. Saya langsung bisa bekerja di bandara setelah lulus karena selama kuliah kami banyak praktik langsung dengan peralatan nyata. Fasilitas workshop dan lab sangat mendukung.', 'name' => 'Rahmat Ardiansyah', 'role' => 'Teknisi Mekanikal, Bandara Soekarno-Hatta', 'avatar' => 'RA' ),
        array( 'quote' => 'Dosen-dosen TMB bukan hanya pengajar, tapi juga praktisi yang berpengalaman. Mereka membimbing kami tidak hanya dari sisi teori, tapi juga etos kerja dan kedisiplinan yang sangat dibutuhkan di industri penerbangan.', 'name' => 'Siti Nurjanah', 'role' => 'Auditor Keselamatan, PT. Angkasa Pura Indonesia', 'avatar' => 'SN' ),
        array( 'quote' => 'Saya sangat bersyukur memilih TMB. Selain ijazah, saya juga mendapatkan sertifikat kompetensi yang diakui Kemenhub. Ini menjadi nilai tambah yang sangat berarti saat melamar pekerjaan.', 'name' => 'Dwiki Putra', 'role' => 'Teknisi Alat Berat, Bandara Kertajati', 'avatar' => 'DP' ),
        array( 'quote' => 'Pengalaman praktik di General Workshop dan laboratorium AC, Water & Pump, serta PLC sangat membekali saya. Saya merasa percaya diri menghadapi tantangan di dunia kerja karena sudah terbiasa dengan peralatan industri.', 'name' => 'Muhammad Alif', 'role' => 'Insinyur Pemeliharaan, Bandara Juanda', 'avatar' => 'MA' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Testimoni', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_testimoni',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-testimonial',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Target Navigasi)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'testimoni',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Testimoni Alumni',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-comment-dots',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Subtitle', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Apa kata para alumni TMB tentang pengalaman mereka di Program Studi Teknik Mekanikal Bandar Udara.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                
                // REPEATER TESTIMONI
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Testimoni', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_testimoni ) ),
                    'group'       => __( 'Data Testimoni', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textarea', 'heading' => 'Isi Testimoni', 'param_name' => 'quote'),
                        array('type' => 'textfield', 'heading' => 'Nama Alumni', 'param_name' => 'name', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Jabatan / Pekerjaan', 'param_name' => 'role'),
                        array('type' => 'textfield', 'heading' => 'Inisial Avatar (Max 2 Huruf)', 'param_name' => 'avatar', 'value' => '??'),
                    ),
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