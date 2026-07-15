<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_survey_list', 'ppic_survey_list_render' );
function ppic_survey_list_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'intro_title'     => 'Pilih Survey Anda',
            'intro_highlight' => 'Anda',
            'intro_desc'      => 'Kami merancang enam instrumen khusus untuk mendengar suara dari setiap pemangku kepentingan. Temukan yang paling relevan dengan peran Anda, dan bantu kami terbang lebih tinggi.',
            'survey_items'    => '',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Parse items group (Kartu Survey)
    $cards = vc_param_group_parse_atts( $atts['survey_items'] );
    if ( ! is_array( $cards ) ) {
        $cards = array();
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-survey-grid-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-survey-container">
            
            <div class="ppic-survey-section-intro">
                <h2>
                    <?php 
                    if ( ! empty( $atts['intro_highlight'] ) ) {
                        $title_clean = str_replace( $atts['intro_highlight'], '<span>' . esc_html( $atts['intro_highlight'] ) . '</span>', $atts['intro_title'] );
                        echo wp_kses_post( $title_clean );
                    } else {
                        echo esc_html( $atts['intro_title'] );
                    }
                    ?>
                </h2>
                <?php if ( ! empty( $atts['intro_desc'] ) ) : ?>
                    <p><?php echo wp_kses_post( $atts['intro_desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="ppic-survey-grid">
                <?php if ( ! empty( $cards ) ) : ?>
                    <?php foreach ( $cards as $card ) : 
                        $icon  = isset( $card['icon'] ) ? trim( $card['icon'] ) : 'fas fa-clipboard-check';
                        $color = isset( $card['icon_color'] ) ? trim( $card['icon_color'] ) : 'gold';
                        $title = isset( $card['title'] ) ? trim( $card['title'] ) : '';
                        $badge = isset( $card['badge'] ) ? trim( $card['badge'] ) : '';
                        $desc  = isset( $card['desc'] ) ? trim( $card['desc'] ) : '';
                        
                        // Parse link button per card
                        $btn_link  = isset( $card['btn_link'] ) ? $card['btn_link'] : '';
                        $link_data = vc_build_link( $btn_link );
                        $a_href    = ! empty( $link_data['url'] ) ? $link_data['url'] : '#';
                        $a_target  = ! empty( $link_data['target'] ) ? ' target="' . esc_attr( trim( $link_data['target'] ) ) . '"' : ' target="_blank"';
                        
                        if ( empty( $title ) ) continue;
                    ?>
                        <div class="ppic-survey-card">
                            <div class="ppic-card-icon <?php echo esc_attr( $color ); ?>">
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo esc_html( $title ); ?></h3>
                            <?php if ( ! empty( $badge ) ) : ?>
                                <span class="ppic-survey-audience"><?php echo esc_html( $badge ); ?></span>
                            <?php endif; ?>
                            <?php if ( ! empty( $desc ) ) : ?>
                                <p><?php echo esc_html( $desc ); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $a_href ); ?>"<?php echo $a_target; ?> class="ppic-btn-survey">
                                Isi Kuesioner <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #666;">Data survey belum tersedia.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_survey_list_element' );
function ppic_register_survey_list_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Generate ke-6 dummy data persis seperti survey2.html
    $dummy_surveys = array(
        array( 'icon' => 'fas fa-user-graduate', 'icon_color' => 'gold', 'title' => 'Kepuasan Mahasiswa', 'badge' => 'Taruna', 'desc' => 'Pengalaman belajar Anda adalah cermin mutu pendidikan. Survey ini menggali persepsi terhadap fasilitas, pengajaran, layanan administrasi, dan iklim kampus.', 'btn_link' => 'url:https%3A%2F%2Fforms.gle%2FrmjEuZ5G89d7w1Wh9|target:_blank' ),
        array( 'icon' => 'fas fa-clipboard-check', 'icon_color' => 'teal', 'title' => 'Monev Pembelajaran', 'badge' => 'Taruna', 'desc' => 'Menakar efektivitas metode pengajaran, kurikulum, dan capaian kompetensi. Membantu dosen dan institusi menyempurnakan proses belajar mengajar.', 'btn_link' => 'url:https%3A%2F%2Fforms.gle%2FEEUgr7t6qUjknr7s7|target:_blank' ),
        array( 'icon' => 'fas fa-chalkboard-teacher', 'icon_color' => 'rose', 'title' => 'Kepuasan Dosen', 'badge' => 'Dosen', 'desc' => 'Kesejahteraan, pengembangan karir, dan iklim akademik sangat memengaruhi kualitas pengajaran. Survey ini menjadi wahana untuk menyuarakan harapan.', 'btn_link' => 'url:https%3A%2F%2Fforms.gle%2FEjqkQoVVLXHLDngF7|target:_blank' ),
        array( 'icon' => 'fas fa-users-cog', 'icon_color' => 'purple', 'title' => 'Kepuasan Tendik', 'badge' => 'Tenaga Kependidikan', 'desc' => 'Laboran, teknisi, pustakawan, dan staf administrasi adalah tulang punggung operasional. Survey ini mengukur kepuasan terhadap beban kerja dan dukungan.', 'btn_link' => 'url:https%3A%2F%2Fforms.gle%2FwVqaEH5iE6LCsC7T9|target:_blank' ),
        array( 'icon' => 'fas fa-handshake', 'icon_color' => 'orange', 'title' => 'Kepuasan Mitra Kerjasama', 'badge' => 'Mitra', 'desc' => 'Mitra industri dan pemerintah adalah bagian penting dari ekosistem penerbangan. Evaluasi kepuasan terhadap kerjasama dan kontribusi lulusan.', 'btn_link' => 'url:https%3A%2F%2Fforms.gle%2FNHGwoJP99XKPHGx29|target:_blank' ),
        array( 'icon' => 'fas fa-briefcase', 'icon_color' => 'blue', 'title' => 'Kepuasan Pengguna Lulusan', 'badge' => 'Pengguna Lulusan', 'desc' => 'Perusahaan yang mempekerjakan alumni PPI Curug adalah cermin terbaik kualitas pendidikan. Menggali kompetensi teknis dan profesionalisme lulusan.', 'btn_link' => 'url:https%3A%2F%2FYvgQoo1NsKa98uyz8|target:_blank' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Daftar Survey Grid', 'ppic-custom-element' ),
            'base'     => 'ppic_survey_list',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-grid-view',
            'params'   => array(
                // INTRO HEADINGS
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name'  => 'intro_title',
                    'value'       => 'Pilih Survey Anda',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Kata Sorotan Judul (Kuning)', 'ppic-custom-element' ),
                    'param_name'  => 'intro_highlight',
                    'value'       => 'Anda',
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Deskripsi Pengantar', 'ppic-custom-element' ),
                    'param_name'  => 'intro_desc',
                    'value'       => 'Kami merancang enam instrumen khusus untuk mendengar suara dari setiap pemangku kepentingan. Temukan yang paling relevan dengan peran Anda, dan bantu kami terbang lebih tinggi.',
                ),
                // CARDS REPEATER
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Kartu Survey', 'ppic-custom-element' ),
                    'param_name'  => 'survey_items',
                    'value'       => urlencode( wp_json_encode( $dummy_surveys ) ),
                    'params'      => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Judul Survey / Kuesioner', 'ppic-custom-element' ),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Teks Badge Target (Audience)', 'ppic-custom-element' ),
                            'param_name'  => 'badge',
                        ),
                        array(
                            'type'        => 'textarea',
                            'heading'     => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                            'param_name'  => 'desc',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Ikon FontAwesome', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'value'       => 'fas fa-clipboard-check',
                        ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => __( 'Warna Lingkaran Ikon', 'ppic-custom-element' ),
                            'param_name'  => 'icon_color',
                            'value'       => array(
                                'Gold (Kuning)' => 'gold',
                                'Teal (Hijau Toska)' => 'teal',
                                'Rose (Merah Muda)' => 'rose',
                                'Purple (Ungu)' => 'purple',
                                'Orange (Jingga)' => 'orange',
                                'Blue (Biru)' => 'blue',
                            ),
                        ),
                        array(
                            'type'        => 'vc_link',
                            'heading'     => __( 'Link Kuesioner (Google Forms)', 'ppic-custom-element' ),
                            'param_name'  => 'btn_link',
                        ),
                    ),
                ),
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