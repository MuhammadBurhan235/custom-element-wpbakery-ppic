<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_mengenal', 'ppic_ppid_mengenal_render' );
function ppic_ppid_mengenal_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'          => 'Mengenal PPID',
            'subtitle'       => 'PPID hadir sebagai garda terdepan dalam pelayanan informasi publik. Kami memastikan setiap masyarakat mendapatkan akses informasi yang transparan, kredibel, dan dapat dipertanggungjawabkan.',
            'description'    => 'Sebagai bagian dari implementasi keterbukaan informasi publik, PPID menjalankan fungsi pengelolaan, pendokumentasian, dan pelayanan informasi sesuai dengan prinsip good governance.',
            'image'          => '',
            'image_fallback' => 'https://images.unsplash.com/photo-1573164713988-9665fc9c2c6e?w=800&q=80', // Fallback default
            'links'          => '',
            'el_id'          => '',
            'el_class'       => '',
        ),
        $atts
    );

    // Proses daftar tautan (Quick Links)
    $links = vc_param_group_parse_atts( $atts['links'] );

    // Proses Gambar (Attachment ID vs Fallback URL)
    $img_id = isset( $atts['image'] ) ? trim( $atts['image'] ) : '';
    $img_url = '';
    $img_alt = esc_attr( $atts['title'] );

    if ( ! empty( $img_id ) ) {
        $img_src = wp_get_attachment_image_src( $img_id, 'large' );
        if ( $img_src ) {
            $img_url = $img_src[0];
            $alt_text = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
            if ( ! empty( $alt_text ) ) {
                $img_alt = esc_attr( $alt_text );
            }
        }
    }

    if ( empty( $img_url ) && ! empty( $atts['image_fallback'] ) ) {
        $img_url = trim( $atts['image_fallback'] );
    }

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-ppid-mengenal-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-ppid-mengenal-container">
            <div class="ppic-ppid-mengenal-content">
                <h2 class="ppic-ppid-mengenal-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-ppid-mengenal-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <?php endif; ?>
                
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <p class="ppic-ppid-mengenal-desc"><?php echo esc_html( $atts['description'] ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $links ) && is_array( $links ) ) : ?>
                    <ul class="ppic-ppid-mengenal-links">
                        <?php foreach ( $links as $link ) : 
                            $label = isset( $link['label'] ) ? trim( $link['label'] ) : '';
                            $url   = isset( $link['url'] ) && ! empty( $link['url'] ) ? trim( $link['url'] ) : '#';
                            
                            if ( empty( $label ) ) continue;
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $url ); ?>">
                                    <i class="fas fa-angle-right" aria-hidden="true"></i> <?php echo esc_html( $label ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="ppic-ppid-mengenal-image-wrapper">
                <?php if ( ! empty( $img_url ) ) : ?>
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo $img_alt; ?>" class="ppic-ppid-mengenal-img" loading="lazy" />
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_mengenal_element' );
function ppic_register_ppid_mengenal_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_links = array(
        array( 'label' => 'Profil PPID', 'url' => '#' ),
        array( 'label' => 'Pejabat PPID', 'url' => '#' ),
        array( 'label' => 'Tugas dan Fungsi', 'url' => '#' ),
        array( 'label' => 'Visi dan Misi', 'url' => '#' ),
        array( 'label' => 'Struktur Organisasi', 'url' => '#' ),
        array( 'label' => 'Dasar Hukum', 'url' => '#' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Mengenal', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_mengenal',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-id-alt',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Mengenal PPID',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul (Teks Atas)', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'PPID hadir sebagai garda terdepan dalam pelayanan informasi publik. Kami memastikan setiap masyarakat mendapatkan akses informasi yang transparan, kredibel, dan dapat dipertanggungjawabkan.',
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi (Teks Bawah)', 'ppic-custom-element' ),
                    'param_name' => 'description',
                    'value'      => 'Sebagai bagian dari implementasi keterbukaan informasi publik, PPID menjalankan fungsi pengelolaan, pendokumentasian, dan pelayanan informasi sesuai dengan prinsip good governance.',
                ),
                array(
                    'type'        => 'attach_image',
                    'heading'     => __( 'Gambar Samping', 'ppic-custom-element' ),
                    'param_name'  => 'image',
                    'description' => 'Pilih gambar dari Media Library.',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Fallback Image URL (Opsional)', 'ppic-custom-element' ),
                    'param_name'  => 'image_fallback',
                    'value'       => 'https://images.unsplash.com/photo-1573164713988-9665fc9c2c6e?w=800&q=80',
                    'description' => 'Akan digunakan jika Gambar Samping di atas kosong.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Tautan (Quick Links)', 'ppic-custom-element' ),
                    'param_name' => 'links',
                    'value'      => urlencode( wp_json_encode( $dummy_links ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Label Tautan', 'ppic-custom-element' ),
                            'param_name'  => 'label',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Tautan', 'ppic-custom-element' ),
                            'param_name' => 'url',
                            'value'      => '#',
                        ),
                    ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}