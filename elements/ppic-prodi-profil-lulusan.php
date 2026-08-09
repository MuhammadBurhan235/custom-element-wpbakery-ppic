<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_profil_lulusan', 'ppic_prodi_profil_lulusan_render' );
function ppic_prodi_profil_lulusan_render( $atts ) {
    
    // 1. KITA PINDAHKAN DATA DUMMY KE SINI AGAR FRONTEND BISA MEMBACANYA
    $dummy_profil = array(
        array( 
            'icon'  => 'fas fa-user-tie', 
            'title' => 'Personel Mekanikal Bandar Udara', 
            'desc'  => 'Melakukan pengoperasian, pemeliharaan, perbaikan, analisa kerusakan, perencanaan, dan evaluasi peralatan mekanikal bandar udara.' 
        ),
        array( 
            'icon'  => 'fas fa-bolt', 
            'title' => 'Teknisi Gedung Bidang Mekanikal, Kelistrikan, Pompa dan Pemipaan (MEP)', 
            'desc'  => 'Melakukan pengoperasian, pemeliharaan, perbaikan, analisa kerusakan, perencanaan, evaluasi dan desain di bidang mekanik, listrik, pompa dan pemipaan.' 
        ),
        array( 
            'icon'  => 'fas fa-tractor', 
            'title' => 'Teknisi Mekanik Alat Berat', 
            'desc'  => 'Melakukan pemeliharaan dan gangguan penanganan kerusakan (troubleshooting) pada alat berat.' 
        ),
        array( 
            'icon'  => 'fas fa-clipboard-check', 
            'title' => 'Auditor Internal (Pelaksana Sertifikasi)', 
            'desc'  => 'Melakukan pengukuran dan penilaian terhadap sistem dan prosedur keselamatan serta pelayanan bidang mekanikal.' 
        ),
    );

    $atts = shortcode_atts(
        array(
            'section_id' => 'profil-lulusan',
            'title'      => 'Profil Lulusan',
            'title_icon' => 'fas fa-users',
            'subtitle'   => 'Lulusan Program Studi Teknik Mekanikal Bandar Udara memiliki kompetensi yang siap pakai di dunia industri penerbangan dan bidang terkait.',
            // 2. MASUKKAN DATA DUMMY SEBAGAI DEFAULT VALUE
            'items'      => urlencode( wp_json_encode( $dummy_profil ) ),
            'el_class'   => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-profil-lulusan-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater profil
    $profil_items = vc_param_group_parse_atts( $atts['items'] );
    if ( ! is_array( $profil_items ) ) {
        $profil_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-profil-container">
            
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

            <div class="ppic-profil-grid">
                <?php if ( ! empty( $profil_items ) ) : ?>
                    <?php foreach ( $profil_items as $item ) : 
                        $icon  = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-user-tie';
                        $title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                        $desc  = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                        
                        if ( empty( $title ) ) continue;
                    ?>
                        <div class="ppic-profil-card">
                            <h4>
                                <i class="<?php echo esc_attr( $icon ); ?>"></i> 
                                <span><?php echo esc_html( $title ); ?></span>
                            </h4>
                            <?php if ( ! empty( $desc ) ) : ?>
                                <p><?php echo wp_kses_post( $desc ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="grid-column: 1 / -1; text-align:center; color:#64748b;">Data profil lulusan belum ditambahkan.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_profil_lulusan_element' );
function ppic_register_prodi_profil_lulusan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Data dummy di bawah sini tetap dipertahankan untuk editor backend WPBakery
    $dummy_profil = array(
        array( 
            'icon'  => 'fas fa-user-tie', 
            'title' => 'Personel Mekanikal Bandar Udara', 
            'desc'  => 'Melakukan pengoperasian, pemeliharaan, perbaikan, analisa kerusakan, perencanaan, dan evaluasi peralatan mekanikal bandar udara.' 
        ),
        array( 
            'icon'  => 'fas fa-bolt', 
            'title' => 'Teknisi Gedung Bidang Mekanikal, Kelistrikan, Pompa dan Pemipaan (MEP)', 
            'desc'  => 'Melakukan pengoperasian, pemeliharaan, perbaikan, analisa kerusakan, perencanaan, evaluasi dan desain di bidang mekanik, listrik, pompa dan pemipaan.' 
        ),
        array( 
            'icon'  => 'fas fa-tractor', 
            'title' => 'Teknisi Mekanik Alat Berat', 
            'desc'  => 'Melakukan pemeliharaan dan gangguan penanganan kerusakan (troubleshooting) pada alat berat.' 
        ),
        array( 
            'icon'  => 'fas fa-clipboard-check', 
            'title' => 'Auditor Internal (Pelaksana Sertifikasi)', 
            'desc'  => 'Melakukan pengukuran dan penilaian terhadap sistem dan prosedur keselamatan serta pelayanan bidang mekanikal.' 
        ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Profil Lulusan', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_profil_lulusan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-businessman',
            'params'   => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'profil-lulusan',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Profil Lulusan',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-users',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Lulusan Program Studi Teknik Mekanikal Bandar Udara memiliki kompetensi yang siap pakai di dunia industri penerbangan dan bidang terkait.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Profil Profesi/Karier', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_profil ) ),
                    'group'       => __( 'Data Profil', 'ppic-custom-element' ),
                    'params'      => array(
                        array(
                            'type'        => 'textfield', 
                            'heading'     => 'Nama Profesi / Karier', 
                            'param_name'  => 'title', 
                            'admin_label' => true
                        ),
                        array(
                            'type'        => 'textfield', 
                            'heading'     => 'Ikon FontAwesome', 
                            'param_name'  => 'icon', 
                            'value'       => 'fas fa-user-tie'
                        ),
                        array(
                            'type'        => 'textarea', 
                            'heading'     => 'Deskripsi Profesi', 
                            'param_name'  => 'desc'
                        ),
                    ),
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