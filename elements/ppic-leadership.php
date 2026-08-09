<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Helper function membaca CSV (Mirip dengan Gallery)
function ppic_leadership_get_csv_contents( $attachment_id ) {
    $attachment_id = absint( $attachment_id );
    if ( ! $attachment_id || ! function_exists( 'get_attached_file' ) ) {
        return '';
    }

    $file_path = get_attached_file( $attachment_id );
    if ( ! $file_path || ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
        return '';
    }

    $extension = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );
    if ( 'csv' !== $extension && 'txt' !== $extension ) {
        return '';
    }

    $contents = file_get_contents( $file_path );
    return false !== $contents ? $contents : '';
}

// 2. Parser CSV yang dikelompokkan berdasarkan "header"
function ppic_leadership_parse_spreadsheet( $raw_value ) {
    if ( empty( $raw_value ) ) {
        return array();
    }
    
    // Hilangkan BOM
    $raw_value = preg_replace( '/^\xEF\xBB\xBF/', '', $raw_value );
    $lines = preg_split( '/\r\n|\r|\n/', $raw_value );
    $lines = array_values( array_filter( array_map( 'trim', $lines ), function($l) { return '' !== $l; } ) );
    
    if ( count( $lines ) < 2 ) {
        return array();
    }
    
    $delimiter = ',';
    if ( substr_count( $lines[0], ';' ) > substr_count( $lines[0], ',' ) ) {
        $delimiter = ';';
    }
    
    $headers = str_getcsv( $lines[0], $delimiter );
    $mapped_headers = array();
    
    foreach ( $headers as $h ) {
        $h = strtolower( trim( preg_replace('/[^a-zA-Z0-9]+/', '_', $h), '_' ) );
        // Mencari alias nama kolom
        if ( in_array($h, ['header', 'bagian', 'grup']) ) $mapped_headers[] = 'header';
        elseif ( in_array($h, ['jabatan', 'title', 'posisi']) ) $mapped_headers[] = 'jabatan';
        elseif ( in_array($h, ['nama', 'name']) ) $mapped_headers[] = 'nama';
        elseif ( in_array($h, ['foto', 'image', 'image_url']) ) $mapped_headers[] = 'foto';
        else $mapped_headers[] = '';
    }

    // Inisialisasi wadah untuk setiap header akordion
    $grouped_data = array(
        'Pimpinan' => array(),
        'Dewan Pengawas' => array(),
        'Senat Akademik' => array(),
        'Ketua Program Studi' => array(),
        'Kepala Satuan & Pusat' => array(),
        'Koordinator' => array(),
        'Kepala Unit' => array(),
    );
    
    foreach ( array_slice( $lines, 1 ) as $line ) {
        $columns = str_getcsv( $line, $delimiter );
        $row = array('header' => '', 'jabatan' => '', 'nama' => '', 'foto' => '');
        
        foreach ( $mapped_headers as $index => $field ) {
            if ( '' === $field ) continue;
            $row[ $field ] = isset( $columns[ $index ] ) ? trim( wp_unslash( $columns[ $index ] ) ) : '';
        }

        if ( !empty($row['nama']) ) {
            $h_val = $row['header'];
            // Cocokkan header secara loose (mengabaikan huruf besar/kecil)
            foreach ( array_keys($grouped_data) as $key ) {
                if ( stripos($h_val, $key) !== false ) {
                    $grouped_data[$key][] = $row;
                    break;
                }
            }
        }
    }
    
    return $grouped_data;
}

// 3. Register Custom WPBakery Uploader Param
function ppic_leadership_register_csv_param() {
    if ( ! function_exists( 'vc_add_shortcode_param' ) ) {
        return;
    }
    vc_add_shortcode_param( 'ppic_leadership_csv_upload', 'ppic_leadership_csv_upload_field' );
}
add_action( 'init', 'ppic_leadership_register_csv_param', 20 );

