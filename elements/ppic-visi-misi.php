<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Daftarkan Elemen Visi Misi ke WPBakery
function ppic_register_visi_misi_element() {
    if ( function_exists( 'vc_map' ) ) {
        vc_map( array(
            "name" => __( "PPIC Visi & Misi", "ppic" ),
            "base" => "ppic_visi_misi_section",
            "category" => __( "PPIC Elements", "ppic" ),
            "icon" => "dashicons dashicons-visibility", 
            "params" => array(
                // --- Bagian Visi ---
                array(
                    "type" => "textfield",
                    "heading" => __( "Judul Visi", "ppic" ),
                    "param_name" => "visi_title",
                    "value" => "Visi",
                    "group" => __( "Visi", "ppic" )
                ),
                array(
                    "type" => "textarea",
                    "heading" => __( "Deskripsi Visi", "ppic" ),
                    "param_name" => "visi_text",
                    "value" => '"Menjadi pusat unggulan pendidikan vokasi dan profesi penerbangan berstandar internasional, meningkatkan martabat dan kesejahteraan bangsa."',
                    "group" => __( "Visi", "ppic" )
                ),

                // --- Bagian Misi ---
                array(
                    "type" => "textfield",
                    "heading" => __( "Judul Misi", "ppic" ),
                    "param_name" => "misi_title",
                    "value" => "Misi",
                    "group" => __( "Misi", "ppic" )
                ),
                array(
                    "type" => "param_group",
                    "heading" => __( "Daftar Poin Misi", "ppic" ),
                    "param_name" => "misi_items",
                    "group" => __( "Misi", "ppic" ),
                    "value" => urlencode( json_encode( array(
                        array( "misi_text" => "Pendidikan & pelatihan penerbangan berkualitas global" ),
                        array( "misi_text" => "Penelitian terapan untuk pengembangan aviasi" ),
                        array( "misi_text" => "Pengabdian masyarakat dan kerjasama strategis" )
                    ) ) ),
                    "params" => array(
                        array(
                            "type" => "textarea",
                            "heading" => __( "Teks Misi", "ppic" ),
                            "param_name" => "misi_text",
                            "admin_label" => true,
                        )
                    )
                ),
                array(
                    "type" => "el_id",
                    "heading" => __( "Element ID", "js_composer" ),
                    "param_name" => "el_id",
                    "description" => __( "Enter element ID (Note: make sure it is unique and valid according to w3c specification).", "js_composer" )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Extra class name", "js_composer" ),
                    "param_name" => "el_class",
                    "description" => __( "Style particular content element differently by adding a class name and referring to it in custom CSS.", "js_composer" )
                )
            )
        ) );
    }
}
add_action( 'vc_before_init', 'ppic_register_visi_misi_element' );

// 2. Render Output ke Frontend
function ppic_visi_misi_section_render( $atts ) {
    $atts = shortcode_atts( array(
        'visi_title' => 'Visi',
        'visi_text'  => '"Menjadi pusat unggulan pendidikan vokasi dan profesi penerbangan berstandar internasional, meningkatkan martabat dan kesejahteraan bangsa."',
        'misi_title' => 'Misi',
        'misi_items' => '',
        'el_id'      => '',
        'el_class'   => '',
    ), $atts );

    $misi_items = vc_param_group_parse_atts( $atts['misi_items'] );
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-vm-wrapper' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Ikon SVG bawaan agar tidak butuh FontAwesome
    $icon_mata = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#00365a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    $icon_target = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#00365a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>';
    $icon_panah = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00365a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';

    ob_start();
    ?>
    <div<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        
        <div class="ppic-vm-card">
            <div class="ppic-vm-header">
                <span class="ppic-vm-icon"><?php echo $icon_mata; ?></span>
                <h3 class="ppic-vm-title"><?php echo esc_html( $atts['visi_title'] ); ?></h3>
            </div>
            <div class="ppic-vm-body">
                <p><?php echo esc_html( $atts['visi_text'] ); ?></p>
            </div>
        </div>

        <div class="ppic-vm-card">
            <div class="ppic-vm-header">
                <span class="ppic-vm-icon"><?php echo $icon_target; ?></span>
                <h3 class="ppic-vm-title"><?php echo esc_html( $atts['misi_title'] ); ?></h3>
            </div>
            <div class="ppic-vm-body">
                <ul class="ppic-misi-list">
                    <?php 
                    if ( ! empty( $misi_items ) && is_array( $misi_items ) ) {
                        foreach ( $misi_items as $item ) {
                            if ( ! empty( $item['misi_text'] ) ) {
                                echo '<li><span class="misi-bullet">' . $icon_panah . '</span> <span class="misi-text">' . esc_html( $item['misi_text'] ) . '</span></li>';
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>

    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'ppic_visi_misi_section', 'ppic_visi_misi_section_render' );