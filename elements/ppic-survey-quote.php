<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_survey_quote', 'ppic_survey_quote_render' );
function ppic_survey_quote_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'quote_text'  => 'Kualitas pendidikan bukanlah hasil akhir, melainkan perjalanan berkelanjutan yang ditempuh bersama. Setiap jawaban Anda adalah batu loncatan menuju keunggulan.',
            'attribution' => 'Satuan Penjaminan Mutu PPI Curug',
            'note_text'   => 'Program Survey Kepuasan Masyarakat Tahun 2025 berdasarkan <strong>Undang-Undang Nomor 12 Tahun 2012 tentang Pendidikan Tinggi</strong>. Hasil survey menjadi rujukan strategis peningkatan mutu layanan diklat.',
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-survey-quote-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-survey-quote-container">
            
            <blockquote>
                <i class="fas fa-quote-left" aria-hidden="true"></i>
                <?php echo esc_html( $atts['quote_text'] ); ?>
                <i class="fas fa-quote-right" aria-hidden="true"></i>
            </blockquote>
            
            <?php if ( ! empty( $atts['attribution'] ) ) : ?>
                <div class="ppic-quote-attribution">&mdash; <?php echo esc_html( $atts['attribution'] ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $atts['note_text'] ) ) : ?>
                <div class="ppic-quote-note">
                    <p><?php echo wp_kses_post( $atts['note_text'] ); ?></p>
                </div>
            <?php endif; ?>
            
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_survey_quote_element' );
function ppic_register_survey_quote_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Survey Quote', 'ppic-custom-element' ),
            'base'     => 'ppic_survey_quote',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-editor-quote',
            'params'   => array(
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Kutipan (Quote)', 'ppic-custom-element' ),
                    'param_name'  => 'quote_text',
                    'value'       => 'Kualitas pendidikan bukanlah hasil akhir, melainkan perjalanan berkelanjutan yang ditempuh bersama. Setiap jawaban Anda adalah batu loncatan menuju keunggulan.',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Sumber / Atribusi', 'ppic-custom-element' ),
                    'param_name'  => 'attribution',
                    'value'       => 'Satuan Penjaminan Mutu PPI Curug',
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Catatan Kaki (Footer Note)', 'ppic-custom-element' ),
                    'param_name'  => 'note_text',
                    'value'       => 'Program Survey Kepuasan Masyarakat Tahun 2025 berdasarkan <strong>Undang-Undang Nomor 12 Tahun 2012 tentang Pendidikan Tinggi</strong>. Hasil survey menjadi rujukan strategis peningkatan mutu layanan diklat.',
                    'description' => __( 'Mendukung tag HTML dasar seperti &lt;strong&gt; untuk teks tebal.', 'ppic-custom-element' ),
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