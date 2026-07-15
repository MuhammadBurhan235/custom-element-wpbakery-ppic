<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_profil_visi_misi', 'ppic_ppid_profil_visi_misi_render' );
function ppic_ppid_profil_visi_misi_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // KARTU VISI (KIRI)
            'visi_title'  => 'Visi PPID PPI Curug',
            'visi_desc'   => 'Menjadikan PPID PPI Curug sebagai pendukung dalam menuju Pusat Unggulan (<em>Center of Excellence</em>) melalui pelayanan prima di bidang pelayanan dan penyediaan informasi publik.',
            
            // KARTU MISI (KANAN)
            'misi_title'  => 'Misi PPID PPI Curug',
            'misi_items'  => '',
            
            // UMUM
            'el_id'       => 'visi-misi',
            'el_class'    => '',
        ),
        $atts
    );

    // Default Fallback Data untuk Daftar Misi
    $dummy_misi = array(
        array( 'text' => 'Meningkatkan kualitas dan efisiensi layanan informasi publik yang Ramah, Efisien, Sigap, Informatif dan Kompeten di Politeknik Penerbangan Indonesia Curug.' ),
        array( 'text' => 'Meningkatkan transparansi dan digitalisasi informasi dan dokumentasi Pengelolaan Layanan Informasi Publik untuk mendukung <em>good governance</em>.' ),
    );

    // Parsing Param Group Misi
    $misi_data = vc_param_group_parse_atts( $atts['misi_items'] );
    if ( empty( $misi_data ) || ! is_array( $misi_data ) ) {
        $misi_data = $dummy_misi;
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-visimisi-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-visimisi-container">
            <div class="visimisi-grid">
                
                <!-- CARD 1: VISI (Kiri) -->
                <div class="visimisi-card">
                    <div class="visimisi-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="visimisi-title"><?php echo esc_html( $atts['visi_title'] ); ?></h3>
                    <p class="visimisi-text">
                        <?php echo wp_kses_post( $atts['visi_desc'] ); ?>
                    </p>
                </div>

                <!-- CARD 2: MISI (Kanan) -->
                <div class="visimisi-card">
                    <div class="visimisi-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="visimisi-title"><?php echo esc_html( $atts['misi_title'] ); ?></h3>
                    <ul class="visimisi-list">
                        <?php foreach ( $misi_data as $misi ) : 
                            $text = isset( $misi['text'] ) ? $misi['text'] : '';
                            if ( empty( $text ) ) continue;
                        ?>
                            <li><?php echo wp_kses_post( $text ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_profil_visi_misi_element' );
function ppic_register_ppid_profil_visi_misi_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_misi = array(
        array( 'text' => 'Meningkatkan kualitas dan efisiensi layanan informasi publik yang Ramah, Efisien, Sigap, Informatif dan Kompeten di Politeknik Penerbangan Indonesia Curug.' ),
        array( 'text' => 'Meningkatkan transparansi dan digitalisasi informasi dan dokumentasi Pengelolaan Layanan Informasi Publik untuk mendukung <em>good governance</em>.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Profil - Visi & Misi', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_profil_visi_misi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-visibility',
            'params'   => array(
                // KARTU VISI
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Visi', 'ppic-custom-element' ),
                    'param_name' => 'visi_title',
                    'value'      => 'Visi PPID PPI Curug',
                    'group'      => __( 'Kartu Visi', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Visi', 'ppic-custom-element' ),
                    'param_name' => 'visi_desc',
                    'value'      => 'Menjadikan PPID PPI Curug sebagai pendukung dalam menuju Pusat Unggulan (<em>Center of Excellence</em>) melalui pelayanan prima di bidang pelayanan dan penyediaan informasi publik.',
                    'description'=> __( 'Anda bisa menggunakan tag &lt;em&gt; untuk membuat teks miring.', 'ppic-custom-element' ),
                    'group'      => __( 'Kartu Visi', 'ppic-custom-element' ),
                ),

                // KARTU MISI
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Misi', 'ppic-custom-element' ),
                    'param_name' => 'misi_title',
                    'value'      => 'Misi PPID PPI Curug',
                    'group'      => __( 'Kartu Misi', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Misi', 'ppic-custom-element' ),
                    'param_name' => 'misi_items',
                    'value'      => urlencode( wp_json_encode( $dummy_misi ) ),
                    'group'      => __( 'Kartu Misi', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textarea', 'heading' => 'Poin Misi', 'param_name' => 'text', 'admin_label' => true),
                    ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'visi-misi',
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