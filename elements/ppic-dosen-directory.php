<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_dosen_directory_split_values( $value ) {
    $value = trim( (string) $value );
    if ( '' === $value ) return array();
    $parts = preg_split( '/\r\n|\r|\n|,/', $value );
    $results = array();
    foreach ( $parts as $part ) {
        $part = trim( wp_strip_all_tags( $part ) );
        if ( '' !== $part ) $results[] = $part;
    }
    return array_values( array_unique( $results ) );
}

function ppic_dosen_directory_prepare_email_href( $value ) {
    $value = trim( (string) $value );
    if ( '' === $value ) return '';
    if ( 0 === stripos( $value, 'mailto:' ) ) return sanitize_email( substr( $value, 7 ) ) ? $value : '';
    return sanitize_email( $value ) ? 'mailto:' . sanitize_email( $value ) : '';
}

function ppic_dosen_directory_extract_google_drive_file_id( $url ) {
    $url = trim( (string) $url );
    if ( '' === $url ) return '';
    if ( preg_match( '/\/d\/([a-zA-Z0-9-_]+)/', $url, $matches ) ) return isset( $matches[1] ) ? trim( (string) $matches[1] ) : '';
    if ( preg_match( '/id=([a-zA-Z0-9-_]+)/', $url, $matches ) ) return isset( $matches[1] ) ? trim( (string) $matches[1] ) : '';
    return '';
}

function ppic_dosen_directory_normalize_photo_url( $url ) {
    $url = trim( (string) $url );
    if ( '' === $url ) return '';
    $drive_file_id = ppic_dosen_directory_extract_google_drive_file_id( $url );
    if ( '' !== $drive_file_id ) return 'https://lh3.googleusercontent.com/d/' . rawurlencode( $drive_file_id );
    return $url;
}

function ppic_dosen_directory_decode_spreadsheet_input( $value ) {
    $value = trim( (string) $value );
    if ( '' === $value ) return '';
    $decoded = rawurldecode( $value );
    $base64_value = base64_decode( $decoded, true );
    if ( false !== $base64_value && '' !== trim( $base64_value ) ) return $base64_value;
    return $decoded;
}

function ppic_dosen_directory_normalize_spreadsheet_text( $value ) {
    $value = ppic_dosen_directory_decode_spreadsheet_input( $value );
    if ( '' === $value ) return '';
    $value = html_entity_decode( $value, ENT_QUOTES, 'UTF-8' );
    $value = str_replace( array( "\r\n", "\r" ), "\n", $value );
    $value = preg_replace( '/<br\s*\/?>/i', "\n", $value );
    $value = preg_replace( '/<\/p>\s*<p[^>]*>/i', "\n", $value );
    $value = preg_replace( '/<\/div>\s*<div[^>]*>/i', "\n", $value );
    $value = preg_replace( '/<\/li>\s*<li[^>]*>/i', "\n", $value );
    $value = wp_strip_all_tags( $value );
    $value = str_replace( array( "\xC2\xA0", '&nbsp;' ), ' ', $value );
    $value = preg_replace( "/\n{3,}/", "\n\n", $value );
    return trim( $value );
}

function ppic_dosen_directory_detect_delimiter( $line ) {
    $delimiters = array( "\t" => substr_count( $line, "\t" ), ';' => substr_count( $line, ';' ), ',' => substr_count( $line, ',' ) );
    arsort( $delimiters );
    $delimiter = key( $delimiters );
    return ( is_string( $delimiter ) && $delimiters[ $delimiter ] > 0 ) ? $delimiter : ',';
}

function ppic_dosen_directory_parse_csv_line( $line, $preferred_delimiter = ',' ) {
    $candidates = array_values( array_unique( array( $preferred_delimiter, ',', ';', "\t" ) ) );
    $best_columns = array( $line );
    foreach ( $candidates as $candidate ) {
        $columns = str_getcsv( $line, $candidate );
        if ( count( $columns ) > count( $best_columns ) || ( count( $columns ) === count( $best_columns ) && $columns !== $best_columns ) ) {
            $best_columns = $columns;
        }
    }
    if ( 1 === count( $best_columns ) ) {
        $nested_line = trim( (string) $best_columns[0] );
        if ( $nested_line !== $line && preg_match( '/[,;\t]/', $nested_line ) ) {
            $nested_columns = ppic_dosen_directory_parse_csv_line( $nested_line, $preferred_delimiter );
            if ( count( $nested_columns ) > 1 ) return $nested_columns;
        }
    }
    return $best_columns;
}

function ppic_dosen_directory_map_header( $header ) {
    $header = wp_strip_all_tags( (string) $header );
    $header = preg_replace( '/^\xEF\xBB\xBF/', '', $header );
    $header = strtolower( trim( $header ) );
    $header = preg_replace( '/[^a-z0-9]+/', '_', $header );
    $header = trim( $header, '_' );

    // Aliases diperbarui agar cocok 100% dengan Header dari Google Sheet Anda
    $aliases = array(
        'name' => 'name', 'nama' => 'name', 'nama_lengkap' => 'name', 'full_name' => 'name',
        
        'jenis_tenaga' => 'jenis_tenaga', 'jenis' => 'jenis_tenaga', 'status' => 'jenis_tenaga', 'personnel_category' => 'jenis_tenaga',
        
        'asal_instansi' => 'asal_instansi', 'instansi' => 'asal_instansi', 'perusahaan' => 'asal_instansi', 'origin_institution' => 'asal_instansi',
        
        'jabatan' => 'jabatan', 'jabatan_fungsional' => 'jabatan', 'functional_status' => 'jabatan',
        
        'prodi' => 'prodi', 'program_studi' => 'prodi', 'program' => 'prodi', 'study_program' => 'prodi',
        
        'bio' => 'bio', 'bio_singkat' => 'bio', 'deskripsi' => 'bio', 'profil_singkat' => 'bio', 'short_bio' => 'bio',
        
        'pendidikan' => 'pendidikan', 'education' => 'pendidikan',
        
        'sertifikat_kompetensi' => 'sertifikat_kompetensi', 'sertifikat' => 'sertifikat_kompetensi', 'kompetensi' => 'sertifikat_kompetensi', 'certifications' => 'sertifikat_kompetensi',
        
        'kepakaran' => 'kepakaran', 'keahlian' => 'kepakaran', 'expertise' => 'kepakaran',
        
        'mata_kuliah' => 'mata_kuliah', 'matkul' => 'mata_kuliah', 'mata_kuliah_yang_diampu' => 'mata_kuliah', 'courses_taught' => 'mata_kuliah',
        
        'scholar_url' => 'scholar_url', 'google_scholar' => 'scholar_url', 'google_scholar_url' => 'scholar_url', 'scholar' => 'scholar_url',
        
        'sinta' => 'sinta_id', 'sinta_id' => 'sinta_id',
        
        'linkedin' => 'linkedin_url', 'linkedin_url' => 'linkedin_url',
        
        'email' => 'email', 'email_address' => 'email',
        
        // Membaca link GDrive dari "PHOTO_URL" di Spreadsheet
        'photo' => 'photo', 'photo_id' => 'photo', 'foto_id' => 'photo', 'photo_url' => 'photo', 'foto' => 'photo', 'foto_url' => 'photo', 'image_url' => 'photo', 
    );

    return isset( $aliases[ $header ] ) ? $aliases[ $header ] : '';
}

