<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_kurikulum', 'ppic_prodi_kurikulum_render' );
function ppic_prodi_kurikulum_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'section_id'     => 'kurikulum',
            'title'          => 'Kurikulum',
            'title_icon'     => 'fas fa-book-open',
            'subtitle'       => 'Kurikulum TMB dirancang berbasis kompetensi dengan perbandingan praktikum yang lebih besar daripada teori, sehingga lulusan benar-benar siap terjun ke dunia industri.',
            'total_sks'      => '109',
            'total_semester' => '6',
            'semesters'      => '',
            'note'           => '* Kurikulum dapat disesuaikan dengan perkembangan industri dan regulasi terbaru.',
            'el_class'       => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-kurikulum-section section-block alt' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data semester
    $semester_items = vc_param_group_parse_atts( $atts['semesters'] );
    if ( ! is_array( $semester_items ) ) {
        $semester_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-kurikulum-container">
            
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

            <div class="ppic-kurikulum-wrapper">
                <!-- Toggle Header -->
                <div class="ppic-kurikulum-info" id="kurikulumToggle_<?php echo esc_attr($atts['section_id']); ?>">
                    <div class="ppic-stat-group">
                        <div class="stat">
                            <i class="fas fa-book"></i>
                            <div>
                                <span class="num"><?php echo esc_html( $atts['total_sks'] ); ?></span> 
                                <span class="label">Total SKS</span>
                            </div>
                        </div>
                        <div class="stat">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <span class="num"><?php echo esc_html( $atts['total_semester'] ); ?></span> 
                                <span class="label">Semester</span>
                            </div>
                        </div>
                    </div>
                    <div class="toggle-icon" id="kurikulumIcon_<?php echo esc_attr($atts['section_id']); ?>">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>

                <!-- Collapsible Content -->
                <div class="ppic-kurikulum-collapse active" id="kurikulumCollapse_<?php echo esc_attr($atts['section_id']); ?>">
                    <div class="ppic-kurikulum-collapse-inner">
                        <div class="ppic-kurikulum-grid">
                            <?php if ( ! empty( $semester_items ) ) : ?>
                                <?php foreach ( $semester_items as $smt ) : 
                                    $smt_title = isset( $smt['title'] ) ? trim( $smt['title'] ) : '';
                                    $smt_icon  = isset( $smt['icon'] ) ? trim( $smt['icon'] ) : 'fas fa-check';
                                    $smt_subs  = isset( $smt['subjects'] ) ? trim( $smt['subjects'] ) : '';
                                    
                                    if ( empty( $smt_title ) ) continue;
                                ?>
                                    <div class="ppic-kurikulum-card">
                                        <h4><i class="<?php echo esc_attr( $smt_icon ); ?>"></i> <?php echo esc_html( $smt_title ); ?></h4>
                                        <?php if ( ! empty( $smt_subs ) ) : 
                                            // Memecah teks baris baru menjadi array
                                            $lines = explode( "\n", str_replace( array("\r\n", "\r"), "\n", $smt_subs ) );
                                        ?>
                                            <ul>
                                                <?php foreach ( $lines as $line ) : 
                                                    $line = trim( $line );
                                                    if ( empty( $line ) ) continue;
                                                    
                                                    // Memecah nama matkul dan SKS menggunakan separator '|'
                                                    $parts = explode( '|', $line );
                                                    $matkul = isset( $parts[0] ) ? trim( $parts[0] ) : '';
                                                    $sks    = isset( $parts[1] ) ? trim( $parts[1] ) : '';
                                                ?>
                                                    <li>
                                                        <span class="subject-name"><?php echo esc_html( $matkul ); ?></span>
                                                        <?php if ( ! empty( $sks ) ) : ?>
                                                            <span class="sks"><?php echo esc_html( $sks ); ?></span>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="grid-column: 1 / -1; text-align:center;">Data kurikulum belum tersedia.</p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ( ! empty( $atts['note'] ) ) : ?>
                            <p class="ppic-kurikulum-note">
                                <?php echo esc_html( $atts['note'] ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
    <?php
    // JS Murni untuk Toggle Collapse
    static $ppic_kurikulum_js_loaded = false;
    if ( ! $ppic_kurikulum_js_loaded ) {
        $ppic_kurikulum_js_loaded = true;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mencari semua elemen toggle yang ada di halaman (berjaga-jaga jika ada lebih dari 1 prodi di-load)
            const toggles = document.querySelectorAll('.ppic-kurikulum-info');
            
            toggles.forEach(function(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    // Ambil ID Unik
                    const idPart = this.id.replace('kurikulumToggle_', '');
                    const collapseContent = document.getElementById('kurikulumCollapse_' + idPart);
                    const icon = document.getElementById('kurikulumIcon_' + idPart);
                    
                    if (!collapseContent) return;

                    // Logika buka tutup
                    if (collapseContent.classList.contains('active')) {
                        // Tutup
                        collapseContent.style.maxHeight = collapseContent.scrollHeight + "px"; // Set max-height ke tinggi aktual dulu sebelum transisi
                        setTimeout(() => {
                            collapseContent.style.maxHeight = "0px";
                        }, 10);
                        collapseContent.classList.remove('active');
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        // Buka
                        collapseContent.style.maxHeight = collapseContent.scrollHeight + "px";
                        collapseContent.classList.add('active');
                        icon.style.transform = 'rotate(0deg)';
                        
                        // Bersihkan inline style setelah animasi selesai agar responsif tidak rusak
                        setTimeout(() => {
                            collapseContent.style.maxHeight = "none";
                        }, 400); // 400ms sesuai durasi transisi CSS
                    }
                });
            });
        });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_kurikulum_element' );