function ppic_leadership_csv_upload_field( $settings, $value ) {
    $param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
    $attachment_id = absint( $value );
    $file_label = '';
    $field_id = 'ppic-ldr-csv-' . wp_rand( 1000, 999999 );

    if ( $attachment_id && function_exists( 'get_attached_file' ) ) {
        $file_path = get_attached_file( $attachment_id );
        if ( $file_path ) $file_label = basename( $file_path );
    }

    $output = '<div id="' . esc_attr( $field_id ) . '" class="ppic-csv-upload-field">';
    $output .= '<input type="hidden" class="wpb_vc_param_value wpb-textinput ' . esc_attr( $param_name ) . ' ' . esc_attr( $settings['type'] ) . '_field" name="' . esc_attr( $param_name ) . '" value="' . esc_attr( $attachment_id ) . '">';
    $output .= '<input type="text" class="ppic-csv-upload-field__label" value="' . esc_attr( $file_label ) . '" placeholder="Belum ada file dipilih" readonly style="width:100%;margin-bottom:8px;">';
    $output .= '<button type="button" class="button button-secondary ppic-csv-upload-field__select">Pilih Data Struktur (CSV)</button> ';
    $output .= '<button type="button" class="button ppic-csv-upload-field__clear"' . ( $attachment_id ? '' : ' style="display:none;"' ) . '>Hapus File</button>';
    $output .= '<script>(function(){var root=document.getElementById(' . wp_json_encode( $field_id ) . ');if(!root||root.dataset.ppicCsvReady){return;}root.dataset.ppicCsvReady="1";var selectButton=root.querySelector(".ppic-csv-upload-field__select");var clearButton=root.querySelector(".ppic-csv-upload-field__clear");var hiddenInput=root.querySelector(".wpb_vc_param_value");var labelInput=root.querySelector(".ppic-csv-upload-field__label");if(selectButton){selectButton.addEventListener("click",function(event){event.preventDefault();if(typeof window.wp==="undefined"||typeof window.wp.media==="undefined"){window.alert("Media Library WordPress belum siap.");return;}var fileFrame=window.wp.media({title:"Pilih File CSV Struktur",button:{text:"Gunakan file ini"},multiple:false});fileFrame.on("select",function(){var attachment=fileFrame.state().get("selection").first().toJSON();if(hiddenInput){hiddenInput.value=attachment.id;hiddenInput.dispatchEvent(new Event("change",{bubbles:true}));}if(labelInput){labelInput.value=attachment.filename||attachment.url||"";}if(clearButton){clearButton.style.display="";}});fileFrame.open();});}if(clearButton){clearButton.addEventListener("click",function(event){event.preventDefault();if(hiddenInput){hiddenInput.value="";hiddenInput.dispatchEvent(new Event("change",{bubbles:true}));}if(labelInput){labelInput.value="";}clearButton.style.display="none";});}}());</script>';
    $output .= '</div>';

    return $output;
}