function ppic_dosen_directory_parse_spreadsheet( $raw_value ) {
    $raw_value = ppic_dosen_directory_normalize_spreadsheet_text( $raw_value );
    if ( '' === $raw_value ) return array();
    $raw_value = preg_replace( '/^\xEF\xBB\xBF/', '', $raw_value );
    $lines = preg_split( '/\r\n|\r|\n/', $raw_value );
    $lines = array_values( array_filter( array_map( 'trim', $lines ), static function ( $line ) { return '' !== $line; } ) );
    if ( count( $lines ) < 2 ) return array();

    $delimiter = ppic_dosen_directory_detect_delimiter( $lines[0] );
    $headers = ppic_dosen_directory_parse_csv_line( $lines[0], $delimiter );
    $mapped_headers = array_map( 'ppic_dosen_directory_map_header', $headers );

    if ( ! in_array( 'name', $mapped_headers, true ) ) return array();

    $rows = array();
    foreach ( array_slice( $lines, 1 ) as $line ) {
        $columns = ppic_dosen_directory_parse_csv_line( $line, $delimiter );
        if ( empty( array_filter( $columns, 'strlen' ) ) ) continue;
        $row = array();
        foreach ( $mapped_headers as $index => $field ) {
            if ( '' === $field ) continue;
            $row[ $field ] = isset( $columns[ $index ] ) ? trim( wp_unslash( $columns[ $index ] ) ) : '';
        }
        if ( ! empty( $row['name'] ) ) $rows[] = $row;
    }
    return $rows;
}

function ppic_dosen_directory_get_csv_attachment_contents( $attachment_id ) {
    $attachment_id = absint( $attachment_id );
    if ( ! $attachment_id || ! function_exists( 'get_attached_file' ) ) return '';
    $file_path = get_attached_file( $attachment_id );
    if ( ! $file_path || ! file_exists( $file_path ) || ! is_readable( $file_path ) ) return '';
    $extension = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );
    if ( 'csv' !== $extension && 'txt' !== $extension ) return '';
    $contents = file_get_contents( $file_path );
    return false !== $contents ? $contents : '';
}

function ppic_dosen_directory_register_csv_param() {
    if ( ! function_exists( 'vc_add_shortcode_param' ) ) return;
    vc_add_shortcode_param( 'ppic_csv_upload', 'ppic_dosen_directory_csv_upload_field' );
}
add_action( 'init', 'ppic_dosen_directory_register_csv_param', 20 );

function ppic_dosen_directory_csv_upload_field( $settings, $value ) {
    $param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
    $attachment_id = absint( $value );
    $file_label = '';
    $field_id = 'ppic-csv-upload-' . wp_rand( 1000, 999999 );

    if ( $attachment_id ) {
        $file_path = function_exists( 'get_attached_file' ) ? get_attached_file( $attachment_id ) : '';
        if ( $file_path ) $file_label = basename( $file_path );
    }

    $output = '<div id="' . esc_attr( $field_id ) . '" class="ppic-csv-upload-field">';
    $output .= '<input type="hidden" class="wpb_vc_param_value wpb-textinput ' . esc_attr( $param_name ) . ' ' . esc_attr( $settings['type'] ) . '_field" name="' . esc_attr( $param_name ) . '" value="' . esc_attr( $attachment_id ) . '">';
    $output .= '<input type="text" class="ppic-csv-upload-field__label" value="' . esc_attr( $file_label ) . '" placeholder="Belum ada file dipilih" readonly style="width:100%;margin-bottom:8px;">';
    $output .= '<button type="button" class="button button-secondary ppic-csv-upload-field__select">Pilih File CSV</button> ';
    $output .= '<button type="button" class="button ppic-csv-upload-field__clear"' . ( $attachment_id ? '' : ' style="display:none;"' ) . '>Hapus File</button>';
    $output .= '<script>(function(){var root=document.getElementById(' . wp_json_encode( $field_id ) . ');if(!root||root.dataset.ppicCsvReady){return;}root.dataset.ppicCsvReady="1";var selectButton=root.querySelector(".ppic-csv-upload-field__select");var clearButton=root.querySelector(".ppic-csv-upload-field__clear");var hiddenInput=root.querySelector(".wpb_vc_param_value");var labelInput=root.querySelector(".ppic-csv-upload-field__label");if(selectButton){selectButton.addEventListener("click",function(event){event.preventDefault();if(typeof window.wp==="undefined"||typeof window.wp.media==="undefined"){window.alert("Media Library WordPress belum siap dimuat. Refresh halaman editor lalu coba lagi.");return;}var fileFrame=window.wp.media({title:"Pilih File CSV",button:{text:"Gunakan file ini"},multiple:false});fileFrame.on("select",function(){var attachment=fileFrame.state().get("selection").first().toJSON();if(hiddenInput){hiddenInput.value=attachment.id;hiddenInput.dispatchEvent(new Event("change",{bubbles:true}));}if(labelInput){labelInput.value=attachment.filename||attachment.url||"";}if(clearButton){clearButton.style.display="";}});fileFrame.open();});}if(clearButton){clearButton.addEventListener("click",function(event){event.preventDefault();if(hiddenInput){hiddenInput.value="";hiddenInput.dispatchEvent(new Event("change",{bubbles:true}));}if(labelInput){labelInput.value="";}clearButton.style.display="none";});}}());</script>';
    $output .= '</div>';
    return $output;
}

function ppic_dosen_directory_enqueue_admin_media( $hook ) {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'ppic_dosen_directory_enqueue_admin_media' );