function ppic_register_prodi_kurikulum_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default data dummy yang disesuaikan dengan cara input Textarea dan mencakup seluruh 6 semester
    $dummy_semesters = array(
        array( 
            'title' => 'Semester I', 
            'icon'  => 'fas fa-1', 
            'subjects' => "Pendidikan Agama | 2 SKS\nPendidikan Pancasila | 2 SKS\nBahasa Inggris Teknik | 2 SKS\nMatematika Teknik | 3 SKS\nFisika Terapan | 3 SKS\nGambar Teknik | 3 SKS\nPengantar Teknik Mekanikal | 2 SKS\nPraktikum Gambar Teknik | 2 SKS" 
        ),
        array( 
            'title' => 'Semester II', 
            'icon'  => 'fas fa-2', 
            'subjects' => "Bahasa Indonesia | 2 SKS\nKewarganegaraan | 2 SKS\nMekanika Teknik | 3 SKS\nTermodinamika Dasar | 3 SKS\nTeknologi Mekanik | 3 SKS\nPraktikum Teknologi Mekanik | 3 SKS\nKeselamatan Kerja | 2 SKS" 
        ),
        array( 
            'title' => 'Semester III', 
            'icon'  => 'fas fa-3', 
            'subjects' => "Mekanika Fluida | 3 SKS\nSistem Refrigerasi Dasar | 3 SKS\nPraktikum AC 1 | 3 SKS\nSistem Pompa dan Pemipaan | 3 SKS\nPraktikum Water & Pump System | 3 SKS\nRangkaian Listrik | 2 SKS\nPraktikum Elektromekanikal | 2 SKS" 
        ),
        array( 
            'title' => 'Semester IV', 
            'icon'  => 'fas fa-4', 
            'subjects' => "Air Conditioning Lanjutan | 3 SKS\nPraktikum AC 2 | 3 SKS\nTraction Equipment | 3 SKS\nAlat Berat & PKP-PK | 3 SKS\nPraktikum Alat Berat | 2 SKS\nPLC & Otomasi | 2 SKS\nPraktikum PLC | 2 SKS" 
        ),
        array( 
            'title' => 'Semester V', 
            'icon'  => 'fas fa-5', 
            'subjects' => "Hidrolik & Pneumatik | 3 SKS\nPraktikum Hidrolik & Pneumatik | 2 SKS\nPerawatan & Perbaikan | 3 SKS\nAnalisis Kerusakan | 2 SKS\nPerencanaan & Evaluasi | 2 SKS\nEtika Profesi | 2 SKS\nKewirausahaan | 2 SKS" 
        ),
        array( 
            'title' => 'Semester VI', 
            'icon'  => 'fas fa-6', 
            'subjects' => "Manajemen Pemeliharaan | 2 SKS\nAudit & Sertifikasi | 2 SKS\nTugas Akhir (TA) | 4 SKS\nPraktik Kerja Lapangan (PKL) | 4 SKS\nUji Kompetensi | 2 SKS" 
        ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Kurikulum', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_kurikulum',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-book-alt',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'kurikulum',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Kurikulum',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-book-open',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Kurikulum TMB dirancang berbasis kompetensi dengan perbandingan praktikum yang lebih besar daripada teori, sehingga lulusan benar-benar siap terjun ke dunia industri.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                // STATISTIK BAR ATAS
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Angka Total SKS', 'ppic-custom-element' ),
                    'param_name'  => 'total_sks',
                    'value'       => '109',
                    'group'       => __( 'Data Kurikulum', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Angka Total Semester', 'ppic-custom-element' ),
                    'param_name'  => 'total_semester',
                    'value'       => '6',
                    'group'       => __( 'Data Kurikulum', 'ppic-custom-element' ),
                ),
                // REPEATER SEMESTER
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Semester & Mata Kuliah', 'ppic-custom-element' ),
                    'param_name'  => 'semesters',
                    'value'       => urlencode( wp_json_encode( $dummy_semesters ) ),
                    'group'       => __( 'Data Kurikulum', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textfield', 'heading' => 'Judul (Contoh: Semester I)', 'param_name' => 'title', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon', 'value' => 'fas fa-1'),
                        array(
                            'type'        => 'textarea', 
                            'heading'     => 'Daftar Mata Kuliah', 
                            'param_name'  => 'subjects',
                            'description' => 'Satu mata kuliah per baris. Pisahkan nama dan SKS dengan garis vertikal (|). Contoh: <strong>Matematika Teknik | 3 SKS</strong>'
                        ),
                    ),
                ),
                // FOOTER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Catatan Kaki (Bawah Tabel)', 'ppic-custom-element' ),
                    'param_name'  => 'note',
                    'value'       => '* Kurikulum dapat disesuaikan dengan perkembangan industri dan regulasi terbaru.',
                    'group'       => __( 'Data Kurikulum', 'ppic-custom-element' ),
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