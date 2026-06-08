<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_training_catalog_split_values( $value ) {
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

function ppic_training_catalog_slugify( $value ) {
    $value = strtolower( trim( wp_strip_all_tags( (string) $value ) ) );
    $value = preg_replace( '/[^a-z0-9]+/', '-', $value );
    $value = trim( (string) $value, '-' );

    return '' !== $value ? $value : 'umum';
}

function ppic_training_catalog_decode_spreadsheet_input( $value ) {
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

function ppic_training_catalog_normalize_spreadsheet_text( $value ) {
    $value = ppic_training_catalog_decode_spreadsheet_input( $value );

    if ( '' === $value ) {
        return '';
    }

    $value = html_entity_decode( $value, ENT_QUOTES, 'UTF-8' );

    if ( function_exists( 'mb_detect_encoding' ) && function_exists( 'mb_convert_encoding' ) ) {
        $encoding = mb_detect_encoding( $value, array( 'UTF-8', 'UTF-16LE', 'UTF-16BE', 'Windows-1252', 'ISO-8859-1' ), true );

        if ( $encoding && 'UTF-8' !== $encoding ) {
            $value = mb_convert_encoding( $value, 'UTF-8', $encoding );
        }
    }

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

function ppic_training_catalog_detect_delimiter( $line ) {
    $delimiters = array(
        "\t" => substr_count( $line, "\t" ),
        ';' => substr_count( $line, ';' ),
        ',' => substr_count( $line, ',' ),
    );

    arsort( $delimiters );
    $delimiter = key( $delimiters );

    return ( is_string( $delimiter ) && $delimiters[ $delimiter ] > 0 ) ? $delimiter : ',';
}

function ppic_training_catalog_parse_csv_line( $line, $preferred_delimiter = ',' ) {
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
            $nested_columns = ppic_training_catalog_parse_csv_line( $nested_line, $preferred_delimiter );

            if ( count( $nested_columns ) > 1 ) {
                return $nested_columns;
            }
        }
    }

    return $best_columns;
}

function ppic_training_catalog_map_header( $header ) {
    $header = wp_strip_all_tags( (string) $header );
    $header = preg_replace( '/^\xEF\xBB\xBF/', '', $header );
    $header = strtolower( trim( $header ) );
    $header = preg_replace( '/[^a-z0-9]+/', '_', $header );
    $header = trim( $header, '_' );

    $aliases = array(
        'title' => 'title',
        'name' => 'title',
        'nama_pelatihan' => 'title',
        'pelatihan' => 'title',
        'category' => 'category',
        'category_name' => 'category',
        'kategori' => 'category',
        'category_slug' => 'category_slug',
        'kategori_slug' => 'category_slug',
        'slug' => 'category_slug',
        'certification' => 'certification',
        'cert_text' => 'certification',
        'sertifikasi' => 'certification',
        'sertifikasi_text' => 'certification',
        'description' => 'description',
        'desc' => 'description',
        'deskripsi' => 'description',
        'summary' => 'description',
        'inquiry_url' => 'inquiry_url',
        'whatsapp_url' => 'inquiry_url',
        'link' => 'inquiry_url',
        'url' => 'inquiry_url',
        'contact_url' => 'inquiry_url',
        'inquiry_label' => 'inquiry_label',
        'button_label' => 'inquiry_label',
        'label_tombol' => 'inquiry_label',
    );

    return isset( $aliases[ $header ] ) ? $aliases[ $header ] : '';
}

function ppic_training_catalog_parse_spreadsheet( $raw_value ) {
    $raw_value = ppic_training_catalog_normalize_spreadsheet_text( $raw_value );

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

    $delimiter = ppic_training_catalog_detect_delimiter( $lines[0] );
    $headers = ppic_training_catalog_parse_csv_line( $lines[0], $delimiter );
    $mapped_headers = array_map( 'ppic_training_catalog_map_header', $headers );

    if ( ! in_array( 'title', $mapped_headers, true ) || ! in_array( 'category', $mapped_headers, true ) ) {
        return array();
    }

    $rows = array();

    foreach ( array_slice( $lines, 1 ) as $line ) {
        $columns = ppic_training_catalog_parse_csv_line( $line, $delimiter );

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

        if ( ! empty( $row['title'] ) && ! empty( $row['category'] ) ) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function ppic_training_catalog_build_whatsapp_url( $title, $number ) {
    $number = preg_replace( '/[^0-9]/', '', (string) $number );

    if ( '' === $number ) {
        return '';
    }

    $message = sprintf(
        'Halo PPI Curug, saya tertarik dengan pelatihan: "%s". Mohon info lebih lanjut.',
        trim( (string) $title )
    );

    return 'https://wa.me/' . $number . '?text=' . rawurlencode( $message );
}

add_shortcode( 'ppic_training_catalog', 'ppic_training_catalog_render' );
function ppic_training_catalog_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'data_source' => 'manual',
            'search_placeholder' => 'Cari pelatihan (nama/deskripsi)...',
            'filter_label' => 'Filter Kategori',
            'all_label' => 'Semua Pelatihan',
            'result_text' => 'Menampilkan %1$s dari %2$s pelatihan',
            'empty_title' => 'Tidak menemukan pelatihan yang Anda cari?',
            'empty_description' => 'Hubungi tim kami untuk rekomendasi pelatihan yang paling sesuai dengan kebutuhan lembaga atau personel Anda.',
            'empty_button_text' => 'Hubungi Kami via WhatsApp',
            'empty_button_url' => '',
            'default_whatsapp_number' => '6285156120178',
            'default_inquiry_label' => 'Tanya via WhatsApp',
            'spreadsheet_file' => '',
            'trainings' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'title' => 'BASIC/ GUARD AVIATION SECURITY (Initial dan Recurrent)',
                            'category' => 'Aviation Security (AVSEC)',
                            'category_slug' => 'avsec',
                            'certification' => 'Sertifikasi Resmi Kemenhub',
                            'description' => 'Pelatihan dasar personel pengamanan bandara: inspeksi akses, pemeriksaan orang/barang, prosedur siaga darurat.',
                            'inquiry_url' => '',
                            'inquiry_label' => 'Tanya via WhatsApp',
                        ),
                        array(
                            'title' => 'Category A1.3 Airplane Piston Maintenance',
                            'category' => 'AMTO / CASR 147',
                            'category_slug' => 'casr147',
                            'certification' => 'Sertifikasi Resmi Kemenhub',
                            'description' => 'Perawatan pesawat bermesin piston (airplane): engine reciprocating, sistem bahan bakar, overhaul berkala.',
                            'inquiry_url' => '',
                            'inquiry_label' => 'Tanya via WhatsApp',
                        ),
                        array(
                            'title' => 'Flight Instructor Course',
                            'category' => 'Flight Ops (CASR 141)',
                            'category_slug' => 'casr141',
                            'certification' => 'Sertifikasi Resmi Kemenhub',
                            'description' => 'Program pengembangan instruktur penerbangan: lesson plan, evaluasi, standardisasi, dan instructional technique.',
                            'inquiry_url' => '',
                            'inquiry_label' => 'Tanya via WhatsApp',
                        ),
                    )
                )
            ),
            'el_id' => '',
            'el_class' => '',
        ),
        $atts
    );

    $trainings = array();

    if ( 'spreadsheet' === $atts['data_source'] ) {
        if ( ! empty( $atts['spreadsheet_file'] ) && function_exists( 'ppic_dosen_directory_get_csv_attachment_contents' ) ) {
            $trainings = ppic_training_catalog_parse_spreadsheet( ppic_dosen_directory_get_csv_attachment_contents( $atts['spreadsheet_file'] ) );
        }
    } else {
        $trainings = vc_param_group_parse_atts( $atts['trainings'] );
    }

    if ( empty( $trainings ) || ! is_array( $trainings ) ) {
        return '';
    }

    $items = array();
    $categories = array();

    foreach ( $trainings as $training ) {
        $title = isset( $training['title'] ) ? trim( $training['title'] ) : '';
        $category = isset( $training['category'] ) ? trim( $training['category'] ) : '';

        if ( '' === $title || '' === $category ) {
            continue;
        }

        $category_slug = ! empty( $training['category_slug'] ) ? ppic_training_catalog_slugify( $training['category_slug'] ) : ppic_training_catalog_slugify( $category );
        $certification = isset( $training['certification'] ) ? trim( $training['certification'] ) : 'Sertifikasi Resmi Kemenhub';
        $description = isset( $training['description'] ) ? trim( $training['description'] ) : '';
        $inquiry_label = isset( $training['inquiry_label'] ) && '' !== trim( $training['inquiry_label'] ) ? trim( $training['inquiry_label'] ) : $atts['default_inquiry_label'];
        $inquiry_url = isset( $training['inquiry_url'] ) ? esc_url( trim( $training['inquiry_url'] ) ) : '';

        if ( '' === $inquiry_url ) {
            $inquiry_url = ppic_training_catalog_build_whatsapp_url( $title, $atts['default_whatsapp_number'] );
        }

        if ( ! isset( $categories[ $category_slug ] ) ) {
            $categories[ $category_slug ] = array(
                'slug' => $category_slug,
                'label' => $category,
            );
        }

        $items[] = array(
            'title' => $title,
            'category' => $category,
            'category_slug' => $category_slug,
            'certification' => $certification,
            'description' => $description,
            'inquiry_url' => $inquiry_url,
            'inquiry_label' => $inquiry_label,
            'search_blob' => strtolower( implode( ' ', ppic_training_catalog_split_values( $title . "\n" . $category . "\n" . $certification . "\n" . $description ) ) ),
        );
    }

    if ( empty( $items ) ) {
        return '';
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-training-catalog-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    $catalog_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-training-catalog-' ) : uniqid( 'ppic-training-catalog-' );
    $empty_button_url = ! empty( $atts['empty_button_url'] ) ? esc_url( $atts['empty_button_url'] ) : ppic_training_catalog_build_whatsapp_url( 'Katalog Pelatihan Penerbangan', $atts['default_whatsapp_number'] );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" id="<?php echo esc_attr( $catalog_id ); ?>" data-training-catalog>
        <div class="ppic-training-catalog__container">
            <aside class="ppic-training-catalog__sidebar">
                <h3 class="ppic-training-catalog__sidebar-title"><i class="fas fa-filter" aria-hidden="true"></i><?php echo esc_html( $atts['filter_label'] ); ?></h3>

                <div class="ppic-training-catalog__search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="text" class="ppic-training-catalog__search-input" placeholder="<?php echo esc_attr( $atts['search_placeholder'] ); ?>">
                </div>

                <div class="ppic-training-catalog__categories">
                    <label class="is-active">
                        <input type="radio" name="<?php echo esc_attr( $catalog_id ); ?>-category" value="all" checked>
                        <span class="ppic-training-catalog__category-icon"><i class="fas fa-globe" aria-hidden="true"></i></span>
                        <span><?php echo esc_html( $atts['all_label'] ); ?></span>
                    </label>
                    <?php foreach ( $categories as $category ) : ?>
                        <label>
                            <input type="radio" name="<?php echo esc_attr( $catalog_id ); ?>-category" value="<?php echo esc_attr( $category['slug'] ); ?>">
                            <span class="ppic-training-catalog__category-icon"><i class="fas fa-tag" aria-hidden="true"></i></span>
                            <span><?php echo esc_html( $category['label'] ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="ppic-training-catalog__stats"><?php echo esc_html( sprintf( $atts['result_text'], count( $items ), count( $items ) ) ); ?></div>
            </aside>

            <div class="ppic-training-catalog__content">
                <div class="ppic-training-catalog__grid">
                    <?php foreach ( $items as $item ) : ?>
                        <article class="ppic-training-card" data-category="<?php echo esc_attr( $item['category_slug'] ); ?>" data-search="<?php echo esc_attr( $item['search_blob'] ); ?>">
                            <span class="ppic-training-card__badge"><i class="fas fa-tag" aria-hidden="true"></i><?php echo esc_html( $item['category'] ); ?></span>
                            <h3 class="ppic-training-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
                            <?php if ( ! empty( $item['certification'] ) ) : ?>
                                <div class="ppic-training-card__cert"><i class="fas fa-certificate" aria-hidden="true"></i><?php echo esc_html( $item['certification'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $item['description'] ) ) : ?>
                                <div class="ppic-training-card__desc"><?php echo esc_html( $item['description'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $item['inquiry_url'] ) ) : ?>
                                <a class="ppic-training-card__link" href="<?php echo esc_url( $item['inquiry_url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $item['inquiry_label'] ); ?><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="contact-missing">
                    <h3><?php echo esc_html( $atts['empty_title'] ); ?></h3>
                    <p><?php echo esc_html( $atts['empty_description'] ); ?></p>
                    <?php if ( ! empty( $empty_button_url ) ) : ?>
                        <a href="<?php echo esc_url( $empty_button_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn-contact"><i class="fab fa-whatsapp" aria-hidden="true"></i> <?php echo esc_html( $atts['empty_button_text'] ); ?></a>
                    <?php endif; ?>
                </div>
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
                document.querySelectorAll('[data-training-catalog]').forEach(function (catalog) {
                    var searchInput = catalog.querySelector('.ppic-training-catalog__search-input');
                    var categoryInputs = catalog.querySelectorAll('.ppic-training-catalog__categories input[type="radio"]');
                    var stats = catalog.querySelector('.ppic-training-catalog__stats');
                    var cards = Array.prototype.slice.call(catalog.querySelectorAll('.ppic-training-card'));
                    var totalItems = cards.length;

                    var updateLabels = function () {
                        catalog.querySelectorAll('.ppic-training-catalog__categories label').forEach(function (label) {
                            var input = label.querySelector('input');
                            label.classList.toggle('is-active', !!(input && input.checked));
                        });
                    };

                    var updateCatalog = function () {
                        var query = ((searchInput && searchInput.value) || '').trim().toLowerCase();
                        var activeCategory = 'all';
                        var visibleCount = 0;

                        categoryInputs.forEach(function (input) {
                            if (input.checked) {
                                activeCategory = input.value || 'all';
                            }
                        });

                        cards.forEach(function (card) {
                            var matchesCategory = activeCategory === 'all' || (card.dataset.category || '') === activeCategory;
                            var matchesQuery = !query || ((card.dataset.search || '').indexOf(query) !== -1);
                            var isVisible = matchesCategory && matchesQuery;

                            card.hidden = !isVisible;

                            if (isVisible) {
                                visibleCount++;
                            }
                        });

                        updateLabels();

                        if (stats) {
                            stats.textContent = 'Menampilkan ' + visibleCount + ' dari ' + totalItems + ' pelatihan';
                        }
                    };

                    if (searchInput) {
                        searchInput.addEventListener('input', updateCatalog);
                    }

                    categoryInputs.forEach(function (input) {
                        input.addEventListener('change', updateCatalog);
                    });

                    updateCatalog();
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_training_catalog_map' );
function ppic_training_catalog_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Training Catalog', 'ppic-custom-element' ),
            'base' => 'ppic_training_catalog',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-index-card',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Sumber Data', 'ppic-custom-element' ),
                    'param_name' => 'data_source',
                    'value' => array(
                        __( 'Input Manual', 'ppic-custom-element' ) => 'manual',
                        __( 'Import CSV / Excel', 'ppic-custom-element' ) => 'spreadsheet',
                    ),
                    'std' => 'manual',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Placeholder Pencarian', 'ppic-custom-element' ),
                    'param_name' => 'search_placeholder',
                    'value' => 'Cari pelatihan (nama/deskripsi)...',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Sidebar', 'ppic-custom-element' ),
                    'param_name' => 'filter_label',
                    'value' => 'Filter Kategori',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Semua Kategori', 'ppic-custom-element' ),
                    'param_name' => 'all_label',
                    'value' => 'Semua Pelatihan',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Nomor WhatsApp Default', 'ppic-custom-element' ),
                    'param_name' => 'default_whatsapp_number',
                    'value' => '6285156120178',
                    'description' => __( 'Dipakai jika tiap baris CSV tidak punya inquiry_url / whatsapp_url sendiri.', 'ppic-custom-element' ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Tombol Default', 'ppic-custom-element' ),
                    'param_name' => 'default_inquiry_label',
                    'value' => 'Tanya via WhatsApp',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Empty State', 'ppic-custom-element' ),
                    'param_name' => 'empty_title',
                    'value' => 'Tidak menemukan pelatihan yang Anda cari?',
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Deskripsi Empty State', 'ppic-custom-element' ),
                    'param_name' => 'empty_description',
                    'value' => 'Hubungi tim kami untuk rekomendasi pelatihan yang paling sesuai dengan kebutuhan lembaga atau personel Anda.',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks Tombol Empty State', 'ppic-custom-element' ),
                    'param_name' => 'empty_button_text',
                    'value' => 'Hubungi Kami via WhatsApp',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Link Tombol Empty State', 'ppic-custom-element' ),
                    'param_name' => 'empty_button_url',
                    'description' => __( 'Opsional. Jika kosong akan dibuat otomatis dari nomor WhatsApp default.', 'ppic-custom-element' ),
                ),
                array(
                    'type' => 'ppic_csv_upload',
                    'heading' => __( 'File CSV/TXT', 'ppic-custom-element' ),
                    'param_name' => 'spreadsheet_file',
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => array( 'spreadsheet' ),
                    ),
                    'description' => __( 'Header yang didukung: title, category, category_slug, certification, description, inquiry_url, inquiry_label. File .xlsx belum diparse langsung.', 'ppic-custom-element' ),
                ),
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Daftar Pelatihan', 'ppic-custom-element' ),
                    'param_name' => 'trainings',
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => array( 'manual' ),
                    ),
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Judul Pelatihan', 'ppic-custom-element' ),
                            'param_name' => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Kategori', 'ppic-custom-element' ),
                            'param_name' => 'category',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Slug Kategori', 'ppic-custom-element' ),
                            'param_name' => 'category_slug',
                            'description' => __( 'Opsional. Jika kosong akan dibuat otomatis dari kategori.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Teks Sertifikasi', 'ppic-custom-element' ),
                            'param_name' => 'certification',
                            'value' => 'Sertifikasi Resmi Kemenhub',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Deskripsi', 'ppic-custom-element' ),
                            'param_name' => 'description',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Link Inquiry / WhatsApp', 'ppic-custom-element' ),
                            'param_name' => 'inquiry_url',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Label Link Inquiry', 'ppic-custom-element' ),
                            'param_name' => 'inquiry_label',
                            'value' => 'Tanya via WhatsApp',
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