// 4. Render Shortcode
add_shortcode( 'ppic_leadership', 'ppic_leadership_render' );
function ppic_leadership_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'           => 'Kepemimpinan &',
            'title_highlight' => 'Struktur Organisasi',
            'subtitle'        => 'Berdasarkan Peraturan Menteri Perhubungan PM 100 Tahun 2021',
            'struktur_img'    => '',
            'spreadsheet_file'=> '',
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Ambil URL Gambar Struktur
    $img_url = 'https://lh3.googleusercontent.com/d/1e8bd2maVEUhIOLBI8PEyoqqg-azPSXeH'; 
    if ( ! empty( $atts['struktur_img'] ) ) {
        $img_data = wp_get_attachment_image_src( $atts['struktur_img'], 'full' );
        if ( $img_data ) $img_url = $img_data[0];
    }

    // Parse Data CSV
    $grouped_data = array();
    if ( ! empty( $atts['spreadsheet_file'] ) ) {
        $grouped_data = ppic_leadership_parse_spreadsheet( ppic_leadership_get_csv_contents( $atts['spreadsheet_file'] ) );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-leadership-section ' . esc_attr( $atts['el_class'] );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container">
            <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?> <span style="color: var(--secondary);"><?php echo esc_html( $atts['title_highlight'] ); ?></span></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="section-sub"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>

            <div class="accordion-container">

                <!-- 1. Struktur Organisasi -->
                <div class="accordion-item">
                    <button class="accordion-header">Struktur Organisasi <i class="fas fa-chevron-down"></i></button>
                    <div class="accordion-content">
                        <div class="zoom-wrapper" id="zoomBox">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="Struktur Organisasi PPI Curug" id="zoomImg">
                        </div>
                        <p class="zoom-hint"><i class="fas fa-search-plus"></i> Arahkan kursor atau sentuh untuk memperbesar gambar.</p>
                    </div>
                </div>

                <!-- 2. Pimpinan (Khusus Photo Grid) -->
                <div class="accordion-item">
                    <button class="accordion-header">Pimpinan <i class="fas fa-chevron-down"></i></button>
                    <div class="accordion-content">
                        <div class="photo-grid">
                            <?php if ( !empty($grouped_data['Pimpinan']) ) : ?>
                                <?php foreach( $grouped_data['Pimpinan'] as $item ) : ?>
                                    <div class="photo-card">
                                        <div class="photo-circle">
                                            <img src="<?php echo esc_url( !empty($item['foto']) ? $item['foto'] : 'https://via.placeholder.com/150' ); ?>" alt="<?php echo esc_attr($item['nama']); ?>">
                                        </div>
                                        <div class="name"><?php echo esc_html($item['nama']); ?></div>
                                        <div class="title"><?php echo esc_html($item['jabatan']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="text-align: center; color: #888;">Data Pimpinan belum tersedia.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Badge Pimpinan -->
                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Direktur &amp; Wakil Direktur &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Direktur &amp; Wakil Direktur:</strong><br>
                                    a. Direktur PPI Curug merupakan unsur pelaksana akademik yang mempunyai tugas melakukan penetapan kebijakan non akademik dan pengelolaan PPI Curug.<br>
                                    b. Wakil Direktur I membantu Direktur dalam memimpin pelaksanaan kegiatan pendidikan, penelitian, dan pengabdian kepada masyarakat, pelatihan, serta pemanfaatan sarana dan prasarana.<br>
                                    c. Wakil Direktur II membantu Direktur dalam memimpin pelaksanaan kegiatan di bidang keuangan, kepegawaian, dan umum serta pengembangan usaha dan kerja sama.<br>
                                    d. Wakil Direktur III membantu Direktur dalam memimpin pelaksanaan kegiatan pembinaan administrasi ketarunaan dan alumni, pembangunan karakter, serta kesehatan dan kesejahteraan taruna.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>

                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Bagian Administrasi Akademik &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Bagian Administrasi Akademik dan Ketarunaan:</strong><br>
                                    Melaksanakan pengelolaan administrasi akademik dan ketarunaan serta pengelolaan data evaluasi akademik. Fungsi:<br>
                                    1) Pengelolaan dan pendokumentasian administrasi akademik;<br>
                                    2) Perencanaan dan pengembangan program akademik;<br>
                                    3) Pengelolaan data dan evaluasi akademik;<br>
                                    4) Pelaksanaan administrasi penerimaan taruna;<br>
                                    5) Pengelolaan pelayanan kesejahteraan taruna;<br>
                                    6) Pengelolaan beasiswa dan bantuan pendidikan taruna;<br>
                                    7) Perencanaan dan pelaksanaan administrasi praktek kerja taruna;<br>
                                    8) Pengelolaan administrasi alumni.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>

                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Bagian Keuangan dan Umum &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Bagian Keuangan dan Umum:</strong><br>
                                    Melaksanakan pengelolaan urusan keuangan dan umum. Fungsi:<br>
                                    1) Penyiapan penyusunan rencana dan program;<br>
                                    2) Pengelolaan keuangan;<br>
                                    3) Penyusunan rencana strategis bisnis dan rencana bisnis dan anggaran;<br>
                                    4) Pelaksanaan urusan kepegawaian dan organisasi;<br>
                                    5) Pelaksanaan urusan tata laksana, dan ketatausahaan;<br>
                                    6) Pengelolaan kerumahtanggaan, barang milik negara, investasi dan aset;<br>
                                    7) Pelaksanaan urusan hukum, kerja sama hubungan masyarakat, komunikasi publik, dan protokol;<br>
                                    8) Pembinaan pendidik dan tenaga kependidikan;<br>
                                    9) Pelaksanaan evaluasi dan pelaporan.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Dewan Pengawas -->
                <div class="accordion-item"> <!-- Active by default -->
                    <button class="accordion-header">Dewan Pengawas <i class="fas fa-chevron-down" style="transform: rotate(180deg);"></i></button>
                    <div class="accordion-content">
                        <div class="compact-grid">
                            <?php if ( !empty($grouped_data['Dewan Pengawas']) ) : ?>
                                <?php foreach( $grouped_data['Dewan Pengawas'] as $item ) : ?>
                                    <div class="compact-card">
                                        <span class="compact-label"><?php echo esc_html($item['jabatan']); ?></span>
                                        <div class="compact-name"><?php echo esc_html($item['nama']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="text-align: center; color: #888;">Data belum tersedia.</p>
                            <?php endif; ?>
                        </div>
                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Dewan Pengawas &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Dewan Pengawas:</strong><br>
                                    Dewan Pengawas mempunyai tugas melakukan pengawasan terhadap pengelolaan keuangan badan layanan umum yang dilakukan oleh Pejabat Pengelola mengenai pelaksanaan rencana strategis bisnis dan rencana bisnis anggaran sesuai dengan ketentuan peraturan perundang-undangan.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Senat Akademik -->
                <div class="accordion-item">
                    <button class="accordion-header">Senat Akademik <i class="fas fa-chevron-down"></i></button>
                    <div class="accordion-content">
                        <div class="compact-grid">
                            <?php if ( !empty($grouped_data['Senat Akademik']) ) : ?>
                                <?php foreach( $grouped_data['Senat Akademik'] as $item ) : ?>
                                    <div class="compact-card">
                                        <span class="compact-label"><?php echo esc_html($item['jabatan']); ?></span>
                                        <div class="compact-name"><?php echo esc_html($item['nama']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Senat Akademik &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Senat Akademik:</strong><br>
                                    Senat merupakan unsur penyusunan kebijakan PPI Curug yang mempunyai tugas memberikan penetapan dan pertimbangan pelaksanaan kebijakan akademik.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Ketua Program Studi -->
                <div class="accordion-item">
                    <button class="accordion-header">Ketua Program Studi <i class="fas fa-chevron-down"></i></button>
                    <div class="accordion-content">
                        <div class="compact-grid">
                            <?php if ( !empty($grouped_data['Ketua Program Studi']) ) : ?>
                                <?php foreach( $grouped_data['Ketua Program Studi'] as $item ) : ?>
                                    <div class="compact-card">
                                        <span class="compact-label"><?php echo esc_html($item['jabatan']); ?></span>
                                        <div class="compact-name"><?php echo esc_html($item['nama']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 6. Kepala Satuan & Pusat -->
                <div class="accordion-item">
                    <button class="accordion-header">Kepala Satuan &amp; Pusat <i class="fas fa-chevron-down"></i></button>
                    <div class="accordion-content">
                        <div class="compact-grid">
                            <?php if ( !empty($grouped_data['Kepala Satuan & Pusat']) ) : ?>
                                <?php foreach( $grouped_data['Kepala Satuan & Pusat'] as $item ) : ?>
                                    <div class="compact-card">
                                        <span class="compact-label"><?php echo esc_html($item['jabatan']); ?></span>
                                        <div class="compact-name"><?php echo esc_html($item['nama']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Satuan Penjaminan Mutu &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Satuan Penjaminan Mutu:</strong><br>
                                    Satuan Penjaminan Mutu merupakan unsur penjaminan mutu yang menjalankan tugas sistem penjaminan mutu sesuai dengan ketentuan peraturan perundang-undangan. Kepala dan Anggota Satuan Penjaminan Mutu merupakan pegawai yang diberi tugas untuk melaksanakan dokumentasi, pemeliharaan, pengendalian, dan pengembangan sistem penjaminan mutu sesuai dengan ketentuan peraturan perundang-undangan.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>
                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Satuan Pemeriksa Intern &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Satuan Pemeriksa Intern:</strong><br>
                                    Satuan Pemeriksaan Intern merupakan unsur pengawas yang menjalankan tugas pengawasan non akademik sesuai dengan ketentuan peraturan perundang-undangan. Satuan Pemeriksaan Intern dipimpin oleh Kepala yang berada di bawah dan bertanggung jawab kepada Direktur.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. Koordinator -->
                <div class="accordion-item">
                    <button class="accordion-header">Koordinator <i class="fas fa-chevron-down"></i></button>
                    <div class="accordion-content">
                        <div class="compact-grid">
                            <?php if ( !empty($grouped_data['Koordinator']) ) : ?>
                                <?php foreach( $grouped_data['Koordinator'] as $item ) : ?>
                                    <div class="compact-card">
                                        <span class="compact-label"><?php echo esc_html($item['jabatan']); ?></span>
                                        <div class="compact-name"><?php echo esc_html($item['nama']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Koordinator &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Koordinator:</strong><br>
                                    Melaksanakan fungsi operasional di masing-masing bidang sesuai koordinasinya (perencanaan, kepegawaian, humas, akademik, tenaga pendidik, PKL &amp; kerjasama, ketarunaan &amp; alumni) untuk mendukung kelancaran tugas pokok institusi.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 8. Kepala Unit -->
                <div class="accordion-item">
                    <button class="accordion-header">Kepala Unit <i class="fas fa-chevron-down"></i></button>
                    <div class="accordion-content">
                        <div class="compact-grid">
                            <?php if ( !empty($grouped_data['Kepala Unit']) ) : ?>
                                <?php foreach( $grouped_data['Kepala Unit'] as $item ) : ?>
                                    <div class="compact-card">
                                        <span class="compact-label"><?php echo esc_html($item['jabatan']); ?></span>
                                        <div class="compact-name"><?php echo esc_html($item['nama']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="tugas-badge">
                            <i class="fas fa-clipboard-list badge-icon"></i>
                            <div class="badge-text">
                                <div class="badge-preview">Tugas Kepala Unit &rarr; <span class="toggle-link">Baca selengkapnya</span></div>
                                <div class="badge-full" style="display:none;">
                                    <strong>Tugas Kepala Unit:</strong><br>
                                    Mengelola dan mengembangkan unit penunjang sesuai bidang masing-masing (asrama, perpustakaan, IT, bahasa, kesehatan, laboratorium, sertifikasi, pelatihan, teknik umum) untuk mendukung kegiatan akademik dan non akademik.
                                    <br><span class="toggle-link mt-2">Sembunyikan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end accordion-container -->
        </div>
    </section>

    <?php
    // Inline Script untuk Interaktivitas Accordion & Toggle Badge
    static $leadership_script_printed = false;
    if ( ! $leadership_script_printed ) {
        $leadership_script_printed = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Accordion Heights dynamically
                const initContents = document.querySelectorAll('.ppic-leadership-section .accordion-content');
                initContents.forEach(content => {
                    if(content.parentElement.classList.contains('active')) {
                        content.style.maxHeight = content.scrollHeight + "px";
                    } else {
                        content.style.maxHeight = null;
                    }
                });

                // Accordion Click Logic
                const headers = document.querySelectorAll('.ppic-leadership-section .accordion-header');
                headers.forEach(header => {
                    header.addEventListener('click', function() {
                        const item = this.parentElement;
                        const content = item.querySelector('.accordion-content');
                        const icon = this.querySelector('i');
                        
                        if (content.style.maxHeight) {
                            // Close
                            content.style.maxHeight = null;
                            item.classList.remove('active');
                            icon.style.transform = 'rotate(0deg)';
                        } else {
                            // Open
                            content.style.maxHeight = content.scrollHeight + "px";
                            item.classList.add('active');
                            icon.style.transform = 'rotate(180deg)';
                        }
                    });
                });

                // Tugas Badge "Baca Selengkapnya" Toggle Logic
                const badges = document.querySelectorAll('.ppic-leadership-section .tugas-badge');
                badges.forEach(badge => {
                    const preview = badge.querySelector('.badge-preview');
                    const full = badge.querySelector('.badge-full');
                    const links = badge.querySelectorAll('.toggle-link');
                    
                    links.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (full.style.display === 'none' || full.style.display === '') {
                                preview.style.display = 'none';
                                full.style.display = 'block';
                            } else {
                                full.style.display = 'none';
                                preview.style.display = 'block';
                            }
                            
                            // Recalculate parent accordion height dynamically so it doesn't clip
                            const parentContent = badge.closest('.accordion-content');
                            if (parentContent && parentContent.style.maxHeight) {
                                parentContent.style.maxHeight = parentContent.scrollHeight + "px";
                            }
                        });
                    });
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

// 5. Mapping ke WPBakery
add_action( 'vc_before_init', 'ppic_leadership_map' );
function ppic_leadership_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Kepemimpinan', 'ppic-custom-element' ),
            'base'     => 'ppic_leadership',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-groups',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Hitam)', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Kepemimpinan &',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'Struktur Organisasi',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Berdasarkan Peraturan Menteri Perhubungan PM 100 Tahun 2021',
                ),
                array(
                    'type'        => 'ppic_leadership_csv_upload',
                    'heading'     => __( 'Data Personel Struktur (CSV)', 'ppic-custom-element' ),
                    'param_name'  => 'spreadsheet_file',
                    'description' => __( 'Unggah CSV dengan kolom: header, jabatan, nama, foto.', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'attach_image',
                    'heading'    => __( 'Gambar Struktur Organisasi', 'ppic-custom-element' ),
                    'param_name' => 'struktur_img',
                    'description'=> __( 'Ini untuk diagram bagan struktur organisasi di tab paling atas.', 'ppic-custom-element' )
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