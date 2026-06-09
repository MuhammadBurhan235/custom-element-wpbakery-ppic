<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_dosen_directory_split_values( $value ) {
    $value = trim( (string) $value );

    if ( '' === $value ) {
        return array();
    }

    $parts = preg_split( '/\r\n|\r|\n|,/', $value );
    $results = array();

    foreach ( $parts as $part ) {
        $part = trim( wp_strip_all_tags( $part ) );

        if ( '' !== $part ) {
            $results[] = $part;
        }
    }

    return array_values( array_unique( $results ) );
}

function ppic_dosen_directory_prepare_email_href( $value ) {
    $value = trim( (string) $value );

    if ( '' === $value ) {
        return '';
    }

    if ( 0 === stripos( $value, 'mailto:' ) ) {
        return sanitize_email( substr( $value, 7 ) ) ? $value : '';
    }

    return sanitize_email( $value ) ? 'mailto:' . sanitize_email( $value ) : '';
}

function ppic_dosen_directory_extract_google_drive_file_id( $url ) {
    $url = trim( (string) $url );

    if ( '' === $url ) {
        return '';
    }

    // Pola regex universal yang lebih aman untuk menangkap karakter Alfanumerik ID Google Drive
    if ( preg_match( '/\/d\/([a-zA-Z0-9-_]+)/', $url, $matches ) ) {
        return isset( $matches[1] ) ? trim( (string) $matches[1] ) : '';
    }
    
    if ( preg_match( '/id=([a-zA-Z0-9-_]+)/', $url, $matches ) ) {
        return isset( $matches[1] ) ? trim( (string) $matches[1] ) : '';
    }

    return '';
}

function ppic_dosen_directory_normalize_photo_url( $url ) {
    $url = trim( (string) $url );

    if ( '' === $url ) {
        return '';
    }

    $drive_file_id = ppic_dosen_directory_extract_google_drive_file_id( $url );

    if ( '' !== $drive_file_id ) {
        // Mengembalikan URL langsung yang dapat dirender oleh browser
        return 'https://lh3.googleusercontent.com/d/' . rawurlencode( $drive_file_id );
    }

    return $url;
}

function ppic_dosen_directory_decode_spreadsheet_input( $value ) {
    $value = trim( (string) $value );

    if ( '' === $value ) {
        return '';
    }

    $decoded = rawurldecode( $value );
    $base64_value = base64_decode( $decoded, true );

    if ( false !== $base64_value && '' !== trim( $base64_value ) ) {
        return $base64_value;
    }

    return $decoded;
}

function ppic_dosen_directory_normalize_spreadsheet_text( $value ) {
    $value = ppic_dosen_directory_decode_spreadsheet_input( $value );

    if ( '' === $value ) {
        return '';
    }

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
    $delimiters = array(
        "\t" => substr_count( $line, "\t" ),
        ';' => substr_count( $line, ';' ),
        ',' => substr_count( $line, ',' ),
    );

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

            if ( count( $nested_columns ) > 1 ) {
                return $nested_columns;
            }
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

    $aliases = array(
        'name' => 'name',
        'nama' => 'name',
        'nama_lengkap' => 'name',
        'full_name' => 'name',
        'jabatan' => 'jabatan',
        'jabatan_fungsional' => 'jabatan',
        'prodi' => 'prodi',
        'program_studi' => 'prodi',
        'program' => 'prodi',
        'bio' => 'bio',
        'bio_singkat' => 'bio',
        'deskripsi' => 'bio',
        'profil_singkat' => 'bio',
        'lama_mengajar' => 'lama_mengajar',
        'pengalaman_mengajar' => 'lama_mengajar',
        'masa_mengajar' => 'lama_mengajar',
        'pendidikan' => 'pendidikan',
        'education' => 'pendidikan',
        'kepakaran' => 'kepakaran',
        'keahlian' => 'kepakaran',
        'expertise' => 'kepakaran',
        'scholar_url' => 'scholar_url',
        'google_scholar' => 'scholar_url',
        'google_scholar_url' => 'scholar_url',
        'scholar' => 'scholar_url',
        'sinta' => 'sinta_id',
        'sinta_id' => 'sinta_id',
        'linkedin' => 'linkedin_url',
        'linkedin_url' => 'linkedin_url',
        'email' => 'email',
        'email_address' => 'email',
        'sertifikasi' => 'sertifikasi',
        'sertifikasi_pendidik' => 'sertifikasi',
        'photo' => 'photo',
        'photo_id' => 'photo',
        'foto_id' => 'photo',
        'photo_url' => 'photo',
        'foto' => 'photo',
        'foto_url' => 'photo',
        'image_url' => 'photo',
    );

    return isset( $aliases[ $header ] ) ? $aliases[ $header ] : '';
}

