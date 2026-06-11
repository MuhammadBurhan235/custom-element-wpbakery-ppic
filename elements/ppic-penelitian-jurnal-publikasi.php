<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_penelitian_jurnal', 'ppic_penelitian_jurnal_render' );
function ppic_penelitian_jurnal_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title_normal'    => 'Jurnal & Publikasi',
            'title_highlight' => 'Ilmiah',
            'desc'            => 'PPI Curug menerbitkan berbagai jurnal ilmiah yang telah terakreditasi nasional dan menjadi wadah bagi akademisi, peneliti, serta praktisi untuk mempublikasikan hasil penelitian dan kajian di bidang aviasi.',
            'journals'        => '',
            'el_id'           => 'jurnal',
            'el_class'        => '',
        ),
        $atts
    );

    // Proses data jurnal
    $journals = vc_param_group_parse_atts( $atts['journals'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-jurnal-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-jurnal-container">
            
            <div class="ppic-jurnal-header">
                <h2 class="ppic-jurnal-title">
                    <?php echo esc_html( $atts['title_normal'] ); ?> 
                    <span><?php echo esc_html( $atts['title_highlight'] ); ?></span>
                </h2>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="ppic-jurnal-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $journals ) && is_array( $journals ) ) : ?>
                <div class="ppic-jurnal-grid">
                    <?php foreach ( $journals as $j ) : 
                        $badge   = isset( $j['badge'] ) ? trim( $j['badge'] ) : 'Jurnal';
                        $title   = isset( $j['title'] ) ? trim( $j['title'] ) : '';
                        $desc    = isset( $j['desc'] ) ? trim( $j['desc'] ) : '';
                        $m1_icon = isset( $j['m1_icon'] ) ? trim( $j['m1_icon'] ) : 'fas fa-calendar-alt';
                        $m1_text = isset( $j['m1_text'] ) ? trim( $j['m1_text'] ) : '';
                        $m2_icon = isset( $j['m2_icon'] ) ? trim( $j['m2_icon'] ) : 'fas fa-check-circle';
                        $m2_text = isset( $j['m2_text'] ) ? trim( $j['m2_text'] ) : '';
                        $btn_txt = isset( $j['btn_text'] ) ? trim( $j['btn_text'] ) : 'Kunjungi Jurnal';
                        $btn_url = isset( $j['btn_link'] ) ? trim( $j['btn_link'] ) : '#';
                        
                        if ( empty( $title ) ) continue;
                        ?>
                        <div class="ppic-jurnal-card">
                            <?php if ( ! empty( $badge ) ) : ?>
                                <div class="jurnal-badge"><?php echo esc_html( $badge ); ?></div>
                            <?php endif; ?>
                            
                            <h4><?php echo esc_html( $title ); ?></h4>
                            <p><?php echo esc_html( $desc ); ?></p>
                            
                            <div class="jurnal-meta">
                                <?php if ( ! empty( $m1_text ) ) : ?>
                                    <span><i class="<?php echo esc_attr( $m1_icon ); ?>" aria-hidden="true"></i> <?php echo esc_html( $m1_text ); ?></span>
                                <?php endif; ?>
                                <?php if ( ! empty( $m2_text ) ) : ?>
                                    <span><i class="<?php echo esc_attr( $m2_icon ); ?>" aria-hidden="true"></i> <?php echo esc_html( $m2_text ); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php echo esc_url( $btn_url ); ?>" class="btn-jurnal-link" target="_blank" rel="noopener noreferrer">
                                <span><?php echo esc_html( $btn_txt ); ?></span>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_penelitian_jurnal_element' );
function ppic_register_penelitian_jurnal_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_journals = array(
        array( 
            'badge' => 'SINTA 2', 'title' => 'Langit Biru: Jurnal Ilmiah Aviasi', 
            'desc' => 'Jurnal ilmiah aviasi yang berisi tulisan bidang penerbangan berupa hasil penelitian dan kajian pustaka. Terakreditasi SINTA 2. Terbit tiga kali setahun.',
            'm1_icon' => 'fas fa-calendar-alt', 'm1_text' => '3x / tahun',
            'm2_icon' => 'fas fa-check-circle', 'm2_text' => 'Terakreditasi SINTA 2',
            'btn_text' => 'Kunjungi Jurnal', 'btn_link' => 'https://journal.ppicurug.ac.id/index.php/jurnal-ilmiah-aviasi'
        ),
        array( 
            'badge' => 'JURNAL BISNIS & OPERASI', 'title' => 'Aviation Business and Operations Journal', 
            'desc' => 'Jurnal hasil penelitian di bidang bisnis, operasi, dan manajemen penerbangan. Blind Review + Turnitin. Terbit Januari & Juli.',
            'm1_icon' => 'fas fa-calendar-alt', 'm1_text' => '2x / tahun',
            'm2_icon' => 'fas fa-users', 'm2_text' => 'Double-Blind Review',
            'btn_text' => 'Kunjungi Jurnal', 'btn_link' => 'https://journal.ppicurug.ac.id/index.php/aboj'
        ),
        array( 
            'badge' => 'SAINS & TEKNOLOGI', 'title' => 'Aviation Science and Technology Journal', 
            'desc' => 'Jurnal ilmiah di bidang sains dan teknologi penerbangan. Proses double-blind review, terbit dua kali setahun.',
            'm1_icon' => 'fas fa-calendar-alt', 'm1_text' => '2x / tahun',
            'm2_icon' => 'fas fa-microscope', 'm2_text' => 'Sains & Teknologi',
            'btn_text' => 'Kunjungi Jurnal', 'btn_link' => 'https://journal.ppicurug.ac.id/index.php/astj'
        ),
        array( 
            'badge' => 'SINTA 5', 'title' => 'JPKM Langit Biru', 
            'desc' => 'Publikasi hasil kegiatan pengabdian masyarakat civitas akademia. Akreditasi SINTA 5. Terbit Maret & September.',
            'm1_icon' => 'fas fa-calendar-alt', 'm1_text' => '2x / tahun',
            'm2_icon' => 'fas fa-check-circle', 'm2_text' => 'Terakreditasi SINTA 5',
            'btn_text' => 'Kunjungi Jurnal', 'btn_link' => 'https://journal.ppicurug.ac.id/index.php/jpkm'
        ),
        array( 
            'badge' => 'TEKNIK MEKANIKAL', 'title' => 'Jurnal Teknik Mekanikal Bandar Udara', 
            'desc' => 'Inovasi dan manajemen mekanikal bandara. Diterbitkan Program Studi Teknik Mekanikal Bandar Udara.',
            'm1_icon' => 'fas fa-calendar-alt', 'm1_text' => '2x / tahun',
            'm2_icon' => 'fas fa-plane', 'm2_text' => 'Spesialis Mekanikal Bandara',
            'btn_text' => 'Kunjungi Jurnal', 'btn_link' => 'https://journal.ppicurug.ac.id/index.php/jtmb'
        ),
        array( 
            'badge' => 'PROSIDING', 'title' => 'Prosiding SNVP', 
            'desc' => 'Prosiding Seminar Nasional Vokasi Penerbangan, forum diseminasi penelitian tahunan bagi akademisi & praktisi.',
            'm1_icon' => 'fas fa-chalkboard-user', 'm1_text' => 'Seminar Tahunan',
            'm2_icon' => 'fas fa-users', 'm2_text' => 'Diseminasi Penelitian Nasional',
            'btn_text' => 'Lihat Prosiding', 'btn_link' => 'https://journal.ppicurug.ac.id/index.php/snvp'
        ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Penelitian - Jurnal', 'ppic-custom-element' ),
            'base'     => 'ppic_penelitian_jurnal',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-book',
            'params'   => array(
                // HEADER
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Biru)', 'ppic-custom-element' ),
                    'param_name' => 'title_normal',
                    'value'      => 'Jurnal & Publikasi',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'Ilmiah',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'PPI Curug menerbitkan berbagai jurnal ilmiah yang telah terakreditasi nasional dan menjadi wadah bagi akademisi, peneliti, serta praktisi untuk mempublikasikan hasil penelitian dan kajian di bidang aviasi.',
                ),

                // KARTU JURNAL (PARAM GROUP)
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Jurnal / Publikasi', 'ppic-custom-element' ),
                    'param_name' => 'journals',
                    'value'      => urlencode( wp_json_encode( $dummy_journals ) ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Teks Badge (Kuning)', 'param_name' => 'badge'),
                        array('type' => 'textfield', 'heading' => 'Judul Jurnal', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textarea', 'heading' => 'Deskripsi', 'param_name' => 'desc'),
                        
                        array('type' => 'textfield', 'heading' => 'Ikon Meta 1 (FontAwesome)', 'param_name' => 'm1_icon', 'value' => 'fas fa-calendar-alt'),
                        array('type' => 'textfield', 'heading' => 'Teks Meta 1', 'param_name' => 'm1_text'),
                        
                        array('type' => 'textfield', 'heading' => 'Ikon Meta 2 (FontAwesome)', 'param_name' => 'm2_icon', 'value' => 'fas fa-check-circle'),
                        array('type' => 'textfield', 'heading' => 'Teks Meta 2', 'param_name' => 'm2_text'),
                        
                        array('type' => 'textfield', 'heading' => 'Teks Tombol/Link', 'param_name' => 'btn_text', 'value' => 'Kunjungi Jurnal'),
                        array('type' => 'textfield', 'heading' => 'URL Link', 'param_name' => 'btn_link', 'description' => 'Link menuju website jurnal terkait'),
                    ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'jurnal',
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