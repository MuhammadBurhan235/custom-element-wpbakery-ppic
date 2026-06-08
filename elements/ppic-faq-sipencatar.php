<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Render Shortcode
add_shortcode( 'ppic_faq_sipencatar', 'ppic_faq_sipencatar_render' );
function ppic_faq_sipencatar_render( $atts ) {
    
    // Data Default (6 FAQ)
    $default_items = urlencode( wp_json_encode( array(
        array( 
            'icon_class' => 'fas fa-question-circle', 
            'question'   => 'Apakah lulusan SMA jurusan IPS bisa mendaftar?', 
            'answer'     => 'Maaf, hanya lulusan SMA jurusan IPA atau SMK jurusan teknik yang relevan dengan bidang penerbangan (listrik, mesin, elektronika, otomotif, dll) yang dapat mendaftar. Untuk program studi Penerbang, prioritas dari jurusan IPA.'
        ),
        array( 
            'icon_class' => 'fas fa-question-circle', 
            'question'   => 'Apakah ada tes bahasa Inggris?', 
            'answer'     => 'Ya, tes bahasa Inggris (TOEFL-like) menjadi salah satu komponen seleksi akademik, karena seluruh proses pembelajaran menggunakan bilingual (Indonesia & Inggris).'
        ),
        array( 
            'icon_class' => 'fas fa-question-circle', 
            'question'   => 'Bagaimana jika tidak lulus seleksi kesehatan?', 
            'answer'     => 'Seleksi kesehatan bersifat mutlak. Peserta yang tidak memenuhi standar kesehatan penerbangan dinyatakan tidak lulus. Tidak ada banding untuk hasil pemeriksaan kesehatan.'
        ),
        array( 
            'icon_class' => 'fas fa-question-circle', 
            'question'   => 'Apakah ada beasiswa untuk jalur mandiri?', 
            'answer'     => 'Ada. Taruna jalur mandiri berprestasi dapat mengajukan beasiswa KIP Kuliah, beasiswa PTDI (Dirgantara Indonesia), dan bantuan sosial lainnya melalui pusat beasiswa PPI Curug.'
        ),
        array( 
            'icon_class' => 'fas fa-question-circle', 
            'question'   => 'Apakah pelatihan fisik dan mental berat?', 
            'answer'     => 'Sebagai institusi kedinasan, PPI Curug memiliki program pembinaan fisik dan kedisiplinan yang cukup intensif. Calon taruna harus siap mental dan fisik yang prima.'
        ),
        array( 
            'icon_class' => 'fas fa-question-circle', 
            'question'   => 'Kapan pengumuman hasil seleksi?', 
            'answer'     => 'Pengumuman akan disampaikan melalui portal SIPENCATAR dan diumumkan di website resmi PPI Curug. Jadwal tepatnya mengikuti kalender penerimaan tahun berjalan.'
        )
    ) ) );

    $atts = shortcode_atts(
        array(
            'title'     => 'Pertanyaan Umum (FAQ)',
            'subtitle'  => 'Jawaban atas pertanyaan yang sering diajukan seputar penerimaan taruna PPI Curug.',
            'faq_items' => $default_items,
            'el_id'     => '',
            'el_class'  => '',
        ),
        $atts
    );

    // Parse Data
    $items = array();
    if ( ! empty( $atts['faq_items'] ) ) {
        $items = vc_param_group_parse_atts( $atts['faq_items'] );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-faq-section ' . esc_attr( $atts['el_class'] );

    // Pastikan FontAwesome dimuat
    if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
        vc_icon_element_fonts_enqueue( 'fontawesome' );
    }

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container">
            <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="section-sub"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
                <div class="grid-2">
                    <?php foreach ( $items as $item ) : ?>
                        <?php 
                        $icon     = ! empty( $item['icon_class'] ) ? $item['icon_class'] : 'fas fa-question-circle';
                        $question = ! empty( $item['question'] ) ? $item['question'] : '';
                        $answer   = ! empty( $item['answer'] ) ? $item['answer'] : '';
                        ?>
                        <div class="faq-card">
                            <h3>
                                <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                                <span><?php echo esc_html( $question ); ?></span>
                            </h3>
                            <?php if ( ! empty( $answer ) ) : ?>
                                <!-- Menggunakan wp_kses_post agar tag HTML sederhana seperti <strong> tetap berfungsi -->
                                <p><?php echo wp_kses_post( $answer ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// 2. Mapping ke WPBakery
add_action( 'vc_before_init', 'ppic_faq_sipencatar_map' );
function ppic_faq_sipencatar_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $default_items = urlencode( wp_json_encode( array(
        array( 'icon_class' => 'fas fa-question-circle', 'question' => 'Apakah lulusan SMA jurusan IPS bisa mendaftar?', 'answer' => 'Maaf, hanya lulusan SMA jurusan IPA atau SMK jurusan teknik yang relevan dengan bidang penerbangan (listrik, mesin, elektronika, otomotif, dll) yang dapat mendaftar. Untuk program studi Penerbang, prioritas dari jurusan IPA.' ),
        array( 'icon_class' => 'fas fa-question-circle', 'question' => 'Apakah ada tes bahasa Inggris?', 'answer' => 'Ya, tes bahasa Inggris (TOEFL-like) menjadi salah satu komponen seleksi akademik, karena seluruh proses pembelajaran menggunakan bilingual (Indonesia & Inggris).' ),
        array( 'icon_class' => 'fas fa-question-circle', 'question' => 'Bagaimana jika tidak lulus seleksi kesehatan?', 'answer' => 'Seleksi kesehatan bersifat mutlak. Peserta yang tidak memenuhi standar kesehatan penerbangan dinyatakan tidak lulus. Tidak ada banding untuk hasil pemeriksaan kesehatan.' ),
        array( 'icon_class' => 'fas fa-question-circle', 'question' => 'Apakah ada beasiswa untuk jalur mandiri?', 'answer' => 'Ada. Taruna jalur mandiri berprestasi dapat mengajukan beasiswa KIP Kuliah, beasiswa PTDI (Dirgantara Indonesia), dan bantuan sosial lainnya melalui pusat beasiswa PPI Curug.' ),
        array( 'icon_class' => 'fas fa-question-circle', 'question' => 'Apakah pelatihan fisik dan mental berat?', 'answer' => 'Sebagai institusi kedinasan, PPI Curug memiliki program pembinaan fisik dan kedisiplinan yang cukup intensif. Calon taruna harus siap mental dan fisik yang prima.' ),
        array( 'icon_class' => 'fas fa-question-circle', 'question' => 'Kapan pengumuman hasil seleksi?', 'answer' => 'Pengumuman akan disampaikan melalui portal SIPENCATAR dan diumumkan di website resmi PPI Curug. Jadwal tepatnya mengikuti kalender penerimaan tahun berjalan.' ),
    ) ) );

    vc_map(
        array(
            'name'     => __( 'PPIC FAQ Sipencatar', 'ppic-custom-element' ),
            'base'     => 'ppic_faq_sipencatar',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-editor-help',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Pertanyaan Umum (FAQ)',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Jawaban atas pertanyaan yang sering diajukan seputar penerimaan taruna PPI Curug.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Pertanyaan & Jawaban', 'ppic-custom-element' ),
                    'param_name' => 'faq_items',
                    'value'      => $default_items,
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value'      => 'fas fa-question-circle',
                            'description'=> __( 'Contoh: fas fa-question-circle', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Pertanyaan', 'ppic-custom-element' ),
                            'param_name'  => 'question',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea', /* INI YANG MEMPERBAIKI BUG: Berubah jadi textarea biasa */
                            'heading'    => __( 'Jawaban', 'ppic-custom-element' ),
                            'param_name' => 'answer',
                            'description'=> __( 'Gunakan tag HTML &lt;strong&gt;Teks&lt;/strong&gt; jika ingin menebalkan huruf.', 'ppic-custom-element' ),
                        ),
                    ),
                ),
                array(
                    'type'       => 'el_id',
                    'heading'    => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                ),
            ),
        )
    );
}