function ppic_dosen_directory_parse_spreadsheet( $raw_value ) {
    $raw_value = ppic_dosen_directory_normalize_spreadsheet_text( $raw_value );

    if ( '' === $raw_value ) {
        return array();
    }

    $raw_value = preg_replace( '/^\xEF\xBB\xBF/', '', $raw_value );
    $lines = preg_split( '/\r\n|\r|\n/', $raw_value );
    $lines = array_values(
        array_filter(
            array_map( 'trim', $lines ),
            static function ( $line ) {
                return '' !== $line;
            }
        )
    );

    if ( count( $lines ) < 2 ) {
        return array();
    }

    $delimiter = ppic_dosen_directory_detect_delimiter( $lines[0] );
    $headers = ppic_dosen_directory_parse_csv_line( $lines[0], $delimiter );
    $mapped_headers = array_map( 'ppic_dosen_directory_map_header', $headers );

    if ( ! in_array( 'name', $mapped_headers, true ) ) {
        return array();
    }

    $rows = array();

    foreach ( array_slice( $lines, 1 ) as $line ) {
        $columns = ppic_dosen_directory_parse_csv_line( $line, $delimiter );

        if ( empty( array_filter( $columns, 'strlen' ) ) ) {
            continue;
        }

        $row = array();

        foreach ( $mapped_headers as $index => $field ) {
            if ( '' === $field ) {
                continue;
            }

            $row[ $field ] = isset( $columns[ $index ] ) ? trim( wp_unslash( $columns[ $index ] ) ) : '';
        }

        if ( ! empty( $row['name'] ) ) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function ppic_dosen_directory_get_csv_attachment_contents( $attachment_id ) {
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

function ppic_dosen_directory_register_csv_param() {
    if ( ! function_exists( 'vc_add_shortcode_param' ) ) {
        return;
    }

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

        if ( $file_path ) {
            $file_label = basename( $file_path );
        }
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
    $atts = shortcode_atts(
        array(
            'data_source' => 'manual',
            'search_placeholder' => 'Cari nama / keahlian...',
            'sort_label' => 'Urutkan:',
            'filter_prodi_label' => 'Program Studi',
            'filter_jabatan_label' => 'Jabatan Fungsional',
            'filter_sertifikasi_label' => 'Sertifikasi Pendidik',
            'no_results_text' => 'Tidak ada dosen yang cocok dengan filter saat ini.',
            'spreadsheet_file' => '',
            'lecturers' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'name' => 'Ahmad Kosasih, S.T., M.T',
                            'jabatan' => 'Lektor',
                            'prodi' => 'Teknik Listrik Bandara',
                            'bio' => 'Dosen Teknik Listrik Bandara dengan pengalaman mengajar lebih dari 21 tahun. Berpengalaman dalam sistem tenaga bandara. Aktif dalam penelitian terapan dan pembinaan mahasiswa vokasi.',
                            'lama_mengajar' => '21 tahun (sejak 2009)',
                            'pendidikan' => "S2 Teknik Penerbangan\nS1 Teknik Listrik Bandara\nSertifikasi Pendidik",
                            'kepakaran' => "Sistem tenaga bandara\nInstruktur ahli\nPublikasi ilmiah\nBersertifikat pendidik",
                            'scholar_url' => 'https://scholar.google.com/citations?user=ahmadkosasih13',
                            'sinta_id' => '300000',
                            'linkedin_url' => 'https://linkedin.com/in/ahmadkosasih13',
                            'email' => 'ahmad.kosasih@ppicurug.ac.id',
                            'sertifikasi' => 'Ya',
                        ),
                        array(
                            'name' => 'Zulina Kurniawati, S.SiT, M.Si',
                            'jabatan' => 'Lektor',
                            'prodi' => 'Teknik Mekanikal Bandar Udara',
                            'bio' => 'Dosen Teknik Mekanikal Bandar Udara dengan pengalaman mengajar lebih dari 18 tahun. Berpengalaman dalam HVAC. Aktif dalam penelitian terapan dan pembinaan mahasiswa vokasi.',
                            'lama_mengajar' => '18 tahun (sejak 2018)',
                            'pendidikan' => "S2 Teknik Penerbangan\nS1 Teknik Mekanikal Bandar Udara\nSertifikasi Pendidik",
                            'kepakaran' => "HVAC\nInstruktur ahli\nPublikasi ilmiah\nBersertifikat pendidik",
                            'scholar_url' => 'https://scholar.google.com/citations?user=zulinakurniawati10',
                            'sinta_id' => '309179',
                            'linkedin_url' => 'https://linkedin.com/in/zulinakurniawati10',
                            'email' => 'zulina.kurniawati@ppicurug.ac.id',
                            'sertifikasi' => 'Ya',
                        ),
                    )
                )
            ),
            'el_id' => '',
            'el_class' => '',
        ),
        $atts
    );

    $lecturers = array();

    if ( 'spreadsheet' === $atts['data_source'] ) {
        if ( ! empty( $atts['spreadsheet_file'] ) ) {
            $lecturers = ppic_dosen_directory_parse_spreadsheet( ppic_dosen_directory_get_csv_attachment_contents( $atts['spreadsheet_file'] ) );
        }
    } elseif ( empty( $lecturers ) ) {
        $lecturers = vc_param_group_parse_atts( $atts['lecturers'] );
    }

    if ( empty( $lecturers ) || ! is_array( $lecturers ) ) {
        return '';
    }

    $items = array();
    $prodi_options = array();
    $jabatan_options = array();
    $sertifikasi_options = array();

    foreach ( $lecturers as $lecturer ) {
        $name = isset( $lecturer['name'] ) ? trim( $lecturer['name'] ) : '';

        if ( '' === $name ) {
            continue;
        }

        $jabatan = isset( $lecturer['jabatan'] ) ? trim( $lecturer['jabatan'] ) : '';
        $prodi = isset( $lecturer['prodi'] ) ? trim( $lecturer['prodi'] ) : '';
        $bio = isset( $lecturer['bio'] ) ? trim( $lecturer['bio'] ) : '';
        $lama_mengajar = isset( $lecturer['lama_mengajar'] ) ? trim( $lecturer['lama_mengajar'] ) : '';
        $pendidikan = ppic_dosen_directory_split_values( isset( $lecturer['pendidikan'] ) ? $lecturer['pendidikan'] : '' );
        $kepakaran = ppic_dosen_directory_split_values( isset( $lecturer['kepakaran'] ) ? $lecturer['kepakaran'] : '' );
        $scholar_url = isset( $lecturer['scholar_url'] ) ? esc_url( trim( $lecturer['scholar_url'] ) ) : '';
        $sinta_id = isset( $lecturer['sinta_id'] ) ? trim( $lecturer['sinta_id'] ) : '';
        $linkedin_url = isset( $lecturer['linkedin_url'] ) ? esc_url( trim( $lecturer['linkedin_url'] ) ) : '';
        $email = isset( $lecturer['email'] ) ? trim( $lecturer['email'] ) : '';
        $email_href = ppic_dosen_directory_prepare_email_href( $email );
        $sertifikasi = isset( $lecturer['sertifikasi'] ) && 'Tidak' === trim( $lecturer['sertifikasi'] ) ? 'Tidak' : 'Ya';
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

        if ( '' !== $prodi ) {
            $prodi_options[] = $prodi;
        }

        if ( '' !== $jabatan ) {
            $jabatan_options[] = $jabatan;
        }

        $sertifikasi_options[] = $sertifikasi;

        $items[] = array(
            'name' => $name,
            'jabatan' => $jabatan,
            'prodi' => $prodi,
            'bio' => $bio,
            'lama_mengajar' => $lama_mengajar,
            'pendidikan' => $pendidikan,
            'kepakaran' => $kepakaran,
            'scholar_url' => $scholar_url,
            'sinta_id' => $sinta_id,
            'linkedin_url' => $linkedin_url,
            'email' => $email,
            'email_href' => $email_href,
            'sertifikasi' => $sertifikasi,
            'photo_url' => $photo_url,
            'search_blob' => strtolower( implode( ' ', array_merge( array( $name, $jabatan, $prodi, $bio ), $pendidikan, $kepakaran ) ) ),
        );
    }

    if ( empty( $items ) ) {
        return '';
    }

    natcasesort( $prodi_options );
    natcasesort( $jabatan_options );
    $prodi_options = array_values( array_unique( $prodi_options ) );
    $jabatan_options = array_values( array_unique( $jabatan_options ) );
    $sertifikasi_options = array_values( array_unique( $sertifikasi_options ) );
    sort( $sertifikasi_options );

    $directory_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-dosen-directory-' ) : uniqid( 'ppic-dosen-directory-' );
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-dosen-directory-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" data-dosen-directory id="<?php echo esc_attr( $directory_id ); ?>">
        <div class="ppic-dosen-directory__container">
            <aside class="ppic-dosen-directory__sidebar">
                <div class="ppic-dosen-directory__search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="text" class="ppic-dosen-directory__search-input" placeholder="<?php echo esc_attr( $atts['search_placeholder'] ); ?>">
                </div>

                <div class="ppic-dosen-filter-group is-open">
                    <button type="button" class="ppic-dosen-filter-toggle">
                        <span><?php echo esc_html( $atts['filter_prodi_label'] ); ?></span>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="ppic-dosen-filter-options">
                        <?php foreach ( $prodi_options as $option ) : ?>
                            <label>
                                <input type="checkbox" class="ppic-dosen-filter-checkbox" data-filter="prodi" value="<?php echo esc_attr( $option ); ?>">
                                <span><?php echo esc_html( $option ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="ppic-dosen-filter-group is-open">
                    <button type="button" class="ppic-dosen-filter-toggle">
                        <span><?php echo esc_html( $atts['filter_jabatan_label'] ); ?></span>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="ppic-dosen-filter-options">
                        <?php foreach ( $jabatan_options as $option ) : ?>
                            <label>
                                <input type="checkbox" class="ppic-dosen-filter-checkbox" data-filter="jabatan" value="<?php echo esc_attr( $option ); ?>">
                                <span><?php echo esc_html( $option ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="ppic-dosen-filter-group is-open">
                    <button type="button" class="ppic-dosen-filter-toggle">
                        <span><?php echo esc_html( $atts['filter_sertifikasi_label'] ); ?></span>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="ppic-dosen-filter-options">
                        <?php foreach ( $sertifikasi_options as $option ) : ?>
                            <label>
                                <input type="checkbox" class="ppic-dosen-filter-checkbox" data-filter="sertifikasi" value="<?php echo esc_attr( $option ); ?>">
                                <span><?php echo 'Ya' === $option ? 'Sudah Sertifikasi' : 'Belum Sertifikasi'; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <div class="ppic-dosen-directory__results">
                <div class="ppic-dosen-directory__results-header">
                    <div class="ppic-dosen-directory__count">Menampilkan <?php echo esc_html( count( $items ) ); ?> dari <?php echo esc_html( count( $items ) ); ?> dosen &amp; instruktur</div>
                    <label class="ppic-dosen-directory__sort-wrap">
                        <span><?php echo esc_html( $atts['sort_label'] ); ?></span>
                        <select class="ppic-dosen-directory__sort">
                            <option value="nameAsc">Nama (A-Z)</option>
                            <option value="nameDesc">Nama (Z-A)</option>
                        </select>
                    </label>
                </div>

                <div class="ppic-dosen-directory__grid">
                    <?php foreach ( $items as $item ) : ?>
                        <article
                            class="ppic-dosen-card"
                            data-name="<?php echo esc_attr( strtolower( $item['name'] ) ); ?>"
                            data-jabatan="<?php echo esc_attr( strtolower( $item['jabatan'] ) ); ?>"
                            data-prodi="<?php echo esc_attr( strtolower( $item['prodi'] ) ); ?>"
                            data-sertifikasi="<?php echo esc_attr( strtolower( $item['sertifikasi'] ) ); ?>"
                            data-search="<?php echo esc_attr( $item['search_blob'] ); ?>"
                        >
                            <div class="ppic-dosen-card__header">
                                <div class="ppic-dosen-card__avatar">
                                    <?php if ( ! empty( $item['photo_url'] ) ) : ?>
                                        <img src="<?php echo esc_url( $item['photo_url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                                    <?php else : ?>
                                        <i class="fas fa-user-tie" aria-hidden="true"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="ppic-dosen-card__headcopy">
                                    <h3 class="ppic-dosen-card__name"><?php echo esc_html( $item['name'] ); ?></h3>
                                    <div class="ppic-dosen-card__badges">
                                        <?php if ( ! empty( $item['jabatan'] ) ) : ?>
                                            <span class="ppic-dosen-card__badge is-title"><?php echo esc_html( $item['jabatan'] ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $item['prodi'] ) ) : ?>
                                            <span class="ppic-dosen-card__badge is-dept"><i class="fas fa-building" aria-hidden="true"></i><?php echo esc_html( $item['prodi'] ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ( ! empty( $item['bio'] ) ) : ?>
                                <p class="ppic-dosen-card__bio"><?php echo esc_html( $item['bio'] ); ?></p>
                            <?php endif; ?>

                            <div class="ppic-dosen-card__details">
                                <?php if ( ! empty( $item['lama_mengajar'] ) ) : ?>
                                    <div class="ppic-dosen-card__detail">
                                        <span class="ppic-dosen-card__label"><i class="fas fa-chalkboard" aria-hidden="true"></i>Lama Mengajar</span>
                                        <span class="ppic-dosen-card__value"><?php echo esc_html( $item['lama_mengajar'] ); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $item['pendidikan'] ) ) : ?>
                                    <div class="ppic-dosen-card__detail">
                                        <span class="ppic-dosen-card__label"><i class="fas fa-graduation-cap" aria-hidden="true"></i>Pendidikan</span>
                                        <div class="ppic-dosen-card__tags is-education">
                                            <?php foreach ( $item['pendidikan'] as $tag ) : ?>
                                                <span class="ppic-dosen-card__tag is-education"><?php echo esc_html( $tag ); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ( ! empty( $item['kepakaran'] ) ) : ?>
                                <div class="ppic-dosen-card__expertise">
                                    <span class="ppic-dosen-card__label"><i class="fas fa-microchip" aria-hidden="true"></i>Kepakaran</span>
                                    <div class="ppic-dosen-card__tags">
                                        <?php foreach ( $item['kepakaran'] as $tag ) : ?>
                                            <span class="ppic-dosen-card__tag"><?php echo esc_html( $tag ); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="ppic-dosen-card__footer">
                                <div class="ppic-dosen-card__footer-primary">
                                    <div class="ppic-dosen-card__academic-links">
                                        <?php if ( ! empty( $item['scholar_url'] ) ) : ?>
                                            <a href="<?php echo esc_url( $item['scholar_url'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-graduation-cap" aria-hidden="true"></i>Google Scholar</a>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $item['sinta_id'] ) ) : ?>
                                            <span><i class="fas fa-scroll" aria-hidden="true"></i>SINTA: <?php echo esc_html( $item['sinta_id'] ); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="ppic-dosen-card__certification<?php echo 'Ya' === $item['sertifikasi'] ? '' : ' is-pending'; ?>">
                                        <i class="fas fa-certificate" aria-hidden="true"></i>
                                        <span><?php echo 'Ya' === $item['sertifikasi'] ? 'Bersertifikasi Pendidik' : 'Belum Sertifikasi Pendidik'; ?></span>
                                    </div>
                                </div>

                                <div class="ppic-dosen-card__contact-links">
                                    <?php if ( ! empty( $item['linkedin_url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $item['linkedin_url'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin" aria-hidden="true"></i>LinkedIn</a>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $item['email_href'] ) ) : ?>
                                        <a href="<?php echo esc_url( $item['email_href'] ); ?>"><i class="fas fa-envelope" aria-hidden="true"></i>Email</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
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

                    var getSelectedMap = function () {
                        var selected = {
                            prodi: [],
                            jabatan: [],
                            sertifikasi: []
                        };

                        checkboxes.forEach(function (checkbox) {
                            if (checkbox.checked && selected[checkbox.dataset.filter]) {
                                selected[checkbox.dataset.filter].push((checkbox.value || '').toLowerCase());
                            }
                        });

                        return selected;
                    };

                    var matchesFilter = function (card, values, key) {
                        if (!values.length) {
                            return true;
                        }

                        return values.indexOf((card.dataset[key] || '').toLowerCase()) !== -1;
                    };

                    var updateDirectory = function () {
                        var selected = getSelectedMap();
                        var query = ((searchInput && searchInput.value) || '').trim().toLowerCase();
                        var visibleCards = [];

                        cards.forEach(function (card) {
                            var haystack = (card.dataset.search || '').toLowerCase();
                            var isVisible = matchesFilter(card, selected.prodi, 'prodi')
                                && matchesFilter(card, selected.jabatan, 'jabatan')
                                && matchesFilter(card, selected.sertifikasi, 'sertifikasi')
                                && (!query || haystack.indexOf(query) !== -1);

                            card.hidden = !isVisible;

                            if (isVisible) {
                                visibleCards.push(card);
                            }
                        });

                        visibleCards.sort(function (leftCard, rightCard) {
                            var leftName = leftCard.dataset.name || '';
                            var rightName = rightCard.dataset.name || '';
                            var direction = sortSelect && sortSelect.value === 'nameDesc' ? -1 : 1;
                            return leftName.localeCompare(rightName) * direction;
                        });

                        visibleCards.forEach(function (card) {
                            grid.appendChild(card);
                        });

                        if (resultCount) {
                            resultCount.textContent = 'Menampilkan ' + visibleCards.length + ' dari ' + totalItems + ' dosen & instruktur';
                        }

                        if (emptyState) {
                            emptyState.hidden = visibleCards.length > 0;
                        }
                    };

                    if (searchInput) {
                        searchInput.addEventListener('input', updateDirectory);
                    }

                    checkboxes.forEach(function (checkbox) {
                        checkbox.addEventListener('change', updateDirectory);
                    });

                    if (sortSelect) {
                        sortSelect.addEventListener('change', updateDirectory);
                    }

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
                    'heading' => __( 'Label Filter Sertifikasi', 'ppic-custom-element' ),
                    'param_name' => 'filter_sertifikasi_label',
                    'value' => 'Sertifikasi Pendidik',
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
                    'description' => __( 'Upload file CSV atau TXT hasil export dari Excel. Header yang didukung: name, jabatan, prodi, bio, lama_mengajar, pendidikan, kepakaran, scholar_url, sinta_id, linkedin_url, email, sertifikasi, photo atau photo_url. photo_url bisa memakai URL gambar langsung atau link Google Drive publik. File .xlsx belum diparse langsung.', 'ppic-custom-element' ),
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
                            'type' => 'textfield',
                            'heading' => __( 'Jabatan Fungsional', 'ppic-custom-element' ),
                            'param_name' => 'jabatan',
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
                            'type' => 'textfield',
                            'heading' => __( 'Lama Mengajar', 'ppic-custom-element' ),
                            'param_name' => 'lama_mengajar',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Pendidikan', 'ppic-custom-element' ),
                            'param_name' => 'pendidikan',
                            'description' => __( 'Pisahkan per baris atau koma.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Kepakaran', 'ppic-custom-element' ),
                            'param_name' => 'kepakaran',
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
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Sertifikasi Pendidik', 'ppic-custom-element' ),
                            'param_name' => 'sertifikasi',
                            'value' => array(
                                __( 'Ya', 'ppic-custom-element' ) => 'Ya',
                                __( 'Tidak', 'ppic-custom-element' ) => 'Tidak',
                            ),
                            'std' => 'Ya',
                        ),
                    ),
                ),
                array(
                    'type' => 'el_id',
                    'heading' => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}