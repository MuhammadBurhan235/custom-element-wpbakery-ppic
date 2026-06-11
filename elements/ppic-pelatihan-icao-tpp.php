<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_pelatihan_tpp', 'ppic_pelatihan_tpp_render' );
function ppic_pelatihan_tpp_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'ICAO TRAINAIR PLUS (TPP)',
            'desc'     => '<strong>TRAINAIR PLUS Programme (TPP)</strong> adalah program kerjasama internasional ICAO yang bertujuan meningkatkan kapasitas pelatihan penerbangan melalui pengembangan kursus berbasis kompetensi (CBD) dan metodologi pengajaran standar global. PPI Curug sebagai anggota TPP mengadopsi sistem jaminan kualitas pelatihan yang diakui dunia.',
            'features' => '',
            'btn_text' => 'Akses Katalog Resmi TRAINAIR PLUS',
            'btn_link' => 'url:https%3A%2F%2Figat.icao.int%2Fated%2FTrainingCatalogue|target:_blank|title:Katalog%20TRAINAIR%20PLUS',
            'btn_icon' => 'fas fa-external-link-alt',
            'el_id'    => 'tpp',
            'el_class' => '',
        ),
        $atts
    );

    // Proses data list fitur
    $features = vc_param_group_parse_atts( $atts['features'] );

    // Parse URL Tombol
    $link = ( '||' !== $atts['btn_link'] ) ? vc_build_link( $atts['btn_link'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_title  = ! empty( $link['title'] ) ? $link['title'] : '';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';
    $a_rel    = ! empty( $link['rel'] ) ? ' rel="' . esc_attr( trim( $link['rel'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-tpp-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-tpp-container">
            
            <h2 class="ppic-tpp-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            
            <div class="ppic-tpp-card">
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <div class="ppic-tpp-desc">
                        <p><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $features ) && is_array( $features ) ) : ?>
                    <ul class="ppic-tpp-list">
                        <?php foreach ( $features as $feat ) : 
                            $text = isset( $feat['text'] ) ? trim( $feat['text'] ) : '';
                            if ( empty( $text ) ) continue;
                            ?>
                            <li><?php echo wp_kses_post( $text ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $a_href ); ?>" class="ppic-tpp-btn" title="<?php echo esc_attr( $a_title ); ?>"<?php echo $a_target; ?><?php echo $a_rel; ?>>
                        <?php if ( ! empty( $atts['btn_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['btn_icon'] ); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                        <?php echo esc_html( $atts['btn_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_pelatihan_tpp_element' );
function ppic_register_pelatihan_tpp_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_features = array(
        array( 'text' => 'Pengembangan materi kursus sesuai standar ICAO (Course Development).' ),
        array( 'text' => 'Sertifikasi instruktur sebagai <em>TPP Instructor / Course Developer</em>.' ),
        array( 'text' => 'Akses ke jaringan global TRAINAIR PLUS (sharing kursus dan best practices).' ),
        array( 'text' => 'Pelatihan untuk personel maskapai, otoritas bandara, dan ATC dengan metodologi terstandar.' )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Pelatihan - ICAO TPP', 'ppic-custom-element' ),
            'base'     => 'ppic_pelatihan_tpp',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-learn-more',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'ICAO TRAINAIR PLUS (TPP)',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea_html',
                    'heading'    => __( 'Deskripsi Paragraf', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => '<strong>TRAINAIR PLUS Programme (TPP)</strong> adalah program kerjasama internasional ICAO yang bertujuan meningkatkan kapasitas pelatihan penerbangan melalui pengembangan kursus berbasis kompetensi (CBD) dan metodologi pengajaran standar global. PPI Curug sebagai anggota TPP mengadopsi sistem jaminan kualitas pelatihan yang diakui dunia.',
                    'description'=> __( 'Anda bisa menebalkan teks atau membuatnya miring di sini.', 'ppic-custom-element' ),
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
                            'description' => 'Mendukung tag HTML dasar seperti &lt;em&gt; atau &lt;strong&gt;',
                        ),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Akses Katalog Resmi TRAINAIR PLUS',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tombol (FontAwesome)', 'ppic-custom-element' ),
                    'param_name' => 'btn_icon',
                    'value'      => 'fas fa-external-link-alt',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                    'value'      => 'url:https%3A%2F%2Figat.icao.int%2Fated%2FTrainingCatalogue|target:_blank|title:Katalog%20TRAINAIR%20PLUS',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'tpp',
                    'description' => __( 'ID khusus untuk auto-scroll (Contoh: tpp).', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name.', 'js_composer' ),
                ),
            ),
        )
    );
}