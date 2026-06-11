<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_penelitian_erepo', 'ppic_penelitian_erepo_render' );
function ppic_penelitian_erepo_render( $atts, $content = null ) {
    $atts = shortcode_atts(
        array(
            'title'      => 'e-Repository (Institutional Repository)',
            'features'   => '',
            'btn_text'   => 'Kunjungi e-Repository',
            'btn_link'   => 'url:https%3A%2F%2Frepository.ppicurug.ac.id|target:_blank',
            'btn_icon'   => 'fas fa-arrow-right',
            'image'      => '',
            'image_url'  => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&q=80', // Fallback image (library)
            'el_id'      => 'e-repository',
            'el_class'   => '',
        ),
        $atts
    );

    // Proses data poin list (fitur)
    $features = vc_param_group_parse_atts( $atts['features'] );

    // Proses URL Tombol
    $link = ( '||' !== $atts['btn_link'] ) ? vc_build_link( $atts['btn_link'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_title  = ! empty( $link['title'] ) ? $link['title'] : '';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';
    $a_rel    = ! empty( $link['rel'] ) ? ' rel="' . esc_attr( trim( $link['rel'] ) ) . '"' : '';

    // Proses Gambar
    if ( ! function_exists( 'ppic_get_erepo_img_url' ) ) {
        function ppic_get_erepo_img_url( $id, $fallback ) {
            if ( ! empty( $id ) ) {
                $src = wp_get_attachment_image_src( $id, 'large' );
                if ( $src ) return $src[0];
            }
            return $fallback;
        }
    }
    $img_src = ppic_get_erepo_img_url( $atts['image'], $atts['image_url'] );

    // Atribut Wrapper
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-erepo-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-erepo-container">
            
            <div class="ppic-erepo-content">
                <h2 class="ppic-erepo-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                
                <div class="ppic-erepo-desc">
                    <?php 
                    // Render konten dari WPBakery text block editor
                    echo wpb_js_remove_wpautop( $content, true ); 
                    ?>
                </div>

                <?php if ( ! empty( $features ) && is_array( $features ) ) : ?>
                    <ul class="ppic-erepo-list">
                        <?php foreach ( $features as $feat ) : 
                            $text = isset( $feat['text'] ) ? trim( $feat['text'] ) : '';
                            if ( empty( $text ) ) continue;
                            ?>
                            <li><?php echo esc_html( $text ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $a_href ); ?>" class="ppic-erepo-link" title="<?php echo esc_attr( $a_title ); ?>"<?php echo $a_target; ?><?php echo $a_rel; ?>>
                        <?php echo esc_html( $atts['btn_text'] ); ?>
                        <?php if ( ! empty( $atts['btn_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['btn_icon'] ); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="ppic-erepo-image">
                <img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $atts['title'] ); ?>" loading="lazy">
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_penelitian_erepo_element' );
function ppic_register_penelitian_erepo_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_features = array(
        array( 'text' => 'Menampung skripsi, tesis, laporan penelitian, dan publikasi ilmiah digital' ),
        array( 'text' => 'Pencarian sederhana dan lanjutan dengan berbagai filter (subjek, tahun, penulis)' ),
        array( 'text' => 'Dapat dinavigasi berdasarkan subjek atau tahun publikasi' ),
        array( 'text' => 'Mendukung open access untuk diseminasi ilmu pengetahuan' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Penelitian - e-Repository', 'ppic-custom-element' ),
            'base'     => 'ppic_penelitian_erepo',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-portfolio',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'e-Repository (Institutional Repository)',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea_html',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'content',
                    'value'      => '<p>PPI Curug telah mengembangkan <strong>e-Repository</strong> berbasis elektronik untuk mengelola, menyimpan, dan menyebarluaskan karya ilmiah civitas akademika secara digital. Penjajakan awal penggunaan repository elektronik telah dilaksanakan pada 14-15 November 2023 dengan menghadirkan narasumber kompeten di bidangnya.</p><p>Repository ini menyediakan akses terhadap skripsi, tesis, laporan penelitian, jurnal, dan publikasi ilmiah lainnya dalam format digital. Pengguna dapat melakukan pencarian sederhana maupun lanjutan (advanced search) untuk menemukan dokumen yang relevan. Koleksi dapat dijelajahi berdasarkan subjek atau tahun publikasi, memudahkan peneliti dalam mengakses referensi ilmiah.</p>',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Poin (List)', 'ppic-custom-element' ),
                    'param_name' => 'features',
                    'value'      => urlencode( wp_json_encode( $dummy_features ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textarea',
                            'heading'     => __( 'Teks List', 'ppic-custom-element' ),
                            'param_name'  => 'text',
                            'admin_label' => true,
                        ),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tautan (Link Bawah)', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Kunjungi e-Repository',
                    'group'      => __( 'Tautan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tautan (FontAwesome)', 'ppic-custom-element' ),
                    'param_name' => 'btn_icon',
                    'value'      => 'fas fa-arrow-right',
                    'group'      => __( 'Tautan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tautan', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                    'value'      => 'url:https%3A%2F%2Frepository.ppicurug.ac.id|target:_blank',
                    'group'      => __( 'Tautan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'attach_image',
                    'heading'    => __( 'Gambar Visual (Kanan)', 'ppic-custom-element' ),
                    'param_name' => 'image',
                    'group'      => __( 'Gambar', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'e-repository',
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