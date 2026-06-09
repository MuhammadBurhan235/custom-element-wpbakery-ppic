<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Helper function untuk mengambil isi file CSV dari Media Library
function ppic_gallery_get_csv_attachment_contents( $attachment_id ) {
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

function ppic_gallery_extract_google_drive_file_id( $url ) {
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

function ppic_gallery_normalize_image_url( $url ) {
    $url = trim( (string) $url );

    if ( '' === $url ) {
        return '';
    }

    $drive_file_id = ppic_gallery_extract_google_drive_file_id( $url );

    if ( '' !== $drive_file_id ) {
        // Mengembalikan URL langsung yang dapat dirender oleh browser
        return 'https://lh3.googleusercontent.com/d/' . rawurlencode( $drive_file_id );
    }

    return $url;
}

// 2. Helper function untuk mem-parsing data CSV Galeri
function ppic_gallery_parse_spreadsheet( $raw_value ) {
    if ( empty( $raw_value ) ) {
        return array();
    }
    
    // Hapus BOM jika ada
    $raw_value = preg_replace( '/^\xEF\xBB\xBF/', '', $raw_value );
    $lines = preg_split( '/\r\n|\r|\n/', $raw_value );
    $lines = array_values( array_filter( array_map( 'trim', $lines ), function($l) { return '' !== $l; } ) );
    
    if ( count( $lines ) < 2 ) {
        return array();
    }
    
    // Deteksi pemisah (, atau ;)
    $delimiter = ',';
    if ( substr_count( $lines[0], ';' ) > substr_count( $lines[0], ',' ) ) {
        $delimiter = ';';
    }
    
    $headers = str_getcsv( $lines[0], $delimiter );
    $mapped_headers = array();
    
    foreach ( $headers as $h ) {
        $h = strtolower( trim( preg_replace('/[^a-zA-Z0-9]+/', '_', $h), '_' ) );
        $aliases = array(
            'kategori'    => 'category', 
            'category'    => 'category',
            'image_url'   => 'image', 
            'image'       => 'image', 
            'img_url'     => 'image', 
            'foto'        => 'image',
            'title'       => 'title', 
            'judul'       => 'title',
            'deskripsi'   => 'desc', 
            'desc'        => 'desc', 
            'description' => 'desc'
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
        if ( ! empty( $row['image'] ) || ! empty( $row['title'] ) ) {
            $rows[] = $row;
        }
    }
    
    return $rows;
}

// 3. Register Custom WPBakery Param (Identik dengan Dosen Directory tapi dengan nama unik)
function ppic_gallery_register_csv_param() {
    if ( ! function_exists( 'vc_add_shortcode_param' ) ) {
        return;
    }
    vc_add_shortcode_param( 'ppic_gallery_csv_upload', 'ppic_gallery_csv_upload_field' );
}
add_action( 'init', 'ppic_gallery_register_csv_param', 20 );

function ppic_gallery_csv_upload_field( $settings, $value ) {
    $param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
    $attachment_id = absint( $value );
    $file_label = '';
    $field_id = 'ppic-gallery-csv-' . wp_rand( 1000, 999999 );

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

if ( ! function_exists( 'ppic_gallery_enqueue_admin_media' ) ) {
    function ppic_gallery_enqueue_admin_media( $hook ) {
        wp_enqueue_media();
    }
    add_action( 'admin_enqueue_scripts', 'ppic_gallery_enqueue_admin_media' );
}

// 4. Fungsi Render Utama
add_shortcode( 'ppic_gallery_main', 'ppic_gallery_main_render' );
function ppic_gallery_main_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'data_source'      => 'manual',
            'spreadsheet_file' => '',
            'gallery_items'    => '',
            'el_id'            => '',
            'el_class'         => '',
        ),
        $atts
    );

    $items = array();

    // Load data berdasarkan mode pilihan
    if ( 'spreadsheet' === $atts['data_source'] ) {
        if ( ! empty( $atts['spreadsheet_file'] ) ) {
            $items = ppic_gallery_parse_spreadsheet( ppic_gallery_get_csv_attachment_contents( $atts['spreadsheet_file'] ) );
        }
    } else {
        if ( ! empty( $atts['gallery_items'] ) ) {
            $items = vc_param_group_parse_atts( $atts['gallery_items'] );
        }
    }

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '<p style="text-align:center; padding: 20px; border: 1px dashed #ccc;">Silakan tambahkan item galeri atau pilih file CSV yang valid.</p>';
    }

    $categories = array();
    $processed_items = array();

    foreach ( $items as $item ) {
        $category = isset( $item['category'] ) ? trim( $item['category'] ) : '';
        $title    = isset( $item['title'] ) ? trim( $item['title'] ) : '';
        $desc     = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
        
        $img_url = '';
        if ( isset( $item['image'] ) && '' !== trim( $item['image'] ) ) {
            $image_val = trim( $item['image'] );
            // Cek jika ID gambar (dari mode manual)
            if ( is_numeric( $image_val ) ) {
                $img_data = wp_get_attachment_image_src( $image_val, 'large' );
                if ( $img_data ) {
                    $img_url = $img_data[0];
                }
            } else {
                // Jika dari CSV akan berupa URL string, termasuk share link Google Drive publik.
                $img_url = ppic_gallery_normalize_image_url( $image_val );
            }
        }

        if ( empty( $img_url ) ) {
            continue;
        }

        if ( ! empty( $category ) && ! in_array( $category, $categories ) ) {
            $categories[] = $category;
        }

        $processed_items[] = array(
            'category' => $category,
            'img_url'  => $img_url,
            'title'    => $title,
            'desc'     => $desc
        );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : ' id="galleryGrid-' . uniqid() . '"';
    $wrapper_class = 'gallery-main ppic-gallery-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-gallery-container">
            
            <?php if ( ! empty( $categories ) ) : ?>
                <div class="ppic-gallery-filters">
                    <button class="gallery-filter-btn active" data-filter="all">Semua</button>
                    <?php foreach ( $categories as $cat ) : ?>
                        <button class="gallery-filter-btn" data-filter="<?php echo esc_attr( $cat ); ?>"><?php echo esc_html( $cat ); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="gallery-grid">
                <?php foreach ( $processed_items as $item ) : ?>
                    <div class="gallery-card" data-category="<?php echo esc_attr( $item['category'] ); ?>">
                        <div class="image-wrapper">
                            <img class="gallery-img" src="<?php echo esc_url( $item['img_url'] ); ?>" loading="lazy" alt="<?php echo esc_attr( $item['title'] ); ?>" onerror="this.src='https://via.placeholder.com/500x300?text=Image+Not+Found'">
                            <div class="overlay-caption">
                                <div class="caption-title"><i class="fas fa-camera"></i> <?php echo esc_html( $item['title'] ); ?></div>
                                <?php if ( ! empty( $item['desc'] ) ) : ?>
                                    <div class="caption-desc"><i class="fas fa-feather-alt"></i> <?php echo esc_html( $item['desc'] ); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="stats-minimal">
                <i class="fas fa-images"></i> <?php echo count( $processed_items ); ?> momen visual · Pusat Unggulan Aviasi Nasional
            </div>

        </div>
    </section>

    <?php
    // JS Filter logic (dicetak sekali saja di footer/inline)
    static $gallery_script_printed = false;
    if ( ! $gallery_script_printed ) {
        $gallery_script_printed = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const gallerySections = document.querySelectorAll('.ppic-gallery-section');
                
                gallerySections.forEach(section => {
                    const filterBtns = section.querySelectorAll('.gallery-filter-btn');
                    const galleryCards = section.querySelectorAll('.gallery-card');

                    filterBtns.forEach(btn => {
                        btn.addEventListener('click', () => {
                            // Update active state
                            filterBtns.forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');

                            // Filter 
                            const filterValue = btn.getAttribute('data-filter');
                            galleryCards.forEach(card => {
                                if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                                    card.style.display = 'block';
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                        });
                    });
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

// 5. WPBakery Mapping
add_action( 'vc_before_init', 'ppic_gallery_main_map' );
function ppic_gallery_main_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Main Gallery', 'ppic-custom-element' ),
            'base'     => 'ppic_gallery_main',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-images-alt2',
            'params'   => array(
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
                    'type'        => 'ppic_gallery_csv_upload',
                    'heading'     => __( 'File CSV', 'ppic-custom-element' ),
                    'param_name'  => 'spreadsheet_file',
                    'dependency'  => array(
                        'element' => 'data_source',
                        'value'   => array( 'spreadsheet' ),
                    ),
                    'description' => __( 'Upload file CSV dari Media Library WordPress. Header kolom yang didukung: kategori, image_url, title, desc. image_url bisa memakai URL gambar langsung atau link Google Drive publik.', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Gambar', 'ppic-custom-element' ),
                    'param_name' => 'gallery_items',
                    'dependency' => array(
                        'element' => 'data_source',
                        'value'   => array( 'manual' ),
                    ),
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Kategori', 'ppic-custom-element' ),
                            'param_name' => 'category',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'attach_image',
                            'heading'    => __( 'Gambar Galeri', 'ppic-custom-element' ),
                            'param_name' => 'image',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Judul', 'ppic-custom-element' ),
                            'param_name' => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi (Opsional)', 'ppic-custom-element' ),
                            'param_name' => 'desc',
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