<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_explore_rpl_programs', 'ppic_explore_rpl_programs_render' );
function ppic_explore_rpl_programs_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'Program RPL',
            'subtitle' => 'Rekognisi Pembelajaran Lampau bagi praktisi penerbangan yang ingin meningkatkan kualifikasi akademik ke jenjang Sarjana Terapan.',
            'btn_link' => '',
            'programs' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'icon'  => 'fas fa-broadcast-tower',
                            'title' => 'RPL LLU',
                            'desc'  => 'Rekognisi untuk bidang Lalu Lintas Udara, percepatan studi jenjang DIV.',
                        ),
                        array(
                            'icon'  => 'fas fa-wrench',
                            'title' => 'RPL TPU',
                            'desc'  => 'Konversi pengalaman teknisi pesawat ke dalam SKS program DIV Teknik Pesawat Udara.',
                        ),
                        array(
                            'icon'  => 'fas fa-satellite',
                            'title' => 'RPL TNU',
                            'desc'  => 'Bagi personel navigasi dan teknisi avionik untuk mendapatkan gelar Sarjana Terapan.',
                        ),
                        array(
                            'icon'  => 'fas fa-bolt',
                            'title' => 'RPL TLB',
                            'desc'  => 'Rekognisi bagi profesional kelistrikan bandara menuju DIV Teknik Listrik Bandara.',
                        ),
                    )
                )
            ),
        ),
        $atts
    );

    $link  = vc_build_link( $atts['btn_link'] );
    $items = vc_param_group_parse_atts( $atts['programs'] );

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '';
    }

    ob_start(); ?>
    <div class="ppic-rpl-section">
        <div class="ppic-rpl-header">
            <h2><?php echo esc_html( $atts['title'] ); ?></h2>
            <p><?php echo esc_html( $atts['subtitle'] ); ?></p>
        </div>
        <div class="ppic-rpl-grid">
            <?php foreach ( $items as $item ) :
                $icon  = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-info-circle';
                $title = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                $desc  = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';

                if ( '' === $title && '' === $desc ) {
                    continue;
                }
                ?>
                <div class="ppic-rpl-card">
                    <?php if ( '' !== $icon ) : ?>
                        <div class="ppic-rpl-icon">
                            <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                        </div>
                    <?php endif; ?>
                    <h4><?php echo esc_html( $title ); ?></h4>
                    <p><?php echo esc_html( $desc ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ( ! empty( $link['url'] ) ) : ?>
            <div class="ppic-rpl-footer">
                <a href="<?php echo esc_url( $link['url'] ); ?>" class="ppic-rpl-btn" <?php echo ! empty( $link['target'] ) ? 'target="' . esc_attr( trim( $link['target'] ) ) . '"' : ''; ?>>
                    <i class="fas fa-file-alt" aria-hidden="true"></i> <?php echo esc_html( ! empty( $link['title'] ) ? $link['title'] : 'Daftar RPL Sekarang' ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_explore_rpl_programs_element' );
function ppic_register_explore_rpl_programs_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name'     => __( 'PPIC Explore RPL Programs', 'ppic-custom-element' ),
            'base'     => 'ppic_explore_rpl_programs',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-welcome-learn-more', // Icon untuk di editor WPBakery
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Program RPL',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea', // Diubah menjadi textarea agar lebih leluasa mengetik kalimat panjang
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'Rekognisi Pembelajaran Lampau bagi praktisi penerbangan yang ingin meningkatkan kualifikasi akademik ke jenjang Sarjana Terapan.',
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Program RPL', 'ppic-custom-element' ),
                    'param_name' => 'programs',
                    'value'      => urlencode(
                        wp_json_encode(
                            array(
                                array(
                                    'icon'  => 'fas fa-broadcast-tower',
                                    'title' => 'RPL LLU',
                                    'desc'  => 'Rekognisi untuk bidang Lalu Lintas Udara, percepatan studi jenjang DIV.',
                                ),
                                array(
                                    'icon'  => 'fas fa-wrench',
                                    'title' => 'RPL TPU',
                                    'desc'  => 'Konversi pengalaman teknisi pesawat ke dalam SKS program DIV Teknik Pesawat Udara.',
                                ),
                                array(
                                    'icon'  => 'fas fa-satellite',
                                    'title' => 'RPL TNU',
                                    'desc'  => 'Bagi personel navigasi dan teknisi avionik untuk mendapatkan gelar Sarjana Terapan.',
                                ),
                                array(
                                    'icon'  => 'fas fa-bolt',
                                    'title' => 'RPL TLB',
                                    'desc'  => 'Rekognisi bagi profesional kelistrikan bandara menuju DIV Teknik Listrik Bandara.',
                                ),
                            )
                        )
                    ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Icon (FontAwesome)', 'ppic-custom-element' ),
                            'param_name'  => 'icon',
                            'value'       => 'fas fa-info-circle',
                            'description' => __( 'Contoh: fas fa-broadcast-tower', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Nama Program', 'ppic-custom-element' ),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                            'param_name' => 'desc',
                        ),
                    ),
                ),
            ),
        )
    );
}