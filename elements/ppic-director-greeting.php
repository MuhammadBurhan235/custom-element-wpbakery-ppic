<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_director_greeting', 'ppic_director_greeting_render' );
function ppic_director_greeting_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'image' => '',
            'image_alt' => 'Direktur PPI Curug Capt. Megi H. Helmiadi',
            'title' => 'Sambutan Direktur',
            'quote' => '“Kami berkomitmen mencetak sumber daya manusia unggul yang siap mengawaki industri penerbangan nasional dan global. Dengan fasilitas modern dan instruktur berpengalaman, PPI Curug menjadi rumah bagi calon-calon penerbang profesional.”',
            'name' => 'Capt. Megi H. Helmiadi',
            'position' => 'Direktur PPI Curug',
            'cta_text' => 'Baca lengkap',
            'cta_link' => 'url:overview.html%23sambutan|title:Baca lengkap',
            'el_id' => '',
            'el_class' => '',
        ),
        $atts
    );

    $link = vc_build_link( $atts['cta_link'] );
    $cta_href = ! empty( $link['url'] ) ? $link['url'] : '#';
    $cta_title = ! empty( $link['title'] ) ? $link['title'] : $atts['cta_text'];
    $cta_target = ! empty( $link['target'] ) ? ' target="' . trim( $link['target'] ) . '"' : '';

    $image_url = 'https://ppicurug.ac.id/wp-content/uploads/2026/02/WhatsApp-Image-2026-02-04-at-10.28.58.jpeg';
    if ( ! empty( $atts['image'] ) ) {
        $image_data = wp_get_attachment_image_src( $atts['image'], 'full' );
        if ( $image_data ) {
            $image_url = $image_data[0];
        }
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-director-greeting-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-director-greeting-container">
            <div class="ppic-director-greeting-image">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $atts['image_alt'] ); ?>">
            </div>
            <div class="ppic-director-greeting-content">
                <h3><?php echo esc_html( $atts['title'] ); ?></h3>
                <p class="ppic-director-greeting-quote"><?php echo esc_html( $atts['quote'] ); ?></p>
                <p class="ppic-director-greeting-meta">
                    <strong><?php echo esc_html( $atts['name'] ); ?></strong>
                    <span> - <?php echo esc_html( $atts['position'] ); ?></span>
                </p>
                <div class="ppic-director-greeting-link">
                    <a href="<?php echo esc_url( $cta_href ); ?>"<?php echo $cta_target; ?>>
                        <?php echo esc_html( $cta_title ); ?>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_director_greeting_map' );
function ppic_director_greeting_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Director Greeting', 'ppic-custom-element' ),
            'base' => 'ppic_director_greeting',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-format-quote',
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'heading' => __( 'Foto Direktur', 'ppic-custom-element' ),
                    'param_name' => 'image',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Alt Text Foto', 'ppic-custom-element' ),
                    'param_name' => 'image_alt',
                    'value' => 'Direktur PPI Curug Capt. Megi H. Helmiadi',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value' => 'Sambutan Direktur',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Isi Sambutan', 'ppic-custom-element' ),
                    'param_name' => 'quote',
                    'value' => '“Kami berkomitmen mencetak sumber daya manusia unggul yang siap mengawaki industri penerbangan nasional dan global. Dengan fasilitas modern dan instruktur berpengalaman, PPI Curug menjadi rumah bagi calon-calon penerbang profesional.”',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Nama Direktur', 'ppic-custom-element' ),
                    'param_name' => 'name',
                    'value' => 'Capt. Megi H. Helmiadi',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Jabatan', 'ppic-custom-element' ),
                    'param_name' => 'position',
                    'value' => 'Direktur PPI Curug',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks CTA', 'ppic-custom-element' ),
                    'param_name' => 'cta_text',
                    'value' => 'Baca lengkap',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link CTA', 'ppic-custom-element' ),
                    'param_name' => 'cta_link',
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