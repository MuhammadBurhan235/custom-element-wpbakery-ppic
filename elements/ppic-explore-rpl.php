<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Helper function untuk parsing data RPL dari CSV
function ppic_rpl_parse_spreadsheet( $raw_value ) {
    if ( empty( $raw_value ) ) {
        return array();
    }
    
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
        $aliases = array(
            'kode'        => 'code',
            'code'        => 'code',
            'nama'        => 'name',
            'name'        => 'name',
            'icon'        => 'icon',
            'deskripsi'   => 'description',
            'description' => 'description',
        );
        $mapped_headers[] = isset( $aliases[$h] ) ? $aliases[$h] : '';
    }
    
    $rows = array();
    foreach ( array_slice( $lines, 1 ) as $line ) {
        $columns = str_getcsv( $line, $delimiter );
        $row = array();
        foreach ( $mapped_headers as $index => $field ) {
            if ( '' === $field ) continue;
            $row[ $field ] = isset( $columns[ $index ] ) ? trim( wp_unslash( $columns[ $index ] ) ) : '';
        }
        if ( ! empty( $row['name'] ) || ! empty( $row['code'] ) ) {
            $rows[] = $row;
        }
    }
    
    return $rows;
}

function ppic_rpl_get_csv_attachment_contents( $attachment_id ) {
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

// 2. Register Custom WPBakery Param untuk CSV upload RPL
function ppic_rpl_register_csv_param() {
    if ( ! function_exists( 'vc_add_shortcode_param' ) ) {
        return;
    }
    vc_add_shortcode_param( 'ppic_rpl_csv_upload', 'ppic_rpl_csv_upload_field' );
}
add_action( 'init', 'ppic_rpl_register_csv_param', 20 );

function ppic_rpl_csv_upload_field( $settings, $value ) {
    $param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
    $attachment_id = absint( $value );
    $file_label = '';
    $field_id = 'ppic-rpl-csv-' . wp_rand( 1000, 999999 );

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
    $output .= '<script>(function(){var root=document.getElementById(' . wp_json_encode( $field_id ) . ');if(!root||root.dataset.ppicRplCsvReady){return;}root.dataset.ppicRplCsvReady="1";var selectButton=root.querySelector(".ppic-csv-upload-field__select");var clearButton=root.querySelector(".ppic-csv-upload-field__clear");var hiddenInput=root.querySelector(".wpb_vc_param_value");var labelInput=root.querySelector(".ppic-csv-upload-field__label");if(selectButton){selectButton.addEventListener("click",function(event){event.preventDefault();if(typeof window.wp==="undefined"||typeof window.wp.media==="undefined"){window.alert("Media Library WordPress belum siap dimuat.");return;}var fileFrame=window.wp.media({title:"Pilih File CSV",button:{text:"Gunakan file ini"},multiple:false});fileFrame.on("select",function(){var attachment=fileFrame.state().get("selection").first().toJSON();if(hiddenInput){hiddenInput.value=attachment.id;hiddenInput.dispatchEvent(new Event("change",{bubbles:true}));}if(labelInput){labelInput.value=attachment.filename||attachment.url||"";}if(clearButton){clearButton.style.display="";}});fileFrame.open();});}if(clearButton){clearButton.addEventListener("click",function(event){event.preventDefault();if(hiddenInput){hiddenInput.value="";hiddenInput.dispatchEvent(new Event("change",{bubbles:true}));}if(labelInput){labelInput.value="";}clearButton.style.display="none";});}}());</script>';
    $output .= '</div>';

    return $output;
}

if ( ! function_exists( 'ppic_rpl_enqueue_admin_media' ) ) {
    function ppic_rpl_enqueue_admin_media( $hook ) {
        wp_enqueue_media();
    }
    add_action( 'admin_enqueue_scripts', 'ppic_rpl_enqueue_admin_media' );
}

// 3. Fungsi Render Utama RPL
add_shortcode( 'ppic_explore_rpl', 'ppic_explore_rpl_render' );
function ppic_explore_rpl_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'            => 'Program RPL',
            'subtitle'         => 'Rekognisi Pembelajaran Lampau bagi praktisi penerbangan yang ingin meningkatkan kualifikasi akademik ke jenjang Sarjana Terapan.',
            'data_source'      => 'manual',
            'spreadsheet_file' => '',
            'rpl_items'        => '',
            'el_id'            => '',
            'el_class'         => '',
        ),
        $atts
    );

    $items = array();

    if ( 'spreadsheet' === $atts['data_source'] ) {
        if ( ! empty( $atts['spreadsheet_file'] ) ) {
            $items = ppic_rpl_parse_spreadsheet( ppic_rpl_get_csv_attachment_contents( $atts['spreadsheet_file'] ) );
        }
    } else {
        if ( ! empty( $atts['rpl_items'] ) ) {
            $items = vc_param_group_parse_atts( $atts['rpl_items'] );
        }
    }

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '<p style="text-align:center; padding: 20px; border: 1px dashed #ccc;">Silakan tambahkan item RPL atau pilih file CSV yang valid.</p>';
    }

    $processed_items = array();

    foreach ( $items as $item ) {
        $code = isset( $item['code'] ) ? trim( $item['code'] ) : '';
        $name = isset( $item['name'] ) ? trim( $item['name'] ) : '';
        $icon = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-graduation-cap';
        $description = isset( $item['description'] ) ? trim( $item['description'] ) : '';

        if ( empty( $name ) && empty( $code ) ) {
            continue;
        }

        $processed_items[] = array(
            'code'        => $code,
            'name'        => $name,
            'icon'        => $icon,
            'description' => $description
        );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : ' id="rplSection-' . uniqid() . '"';
    $wrapper_class = 'ppic-explore-rpl-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-rpl-container">
            <h2 class="ppic-rpl-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <p class="ppic-rpl-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>

            <div class="ppic-rpl-grid">
                <?php foreach ( $processed_items as $item ) : ?>
                    <div class="ppic-rpl-card">
                        <div class="rpl-icon-wrapper">
                            <i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
                        </div>
                        <h4 class="rpl-card-title"><?php echo esc_html( $item['name'] ); ?></h4>
                        <p class="rpl-card-description"><?php echo esc_html( $item['description'] ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="ppic-rpl-cta">
                <a href="#" class="btn-primary-cta"><i class="fas fa-file-alt"></i> Daftar RPL Sekarang</a>
            </div>
        </div>
    </section>
    <?php

    return ob_get_clean();
}

// 4. WPBakery Mapping
add_action( 'vc_before_init', 'ppic_explore_rpl_map' );
function ppic_explore_rpl_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Explore RPL', 'ppic-custom-element' ),
            'base'     => 'ppic_explore_rpl',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-learn-more',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'std'        => 'Program RPL',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'std'        => 'Rekognisi Pembelajaran Lampau bagi praktisi penerbangan yang ingin meningkatkan kualifikasi akademik ke jenjang Sarjana Terapan.',
                ),
                array(
                    'type'       => 'dropdown',
                    'heading'    => __( 'Sumber Data', 'ppic-custom-element' ),
                    'param_name' => 'data_source',
                    'value'      => array(
                        __( 'Input Manual', 'ppic-custom-element' )       => 'manual',
                        __( 'Import CSV', 'ppic-custom-element' ) => 'spreadsheet',
                    ),
                    'std'        => 'manual',
                ),
                array(
                    'type'        => 'ppic_rpl_csv_upload',
                    'heading'     => __( 'File CSV', 'ppic-custom-element' ),
                    'param_name'  => 'spreadsheet_file',
                    'dependency'  => array(
                        'element' => 'data_source',
                        'value'   => array( 'spreadsheet' ),
                    ),
                    'description' => __( 'Header kolom: code, name, icon, description', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Program RPL', 'ppic-custom-element' ),
                    'param_name' => 'rpl_items',
                    'dependency' => array(
                        'element' => 'data_source',
                        'value'   => array( 'manual' ),
                    ),
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Kode RPL', 'ppic-custom-element' ),
                            'param_name' => 'code',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Nama Program', 'ppic-custom-element' ),
                            'param_name' => 'name',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Icon Class (FontAwesome)', 'ppic-custom-element' ),
                            'param_name' => 'icon',
                            'value'      => 'fas fa-graduation-cap',
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                            'param_name' => 'description',
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