add_shortcode( 'ppic_dosen_directory', 'ppic_dosen_directory_render' );
function ppic_dosen_directory_render( $atts ) {
    // 1. TAMBAHKAN shortcode_atts() AGAR LABEL FILTER MUNCUL
    $atts = shortcode_atts( array(
        'data_source'          => 'manual',
        'search_placeholder'   => 'Cari nama / keahlian...',
        'sort_label'           => 'Urutkan:',
        'filter_jenis_label'   => 'JENIS TENAGA', // Ditulis kapital menyerupai mockup
        'filter_prodi_label'   => 'PROGRAM STUDI',
        'filter_jabatan_label' => 'JABATAN FUNGSIONAL',
        'no_results_text'      => 'Tidak ada dosen yang cocok dengan filter saat ini.',
        'spreadsheet_file'     => '',
        'lecturers'            => '',
        'el_id'                => '',
        'el_class'             => '',
    ), $atts );

    // DATA DUMMY BAWAAN (Hanya sampel kecil untuk contoh, gunakan data asli Anda di sini)
    $dummy_lecturers = array(
        array( 'name' => 'Agus Prabowo, A.Md.', 'jenis_tenaga' => 'Laboran', 'jabatan' => 'Laboran', 'prodi' => 'Teknik Mekanikal Bandar Udara', 'bio' => 'Laboran di bidang Teknik Mekanikal Bandar Udara.', 'pendidikan' => "S2 Teknik Penerbangan", 'sertifikat_kompetensi' => "Sertifikat HVAC", 'kepakaran' => "HVAC", 'mata_kuliah' => "HVAC Bandara", 'scholar_url' => '', 'sinta_id' => '311234', 'linkedin_url' => '', 'email' => 'agus.prabowo@ppicurug.ac.id' ),
        array( 'name' => 'Agus Prabowo, A.Md.', 'jenis_tenaga' => 'Laboran', 'jabatan' => 'Laboran', 'prodi' => 'Teknik Mekanikal Bandar Udara', 'bio' => 'Laboran di bidang Teknik Mekanikal Bandar Udara. Pengalaman mengajar lebih dari 18 tahun. Berpengalaman dalam HVAC. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - UGM\nS1 Teknik Mekanikal Bandar Udara - UGM", 'sertifikat_kompetensi' => "Sertifikat HVAC Bandara\nSertifikat Eskalator & Travelator\nSertifikat Mekanikal Penerbangan", 'kepakaran' => "HVAC\neskalator\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "HVAC Bandara\nEskalator & Travelator\nSistem Mekanikal", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '311234', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'agus.prabowo@ppicurug.ac.id' ),
        array( 'name' => 'Ahmad Kosasih, S.T., M.T', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Listrik Bandara', 'bio' => 'Dosen tetap di bidang Teknik Listrik Bandara. Pengalaman mengajar lebih dari 18 tahun. Berpengalaman dalam Sistem tenaga bandara. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - ITB\nS1 Teknik Listrik Bandara - ITB", 'sertifikat_kompetensi' => "Sertifikat Instalasi Listrik Bandara\nSertifikat Sistem Tenaga Penerbangan\nSertifikat K3 Listrik", 'kepakaran' => "Sistem tenaga bandara\nInstalasi\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Sistem Tenaga Bandara\nInstalasi Listrik Penerbangan\nK3 Listrik", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300000', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'ahmad.kosasih@ppicurug.ac.id' ),
        array( 'name' => 'Alwazir Abdushomad, S.Pdi, M.Pdi', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Pertolongan Kecelakaan Pesawat', 'bio' => 'Dosen tetap di bidang Pertolongan Kecelakaan Pesawat. Pengalaman mengajar lebih dari 12 tahun. Berpengalaman dalam Rescue & fire fighting. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UNS\nS1 Pertolongan Kecelakaan Pesawat - UNS", 'sertifikat_kompetensi' => "Sertifikat Rescue & Fire Fighting\nSertifikat Penanganan Kedaruratan\nSertifikat Pertolongan Pertama", 'kepakaran' => "Rescue & fire fighting\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Rescue & Fire Fighting\nPenanganan Kedaruratan\nPertolongan Pertama", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300137', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'alwazir.abdushomad@ppicurug.ac.id' ),
        array( 'name' => 'Andri Kurniawan, S.ST., M.T', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Pesawat Udara', 'bio' => 'Dosen tetap di bidang Teknik Pesawat Udara. Pengalaman mengajar lebih dari 16 tahun. Berpengalaman dalam Struktur pesawat. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - UGM\nS1 Teknik Pesawat Udara - UGM", 'sertifikat_kompetensi' => "Sertifikat Struktur Pesawat\nSertifikat Powerplant\nSertifikat Avionik Dasar", 'kepakaran' => "Struktur pesawat\nPowerplant\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Struktur Pesawat Udara\nPowerplant\nAvionik Dasar", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300274', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'andri.kurniawan@ppicurug.ac.id' ),
        array( 'name' => 'Benny Kurnianto, S.Pd., M.T', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Mekanikal Bandar Udara', 'bio' => 'Dosen tetap di bidang Teknik Mekanikal Bandar Udara. Pengalaman mengajar lebih dari 5 tahun. Berpengalaman dalam HVAC. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - UGM\nS1 Teknik Mekanikal Bandar Udara - UGM", 'sertifikat_kompetensi' => "Sertifikat HVAC Bandara\nSertifikat Eskalator & Travelator", 'kepakaran' => "HVAC\neskalator\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "HVAC Bandara\nEskalator & Travelator", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300411', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'benny.kurnianto@ppicurug.ac.id' ),
        array( 'name' => 'Bhima Shakti Arrafat, S.ST, MS.ASM', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Pesawat Udara', 'bio' => 'Dosen tetap di bidang Teknik Pesawat Udara. Pengalaman mengajar lebih dari 16 tahun. Berpengalaman dalam Struktur pesawat. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - UGM\nS1 Teknik Pesawat Udara - UGM", 'sertifikat_kompetensi' => "Sertifikat Struktur Pesawat\nSertifikat Powerplant\nSertifikat Avionik Dasar", 'kepakaran' => "Struktur pesawat\nPowerplant\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Struktur Pesawat Udara\nPowerplant\nAvionik Dasar", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300548', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'bhima.arrafat@ppicurug.ac.id' ),
        array( 'name' => 'Capt. Budi Santoso, M.Av.', 'jenis_tenaga' => 'Dosen Praktisi', 'asal_instansi' => 'Lion Air', 'jabatan' => 'Asisten Ahli', 'prodi' => 'Penerbang', 'bio' => 'Praktisi dari Lion Air di bidang Penerbang. Pengalaman mengajar lebih dari 9 tahun. Berpengalaman dalam CRM. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - STP\nS1 Penerbang - STP", 'sertifikat_kompetensi' => "Sertifikat CRM\nSertifikat Flight Safety", 'kepakaran' => "CRM\nFlight safety\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "CRM\nFlight Safety\nMeteorologi Penerbangan", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '309864', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'capt.santoso@ppicurug.ac.id' ),
        array( 'name' => 'Capt. Heru Prasetyo, M.M.', 'jenis_tenaga' => 'Dosen Praktisi', 'asal_instansi' => 'Garuda Indonesia', 'jabatan' => 'Lektor', 'prodi' => 'Penerbang', 'bio' => 'Praktisi dari Garuda Indonesia di bidang Penerbang. Pengalaman mengajar lebih dari 19 tahun. Berpengalaman dalam CRM. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - STP\nS1 Penerbang - STP", 'sertifikat_kompetensi' => "Sertifikat CRM\nSertifikat Flight Safety", 'kepakaran' => "CRM\nFlight safety\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "CRM\nFlight Safety\nMeteorologi Penerbangan", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '309316', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'capt.prasetyo@ppicurug.ac.id' ),
        array( 'name' => 'Deni Sapta Nugraha, S.Pd., M.Pd', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Operasi Bandar Udara', 'bio' => 'Dosen tetap di bidang Operasi Bandar Udara. Pengalaman mengajar lebih dari 21 tahun. Berpengalaman dalam Manajemen terminal. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UI\nS1 Operasi Bandar Udara - UI", 'sertifikat_kompetensi' => "Sertifikat Manajemen Terminal\nSertifikat Ground Handling", 'kepakaran' => "Manajemen terminal\nGround handling\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Manajemen Terminal\nGround Handling\nKeselamatan Operasional", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300685', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'deni.nugraha@ppicurug.ac.id' ),
        array( 'name' => 'Dewi Sartika, A.Md.', 'jenis_tenaga' => 'Instruktur', 'jabatan' => 'Instruktur', 'prodi' => 'Penerangan Aeronautika', 'bio' => 'Instruktur ahli di bidang Penerangan Aeronautika. Pengalaman mengajar lebih dari 15 tahun. Berpengalaman dalam Komunikasi penerbangan. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UI\nS1 Penerangan Aeronautika - UI", 'sertifikat_kompetensi' => "Sertifikat Komunikasi Penerbangan\nSertifikat Radio Navigasi", 'kepakaran' => "Komunikasi penerbangan\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Komunikasi Penerbangan\nRadio Navigasi\nPenerangan Aerodrome", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '310823', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dewi.sartika@ppicurug.ac.id' ),
        array( 'name' => 'Dian Kartika Ramadanis, S.E., M.A.', 'jenis_tenaga' => 'Dosen Tidak Tetap', 'jabatan' => 'Asisten Ahli', 'prodi' => 'Penerbang', 'bio' => 'Dosen tidak tetap di bidang Penerbang. Pengalaman mengajar lebih dari 14 tahun. Berpengalaman dalam CRM. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - STP\nS1 Penerbang - STP", 'sertifikat_kompetensi' => "Sertifikat CRM\nSertifikat Flight Safety\nSertifikat Meteorologi Penerbangan", 'kepakaran' => "CRM\nFlight safety\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "CRM\nFlight Safety", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300822', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dian.ramadanis@ppicurug.ac.id' ),
        array( 'name' => 'Dini Wagini, S.IP., MA', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Penerangan Aeronautika', 'bio' => 'Dosen tetap di bidang Penerangan Aeronautika. Pengalaman mengajar lebih dari 10 tahun. Berpengalaman dalam Komunikasi penerbangan. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UI\nS1 Penerangan Aeronautika - UI", 'sertifikat_kompetensi' => "Sertifikat Komunikasi Penerbangan\nSertifikat Radio Navigasi\nSertifikat Penerangan Aerodrome", 'kepakaran' => "Komunikasi penerbangan\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Komunikasi Penerbangan\nRadio Navigasi\nPenerangan Aerodrome", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '300959', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dini.wagini@ppicurug.ac.id' ),
        array( 'name' => 'Dody Wahyu Widodo S.Si.T., M.Si', 'jenis_tenaga' => 'Dosen Tidak Tetap', 'jabatan' => 'Asisten Ahli', 'prodi' => 'Penerbang', 'bio' => 'Dosen tidak tetap di bidang Penerbang. Pengalaman mengajar lebih dari 12 tahun. Berpengalaman dalam CRM. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - STP\nS1 Penerbang - STP", 'sertifikat_kompetensi' => "Sertifikat CRM\nSertifikat Flight Safety\nSertifikat Meteorologi Penerbangan", 'kepakaran' => "CRM\nFlight safety\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "CRM\nFlight Safety\nMeteorologi Penerbangan", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '301096', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dody.widodo@ppicurug.ac.id' ),
        array( 'name' => 'Dr MOHAMMAD ANDRA ADITIYAWARMAN, S.T, M.T.', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Bangunan dan Landasan', 'bio' => 'Dosen tetap di bidang Teknik Bangunan dan Landasan. Pengalaman mengajar lebih dari 7 tahun. Berpengalaman dalam Perkerasan runway. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - ITS\nS1 Teknik Bangunan dan Landasan - ITS", 'sertifikat_kompetensi' => "Sertifikat Perkerasan Runway\nSertifikat Drainase Bandara", 'kepakaran' => "Perkerasan runway\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Perkerasan Runway\nDrainase Bandara\nKonstruksi Penerbangan", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '301233', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.aditiyawarman@ppicurug.ac.id' ),
        array( 'name' => 'Dr. Agoes Soebagio, SH., DESS', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Operasi Bandar Udara', 'bio' => 'Dosen tetap di bidang Operasi Bandar Udara. Pengalaman mengajar lebih dari 19 tahun. Berpengalaman dalam Manajemen terminal. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UI\nS1 Operasi Bandar Udara - UI", 'sertifikat_kompetensi' => "Sertifikat Manajemen Terminal\nSertifikat Ground Handling", 'kepakaran' => "Manajemen terminal\nGround handling\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Manajemen Terminal\nGround Handling\nKeselamatan Operasional", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '301370', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.soebagio@ppicurug.ac.id' ),
        array( 'name' => 'Dr. Dhian Supardam, SE., MM', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor Kepala', 'prodi' => 'Lalu Lintas Udara', 'bio' => 'Dosen tetap di bidang Lalu Lintas Udara. Pengalaman mengajar lebih dari 8 tahun. Berpengalaman dalam ATC prosedur. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - ITS\nS1 Lalu Lintas Udara - ITS", 'sertifikat_kompetensi' => "Sertifikat ATC Prosedur\nSertifikat Radar & Surveillance\nSertifikat Manajemen Lalu Lintas Udara", 'kepakaran' => "ATC prosedur\nRadar\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "ATC Prosedur\nRadar & Surveillance", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '301507', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.supardam@ppicurug.ac.id' ),
        array( 'name' => 'Dr. Dian Anggraini P., S.SiT, MT', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Navigasi Udara', 'bio' => 'Dosen tetap di bidang Teknik Navigasi Udara. Pengalaman mengajar lebih dari 18 tahun. Berpengalaman dalam Sistem navigasi. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - ITB\nS1 Teknik Navigasi Udara - ITB", 'sertifikat_kompetensi' => "Sertifikat Sistem Navigasi\nSertifikat ILS\nSertifikat GNSS", 'kepakaran' => "Sistem navigasi\nILS\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Sistem Navigasi\nILS\nGNSS", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '301644', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.dian.p@ppicurug.ac.id' ),
        array( 'name' => 'Dr. Didik Sulistyo Kurniawan, S.T., Msi', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Navigasi Udara', 'bio' => 'Dosen tetap di bidang Teknik Navigasi Udara. Pengalaman mengajar lebih dari 13 tahun. Berpengalaman dalam Sistem navigasi. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - ITB\nS1 Teknik Navigasi Udara - ITB", 'sertifikat_kompetensi' => "Sertifikat Sistem Navigasi\nSertifikat ILS", 'kepakaran' => "Sistem navigasi\nILS\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Sistem Navigasi\nILS\nGNSS", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '301781', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.kurniawan@ppicurug.ac.id' ),
        array( 'name' => 'Dr. Feti Fatonah, SE, M.Si', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor Kepala', 'prodi' => 'Teknik Navigasi Udara', 'bio' => 'Dosen tetap di bidang Teknik Navigasi Udara. Pengalaman mengajar lebih dari 8 tahun. Berpengalaman dalam Sistem navigasi. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - ITB\nS1 Teknik Navigasi Udara - ITB", 'sertifikat_kompetensi' => "Sertifikat Sistem Navigasi\nSertifikat ILS\nSertifikat GNSS", 'kepakaran' => "Sistem navigasi\nILS\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Sistem Navigasi\nILS", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '301918', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.fatonah@ppicurug.ac.id' ),
        array( 'name' => 'Dr. Muizuddn Azka, ST., MT', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Teknik Navigasi Udara', 'bio' => 'Dosen tetap di bidang Teknik Navigasi Udara. Pengalaman mengajar lebih dari 16 tahun. Berpengalaman dalam Sistem navigasi. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Teknik Penerbangan - ITB\nS1 Teknik Navigasi Udara - ITB", 'sertifikat_kompetensi' => "Sertifikat Sistem Navigasi\nSertifikat ILS\nSertifikat GNSS", 'kepakaran' => "Sistem navigasi\nILS\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Sistem Navigasi\nILS\nGNSS", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '302055', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.azka@ppicurug.ac.id' ),
        array( 'name' => 'Dr. Nawang Kalbuana, SE, M.Ak', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor Kepala', 'prodi' => 'Pertolongan Kecelakaan Pesawat', 'bio' => 'Dosen tetap di bidang Pertolongan Kecelakaan Pesawat. Pengalaman mengajar lebih dari 7 tahun. Berpengalaman dalam Rescue & fire fighting. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UNS\nS1 Pertolongan Kecelakaan Pesawat - UNS", 'sertifikat_kompetensi' => "Sertifikat Rescue & Fire Fighting\nSertifikat Penanganan Kedaruratan", 'kepakaran' => "Rescue & fire fighting\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Rescue & Fire Fighting\nPenanganan Kedaruratan\nPertolongan Pertama", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '302192', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'dr.kalbuana@ppicurug.ac.id' ),
        array( 'name' => 'Drs. Agus Setiawan, M.T.', 'jenis_tenaga' => 'Dosen Praktisi', 'asal_instansi' => 'AP I', 'jabatan' => 'Asisten Ahli', 'prodi' => 'Operasi Bandar Udara', 'bio' => 'Praktisi dari AP I di bidang Operasi Bandar Udara. Pengalaman mengajar lebih dari 14 tahun. Berpengalaman dalam Manajemen terminal. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UI\nS1 Operasi Bandar Udara - UI", 'sertifikat_kompetensi' => "Sertifikat Manajemen Terminal\nSertifikat Ground Handling\nSertifikat Keselamatan Operasional", 'kepakaran' => "Manajemen terminal\nGround handling\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Manajemen Terminal\nGround Handling", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '309590', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'drs.setiawan@ppicurug.ac.id' ),
        array( 'name' => 'Drs. Sundoro, M.Si', 'jenis_tenaga' => 'Dosen Tetap', 'jabatan' => 'Lektor', 'prodi' => 'Operasi Bandar Udara', 'bio' => 'Dosen tetap di bidang Operasi Bandar Udara. Pengalaman mengajar lebih dari 7 tahun. Berpengalaman dalam Manajemen terminal. Aktif dalam pembinaan mahasiswa vokasi.', 'pendidikan' => "S2 Manajemen Penerbangan - UI\nS1 Operasi Bandar Udara - UI", 'sertifikat_kompetensi' => "Sertifikat Manajemen Terminal\nSertifikat Ground Handling", 'kepakaran' => "Manajemen terminal\nGround handling\nInstruktur ahli\nPublikasi ilmiah", 'mata_kuliah' => "Manajemen Terminal\nGround Handling\nKeselamatan Operasional", 'scholar_url' => 'https://scholar.google.com/', 'sinta_id' => '302329', 'linkedin_url' => 'https://linkedin.com/', 'email' => 'drs.sundoro@ppicurug.ac.id' ),
    );

    $lecturers = array();
    $parsed_lecturers = vc_param_group_parse_atts( $atts['lecturers'] );
    $has_valid = false;
    if ( is_array( $parsed_lecturers ) ) {
        foreach ( $parsed_lecturers as $l ) {
            if ( ! empty( $l['name'] ) ) {
                $has_valid = true;
                break;
            }
        }
    }

    if ( 'spreadsheet' === $atts['data_source'] && ! empty( $atts['spreadsheet_file'] ) ) {
        $lecturers = ppic_dosen_directory_parse_spreadsheet( ppic_dosen_directory_get_csv_attachment_contents( $atts['spreadsheet_file'] ) );
    } elseif ( $has_valid ) {
        $lecturers = $parsed_lecturers;
    } else {
        $lecturers = $dummy_lecturers; 
    }

    if ( empty( $lecturers ) || ! is_array( $lecturers ) ) {
        return '';
    }

    $items = array();
    $jenis_options = array();
    $prodi_options = array();
    $jabatan_options = array();

    foreach ( $lecturers as $lecturer ) {
        $name = isset( $lecturer['name'] ) ? trim( $lecturer['name'] ) : '';
        if ( '' === $name ) continue;

        $jenis_tenaga = isset( $lecturer['jenis_tenaga'] ) ? trim( $lecturer['jenis_tenaga'] ) : 'Dosen Tetap';
        $asal_instansi = isset( $lecturer['asal_instansi'] ) ? trim( $lecturer['asal_instansi'] ) : '';
        $jabatan = isset( $lecturer['jabatan'] ) ? trim( $lecturer['jabatan'] ) : '';
        $prodi = isset( $lecturer['prodi'] ) ? trim( $lecturer['prodi'] ) : '';
        $bio = isset( $lecturer['bio'] ) ? trim( $lecturer['bio'] ) : '';
        
        $pendidikan = ppic_dosen_directory_split_values( isset( $lecturer['pendidikan'] ) ? $lecturer['pendidikan'] : '' );
        $sertifikat_kompetensi = ppic_dosen_directory_split_values( isset( $lecturer['sertifikat_kompetensi'] ) ? $lecturer['sertifikat_kompetensi'] : '' );
        $kepakaran = ppic_dosen_directory_split_values( isset( $lecturer['kepakaran'] ) ? $lecturer['kepakaran'] : '' );
        $mata_kuliah = ppic_dosen_directory_split_values( isset( $lecturer['mata_kuliah'] ) ? $lecturer['mata_kuliah'] : '' );
        
        $scholar_url = isset( $lecturer['scholar_url'] ) ? esc_url( trim( $lecturer['scholar_url'] ) ) : '';
        $sinta_id = isset( $lecturer['sinta_id'] ) ? trim( $lecturer['sinta_id'] ) : '';
        $linkedin_url = isset( $lecturer['linkedin_url'] ) ? esc_url( trim( $lecturer['linkedin_url'] ) ) : '';
        $email = isset( $lecturer['email'] ) ? trim( $lecturer['email'] ) : '';
        $email_href = ppic_dosen_directory_prepare_email_href( $email );
        
        $photo_source = isset( $lecturer['photo'] ) ? trim( (string) $lecturer['photo'] ) : '';
        $photo_id = ctype_digit( $photo_source ) ? absint( $photo_source ) : 0;
        $photo_url = '';

        if ( $photo_id ) {
            $photo_data = wp_get_attachment_image_src( $photo_id, 'medium' );
            if ( ! empty( $photo_data[0] ) ) {
                $photo_url = $photo_data[0];
            }
        } elseif ( '' !== $photo_source ) {
            $photo_url = esc_url( ppic_dosen_directory_normalize_photo_url( $photo_source ) );
        }

        if ( '' !== $jenis_tenaga ) $jenis_options[] = $jenis_tenaga;
        if ( '' !== $prodi ) $prodi_options[] = $prodi;
        if ( '' !== $jabatan ) $jabatan_options[] = $jabatan;

        $items[] = array(
            'name' => $name, 'jenis_tenaga' => $jenis_tenaga, 'asal_instansi' => $asal_instansi,
            'jabatan' => $jabatan, 'prodi' => $prodi, 'bio' => $bio, 'pendidikan' => $pendidikan,
            'sertifikat_kompetensi' => $sertifikat_kompetensi, 'kepakaran' => $kepakaran,
            'mata_kuliah' => $mata_kuliah, 'scholar_url' => $scholar_url, 'sinta_id' => $sinta_id,
            'linkedin_url' => $linkedin_url, 'email' => $email, 'email_href' => $email_href,
            'photo_url' => $photo_url,
            'search_blob' => strtolower( implode( ' ', array_merge( array( $name, $jabatan, $prodi, $bio, $jenis_tenaga, $asal_instansi ), $pendidikan, $kepakaran, $mata_kuliah ) ) ),
        );
    }

    if ( empty( $items ) ) return '';

    natcasesort( $jenis_options ); natcasesort( $prodi_options ); natcasesort( $jabatan_options );
    $jenis_options = array_values( array_unique( $jenis_options ) );
    $prodi_options = array_values( array_unique( $prodi_options ) );
    $jabatan_options = array_values( array_unique( $jabatan_options ) );

    $directory_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-dosen-directory-' ) : uniqid( 'ppic-dosen-directory-' );
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-dosen-directory-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    
    // 2. BATAS ITEM YANG DITAMPILKAN SEBELUM DI-HIDE
    $visible_limit = 4;

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" data-dosen-directory id="<?php echo esc_attr( $directory_id ); ?>">
        <div class="discovery-container">
            <aside class="filters-sidebar">
                <button class="filter-mobile-toggle" aria-label="Buka filter">
                    <span> Filter &amp; Cari</span>
                    <i class="fas fa-chevron-down chevron-icon"></i>
                </button>

                <div class="filter-content-wrapper">
                    <div class="search-input ppic-dosen-directory__search" style="margin-bottom: 24px;">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <input type="text" class="ppic-dosen-directory__search-input" placeholder="<?php echo esc_attr( $atts['search_placeholder'] ); ?>">
                    </div>

                    <!-- FILTER JENIS TENAGA -->
                    <div class="filter-group ppic-dosen-filter-group is-open">
                        <h3 class="ppic-dosen-filter-toggle">
                            <span><?php echo esc_html( strtoupper($atts['filter_jenis_label']) ); ?></span>
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                        </h3>
                        <div class="filter-options ppic-dosen-filter-options">
                            <?php foreach ( $jenis_options as $index => $option ) : ?>
                                <label class="filter-item-wrapper <?php echo $index >= $visible_limit ? 'is-hidden toggleable' : ''; ?>">
                                    <input type="checkbox" class="ppic-dosen-filter-checkbox filter-checkbox" data-filter="jenis" value="<?php echo esc_attr( $option ); ?>">
                                    <span><?php echo esc_html( $option ); ?></span>
                                </label>
                            <?php endforeach; ?>
                            <?php if ( count( $jenis_options ) > $visible_limit ) : ?>
                                <button type="button" class="show-all-toggle" data-expanded="false">
                                    <span class="text-show">Show all <?php echo count($jenis_options); ?></span>
                                    <span class="text-hide" style="display:none;">Show less</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- FILTER PROGRAM STUDI -->
                    <div class="filter-group ppic-dosen-filter-group is-open">
                        <h3 class="ppic-dosen-filter-toggle">
                            <span><?php echo esc_html( strtoupper($atts['filter_prodi_label']) ); ?></span>
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                        </h3>
                        <div class="filter-options ppic-dosen-filter-options">
                            <?php foreach ( $prodi_options as $index => $option ) : ?>
                                <label class="filter-item-wrapper <?php echo $index >= $visible_limit ? 'is-hidden toggleable' : ''; ?>">
                                    <input type="checkbox" class="ppic-dosen-filter-checkbox filter-checkbox" data-filter="prodi" value="<?php echo esc_attr( $option ); ?>">
                                    <span><?php echo esc_html( $option ); ?></span>
                                </label>
                            <?php endforeach; ?>
                            <?php if ( count( $prodi_options ) > $visible_limit ) : ?>
                                <button type="button" class="show-all-toggle" data-expanded="false">
                                    <span class="text-show">Show all <?php echo count($prodi_options); ?></span>
                                    <span class="text-hide" style="display:none;">Show less</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- FILTER JABATAN FUNGSIONAL -->
                    <?php if(!empty($jabatan_options)): ?>
                    <div class="filter-group ppic-dosen-filter-group is-open">
                        <h3 class="ppic-dosen-filter-toggle">
                            <span><?php echo esc_html( strtoupper($atts['filter_jabatan_label']) ); ?></span>
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                        </h3>
                        <div class="filter-options ppic-dosen-filter-options">
                            <?php foreach ( $jabatan_options as $index => $option ) : ?>
                                <label class="filter-item-wrapper <?php echo $index >= $visible_limit ? 'is-hidden toggleable' : ''; ?>">
                                    <input type="checkbox" class="ppic-dosen-filter-checkbox filter-checkbox" data-filter="jabatan" value="<?php echo esc_attr( $option ); ?>">
                                    <span><?php echo esc_html( $option ); ?></span>
                                </label>
                            <?php endforeach; ?>
                            <?php if ( count( $jabatan_options ) > $visible_limit ) : ?>
                                <button type="button" class="show-all-toggle" data-expanded="false">
                                    <span class="text-show">Show all <?php echo count($jabatan_options); ?></span>
                                    <span class="text-hide" style="display:none;">Show less</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- MAIN RESULTS (SAMA SEPERTI KODE SEBELUMNYA) -->
            <div class="results-area">
                <div class="results-header">
                    <div class="result-count ppic-dosen-directory__count">Menampilkan <?php echo esc_html( count( $items ) ); ?> dari <?php echo esc_html( count( $items ) ); ?> tenaga pendidik &amp; kependidikan</div>
                    <div class="ppic-dosen-directory__sort-wrap">
                        <label style="font-size: 0.8rem; margin-right: 8px"><?php echo esc_html( $atts['sort_label'] ); ?></label>
                        <select class="sort-select ppic-dosen-directory__sort">
                            <option value="nameAsc">Nama (A-Z)</option>
                            <option value="nameDesc">Nama (Z-A)</option>
                        </select>
                    </div>
                </div>

                <div class="lecturer-grid ppic-dosen-directory__grid">
                    <?php foreach ( $items as $idx => $item ) : ?>
                        <div class="lecturer-card ppic-dosen-card" data-name="<?php echo esc_attr( strtolower( $item['name'] ) ); ?>" data-jenis="<?php echo esc_attr( strtolower( $item['jenis_tenaga'] ) ); ?>" data-jabatan="<?php echo esc_attr( strtolower( $item['jabatan'] ) ); ?>" data-prodi="<?php echo esc_attr( strtolower( $item['prodi'] ) ); ?>" data-search="<?php echo esc_attr( $item['search_blob'] ); ?>">
                            <div class="card-top">
                                <div class="card-left">
                                    <div class="card-avatar">
                                        <?php if ( ! empty( $item['photo_url'] ) ) : ?>
                                            <img src="<?php echo esc_url( $item['photo_url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                                        <?php else : ?>
                                            <i class="fas fa-user-tie" aria-hidden="true"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="name-wrap">
                                        <div class="lecturer-name"><?php echo esc_html( $item['name'] ); ?></div>
                                        <div class="card-sub">
                                            <?php if ( ! empty( $item['jabatan'] ) ) : ?><span class="badge-rank"><i class="fas fa-flask" aria-hidden="true"></i> <?php echo esc_html( $item['jabatan'] ); ?></span><?php endif; ?>
                                            <?php if ( ! empty( $item['prodi'] ) ) : ?><span class="lecturer-dept"><i class="fas fa-building" aria-hidden="true"></i> <?php echo esc_html( $item['prodi'] ); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-top-right">
                                    <?php if ( ! empty( $item['jenis_tenaga'] ) ) : ?><span class="badge-category-dosen"><i class="fas fa-flask"></i> <?php echo esc_html( $item['jenis_tenaga'] ); ?></span><?php endif; ?>
                                    <?php if ( ! empty( $item['asal_instansi'] ) ) : ?><span class="badge-institusi"><i class="fas fa-building"></i> <?php echo esc_html( $item['asal_instansi'] ); ?></span><?php endif; ?>
                                </div>
                            </div>

                            <?php if ( ! empty( $item['bio'] ) ) : ?><div class="short-bio"><?php echo esc_html( $item['bio'] ); ?></div><?php endif; ?>

                            <?php if ( ! empty( $item['pendidikan'] ) ) : ?>
                                <div class="detail-section">
                                    <div class="detail-label"><i class="fas fa-graduation-cap" aria-hidden="true"></i> PENDIDIKAN</div>
                                    <div class="badge-container"><?php foreach ( $item['pendidikan'] as $tag ) : ?><span class="badge-standard"><i class="fas fa-graduation-cap" aria-hidden="true"></i> <?php echo esc_html( $tag ); ?></span><?php endforeach; ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['sertifikat_kompetensi'] ) ) : ?>
                                <div class="detail-section">
                                    <div class="detail-label"><i class="fas fa-award" aria-hidden="true"></i> SERTIFIKAT KOMPETENSI</div>
                                    <div class="badge-container"><?php foreach ( $item['sertifikat_kompetensi'] as $tag ) : ?><span class="badge-standard"><i class="fas fa-certificate" aria-hidden="true"></i> <?php echo esc_html( $tag ); ?></span><?php endforeach; ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['kepakaran'] ) ) : ?>
                                <div class="detail-section">
                                    <div class="detail-label"><i class="fas fa-microchip" aria-hidden="true"></i> KEPAKARAN</div>
                                    <div class="expertise-container"><?php foreach ( $item['kepakaran'] as $tag ) : ?><span class="badge-standard"><i class="fas fa-microchip" aria-hidden="true"></i> <?php echo esc_html( $tag ); ?></span><?php endforeach; ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $item['mata_kuliah'] ) ) : ?>
                                <div class="detail-section">
                                    <div class="detail-label courses-toggle" data-target="courses-<?php echo esc_attr($idx); ?>">
                                        <span><i class="fas fa-book" aria-hidden="true"></i> MATA KULIAH YANG DIAMPU <i class="fas fa-chevron-down arrow-toggle" style="margin-left: 8px;"></i></span>
                                    </div>
                                    <div class="courses-list badge-container" id="courses-<?php echo esc_attr($idx); ?>" style="display:none; margin-top: 10px;">
                                        <?php foreach ( $item['mata_kuliah'] as $tag ) : ?><span class="badge-standard"><i class="fas fa-book-open" aria-hidden="true"></i> <?php echo esc_html( $tag ); ?></span><?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="contact-row">
                                <div class="academic-ids">
                                    <?php if ( ! empty( $item['scholar_url'] ) ) : ?><a href="<?php echo esc_url( $item['scholar_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="academic-link">Google Scholar</a><?php endif; ?>
                                    <?php if ( ! empty( $item['sinta_id'] ) ) : ?><span class="academic-link"><i class="fas fa-scroll" aria-hidden="true"></i> SINTA: <?php echo esc_html( $item['sinta_id'] ); ?></span><?php endif; ?>
                                </div>
                                <div class="contact-links">
                                    <?php if ( ! empty( $item['linkedin_url'] ) ) : ?><a href="<?php echo esc_url( $item['linkedin_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="contact-link"><i class="fab fa-linkedin" aria-hidden="true"></i> LinkedIn</a><?php endif; ?>
                                    <?php if ( ! empty( $item['email_href'] ) ) : ?><a href="<?php echo esc_url( $item['email_href'] ); ?>" class="contact-link"><i class="fas fa-envelope" aria-hidden="true"></i> Email</a><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="ppic-dosen-directory__empty" hidden><?php echo esc_html( $atts['no_results_text'] ); ?></div>
            </div>
        </div>
    </section>
    <?php

    static $script_printed = false;
    if ( ! $script_printed ) {
        $script_printed = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var directories = document.querySelectorAll('[data-dosen-directory]');
                directories.forEach(function (directory) {
                    var searchInput = directory.querySelector('.ppic-dosen-directory__search-input');
                    var checkboxes = directory.querySelectorAll('.ppic-dosen-filter-checkbox');
                    var sortSelect = directory.querySelector('.ppic-dosen-directory__sort');
                    var resultCount = directory.querySelector('.ppic-dosen-directory__count');
                    var grid = directory.querySelector('.ppic-dosen-directory__grid');
                    var emptyState = directory.querySelector('.ppic-dosen-directory__empty');
                    var cards = Array.prototype.slice.call(directory.querySelectorAll('.ppic-dosen-card'));
                    var totalItems = cards.length;

                    // Fitur Accordion Sidebar Filter Mobile
                    var mobileToggle = directory.querySelector('.filter-mobile-toggle');
                    var filterContent = directory.querySelector('.filter-content-wrapper');
                    if (mobileToggle && filterContent) {
                        mobileToggle.addEventListener('click', function() {
                            filterContent.classList.toggle('is-open');
                            var chevron = this.querySelector('.chevron-icon');
                            if (chevron) { chevron.style.transform = filterContent.classList.contains('is-open') ? 'rotate(180deg)' : 'rotate(0deg)'; }
                        });
                    }

                    // Fitur "Show all X" pada Filter
                    directory.querySelectorAll('.show-all-toggle').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            var isExpanded = this.dataset.expanded === 'true';
                            this.dataset.expanded = !isExpanded;
                            
                            var parent = this.closest('.ppic-dosen-filter-options');
                            var hiddenItems = parent.querySelectorAll('.filter-item-wrapper.toggleable');
                            
                            hiddenItems.forEach(function(item) {
                                if (isExpanded) {
                                    item.classList.add('is-hidden');
                                } else {
                                    item.classList.remove('is-hidden');
                                }
                            });
                            
                            var textShow = this.querySelector('.text-show');
                            var textHide = this.querySelector('.text-hide');
                            
                            if (isExpanded) {
                                textShow.style.display = 'inline';
                                textHide.style.display = 'none';
                            } else {
                                textShow.style.display = 'none';
                                textHide.style.display = 'inline';
                            }
                        });
                    });

                    // Fitur Toggle Mata Kuliah
                    directory.querySelectorAll('.courses-toggle').forEach(function(toggle) {
                        toggle.addEventListener('click', function() {
                            var targetId = this.getAttribute('data-target');
                            var targetDiv = document.getElementById(targetId);
                            var arrow = this.querySelector('.arrow-toggle');
                            if (targetDiv) {
                                if (targetDiv.style.display === 'none') {
                                    targetDiv.style.display = 'flex';
                                    if(arrow) arrow.style.transform = 'rotate(180deg)';
                                } else {
                                    targetDiv.style.display = 'none';
                                    if(arrow) arrow.style.transform = 'rotate(0deg)';
                                }
                            }
                        });
                    });

                    var getSelectedMap = function () {
                        var selected = { jenis: [], prodi: [], jabatan: [] };
                        checkboxes.forEach(function (checkbox) {
                            if (checkbox.checked && selected[checkbox.dataset.filter]) {
                                selected[checkbox.dataset.filter].push((checkbox.value || '').toLowerCase());
                            }
                        });
                        return selected;
                    };

                    var matchesFilter = function (card, values, key) {
                        if (!values.length) return true;
                        return values.indexOf((card.dataset[key] || '').toLowerCase()) !== -1;
                    };

                    var updateDirectory = function () {
                        var selected = getSelectedMap();
                        var query = ((searchInput && searchInput.value) || '').trim().toLowerCase();
                        var visibleCards = [];

                        cards.forEach(function (card) {
                            var haystack = (card.dataset.search || '').toLowerCase();
                            var isVisible = matchesFilter(card, selected.jenis, 'jenis')
                                && matchesFilter(card, selected.prodi, 'prodi')
                                && matchesFilter(card, selected.jabatan, 'jabatan')
                                && (!query || haystack.indexOf(query) !== -1);

                            card.hidden = !isVisible;
                            card.style.display = isVisible ? 'flex' : 'none';
                            if (isVisible) visibleCards.push(card);
                        });

                        visibleCards.sort(function (leftCard, rightCard) {
                            var leftName = leftCard.dataset.name || '';
                            var rightName = rightCard.dataset.name || '';
                            var direction = sortSelect && sortSelect.value === 'nameDesc' ? -1 : 1;
                            return leftName.localeCompare(rightName) * direction;
                        });

                        visibleCards.forEach(function (card) { grid.appendChild(card); });

                        if (resultCount) { resultCount.textContent = 'Menampilkan ' + visibleCards.length + ' dari ' + totalItems + ' tenaga pendidik & kependidikan'; }
                        if (emptyState) { emptyState.hidden = visibleCards.length > 0; }
                    };

                    if (searchInput) searchInput.addEventListener('input', updateDirectory);
                    checkboxes.forEach(function (checkbox) { checkbox.addEventListener('change', updateDirectory); });
                    if (sortSelect) sortSelect.addEventListener('change', updateDirectory);

                    directory.querySelectorAll('.ppic-dosen-filter-toggle').forEach(function (toggleButton) {
                        toggleButton.addEventListener('click', function () {
                            this.parentElement.classList.toggle('is-open');
                        });
                    });

                    updateDirectory();
                });
            });
        </script>
        <?php
    }
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_dosen_directory_map' );
function ppic_dosen_directory_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Dosen Directory', 'ppic-custom-element' ),
            'base' => 'ppic_dosen_directory',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-groups',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Sumber Data', 'ppic-custom-element' ),
                    'param_name' => 'data_source',
                    'value' => array(
                        __( 'Input Manual', 'ppic-custom-element' ) => 'manual',
                        __( 'Import CSV', 'ppic-custom-element' ) => 'spreadsheet',
                    ),
                    'std' => 'manual',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Placeholder Pencarian', 'ppic-custom-element' ),
                    'param_name' => 'search_placeholder',
                    'value' => 'Cari nama / keahlian...',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Sort', 'ppic-custom-element' ),
                    'param_name' => 'sort_label',
                    'value' => 'Urutkan:',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Filter Jenis Tenaga', 'ppic-custom-element' ),
                    'param_name' => 'filter_jenis_label',
                    'value' => 'Jenis Tenaga',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Filter Program Studi', 'ppic-custom-element' ),
                    'param_name' => 'filter_prodi_label',
                    'value' => 'Program Studi',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Filter Jabatan', 'ppic-custom-element' ),
                    'param_name' => 'filter_jabatan_label',
                    'value' => 'Jabatan Fungsional',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks Empty State', 'ppic-custom-element' ),
                    'param_name' => 'no_results_text',
                    'value' => 'Tidak ada dosen yang cocok dengan filter saat ini.',
                ),
                array(
                    'type' => 'ppic_csv_upload',
                    'heading' => __( 'File CSV', 'ppic-custom-element' ),
                    'param_name' => 'spreadsheet_file',
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => array( 'spreadsheet' ),
                    ),
                    'description' => __( 'Upload file CSV hasil export dari Excel. Header yang didukung: name, jenis_tenaga, asal_instansi, jabatan, prodi, bio, pendidikan, sertifikat_kompetensi, kepakaran, mata_kuliah, scholar_url, sinta_id, linkedin_url, email, photo.', 'ppic-custom-element' ),
                ),
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Daftar Dosen', 'ppic-custom-element' ),
                    'param_name' => 'lecturers',
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => array( 'manual' ),
                    ),
                    'params' => array(
                        array(
                            'type' => 'attach_image',
                            'heading' => __( 'Foto Dosen', 'ppic-custom-element' ),
                            'param_name' => 'photo',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Nama Lengkap', 'ppic-custom-element' ),
                            'param_name' => 'name',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Jenis Tenaga', 'ppic-custom-element' ),
                            'param_name' => 'jenis_tenaga',
                            'value' => array(
                                'Dosen Tetap' => 'Dosen Tetap',
                                'Dosen Tidak Tetap' => 'Dosen Tidak Tetap',
                                'Dosen Praktisi' => 'Dosen Praktisi',
                                'Instruktur' => 'Instruktur',
                                'Laboran' => 'Laboran',
                            ),
                            'std' => 'Dosen Tetap',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Asal Instansi (Opsional, Khusus Praktisi)', 'ppic-custom-element' ),
                            'param_name' => 'asal_instansi',
                            'description' => 'Contoh: Lion Air, AP I, Garuda Indonesia',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Jabatan Fungsional (Opsional)', 'ppic-custom-element' ),
                            'param_name' => 'jabatan',
                            'description' => 'Kosongkan jika tidak memiliki jabatan fungsional.',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Program Studi', 'ppic-custom-element' ),
                            'param_name' => 'prodi',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Bio Singkat', 'ppic-custom-element' ),
                            'param_name' => 'bio',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Pendidikan', 'ppic-custom-element' ),
                            'param_name' => 'pendidikan',
                            'description' => __( 'Pisahkan per baris atau koma. Contoh: S2 Teknik Penerbangan - UGM, S1...', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Sertifikat Kompetensi', 'ppic-custom-element' ),
                            'param_name' => 'sertifikat_kompetensi',
                            'description' => __( 'Pisahkan per baris atau koma.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Kepakaran', 'ppic-custom-element' ),
                            'param_name' => 'kepakaran',
                            'description' => __( 'Pisahkan per baris atau koma.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Mata Kuliah', 'ppic-custom-element' ),
                            'param_name' => 'mata_kuliah',
                            'description' => __( 'Pisahkan per baris atau koma.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Google Scholar URL', 'ppic-custom-element' ),
                            'param_name' => 'scholar_url',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'SINTA ID', 'ppic-custom-element' ),
                            'param_name' => 'sinta_id',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'LinkedIn URL', 'ppic-custom-element' ),
                            'param_name' => 'linkedin_url',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Email', 'ppic-custom-element' ),
                            'param_name' => 'email',
                        ),
                    ),
                ),
                array(
                    'type' => 'el_id',
                    'heading' => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                ),
            ),
        )
    );
}