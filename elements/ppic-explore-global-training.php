<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_explore_global_training', 'ppic_explore_global_training_render' );
function ppic_explore_global_training_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'      => 'Global Training Center',
            'subtitle'   => 'PPI Curug merupakan Associate Member ICAO Trainair Plus dan pemegang lisensi ASTC (Aviation Security Training Centre) — pusat pengembangan SDM penerbangan berstandar internasional di Asia Tenggara.',
            'programs'   => '',
            'cta_text'   => 'Lihat Katalog Pelatihan Lengkap',
            'cta_link'   => 'katalog.html',
        ),
        $atts
    );

    $items = vc_param_group_parse_atts( $atts['programs'] );

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '';
    }

    ob_start(); ?>
    <section class="ppic-gt-section">
        <div class="ppic-gt-container">
            <h2 class="ppic-gt-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <p class="ppic-gt-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            
            <div class="ppic-gt-grid">
                <?php foreach ( $items as $item ) :
                    $badge_icon = isset( $item['badge_icon'] ) ? trim( $item['badge_icon'] ) : '';
                    $badge_text = isset( $item['badge_text'] ) ? trim( $item['badge_text'] ) : '';
                    $title      = isset( $item['title'] ) ? trim( $item['title'] ) : '';
                    $desc       = isset( $item['desc'] ) ? trim( $item['desc'] ) : '';
                    $creds      = isset( $item['creds'] ) ? trim( $item['creds'] ) : '';
                    $link_text  = isset( $item['link_text'] ) && !empty( $item['link_text'] ) ? trim( $item['link_text'] ) : 'Detail Program';
                    $link_url   = isset( $item['link_url'] ) && !empty( $item['link_url'] ) ? trim( $item['link_url'] ) : '#';

                    if ( '' === $title && '' === $desc ) {
                        continue;
                    }
                    ?>
                    <div class="ppic-gt-card">
                        <?php if ( '' !== $badge_text ) : ?>
                            <div class="ppic-gt-badge">
                                <?php if ( '' !== $badge_icon ) : ?>
                                    <i class="<?php echo esc_attr( $badge_icon ); ?>"></i> 
                                <?php endif; ?>
                                <?php echo esc_html( $badge_text ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h3><?php echo esc_html( $title ); ?></h3>
                        <p class="ppic-gt-desc"><?php echo esc_html( $desc ); ?></p>
                        
                        <?php if ( '' !== $creds ) : ?>
                            <div class="ppic-gt-credentials">
                                <?php
                                $cred_array = array_map( 'trim', explode( ',', $creds ) );
                                foreach ( $cred_array as $cred_item ) {
                                    if ( empty( $cred_item ) ) continue;
                                    
                                    // Smart parser untuk ikon Global Training
                                    $icon = 'fas fa-check-circle'; // Default
                                    $cred_lower = strtolower( $cred_item );
                                    
                                    if ( strpos( $cred_lower, 'class' ) !== false || strpos( $cred_lower, 'online' ) !== false ) {
                                        $icon = 'fas fa-calendar-week';
                                    } elseif ( strpos( $cred_lower, 'bilingual' ) !== false ) {
                                        $icon = 'fas fa-language';
                                    } elseif ( strpos( $cred_lower, 'basic' ) !== false || strpos( $cred_lower, 'advance' ) !== false ) {
                                        $icon = 'fas fa-shield-virus';
                                    } elseif ( strpos( $cred_lower, 'recognized' ) !== false ) {
                                        $icon = 'fas fa-certificate';
                                    } elseif ( strpos( $cred_lower, 'a320' ) !== false || strpos( $cred_lower, 'b737' ) !== false ) {
                                        $icon = 'fas fa-plane';
                                    } elseif ( strpos( $cred_lower, 'instructor' ) !== false ) {
                                        $icon = 'fas fa-chalkboard';
                                    } elseif ( strpos( $cred_lower, 'test' ) !== false ) {
                                        $icon = 'fas fa-microphone-alt';
                                    } elseif ( strpos( $cred_lower, 'coaching' ) !== false ) {
                                        $icon = 'fas fa-chalkboard-teacher';
                                    }
                                    
                                    echo '<span class="gt-cred-pill"><i class="' . esc_attr( $icon ) . '"></i> ' . esc_html( $cred_item ) . '</span>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?php echo esc_url( $link_url ); ?>" class="ppic-gt-btn">
                            <?php echo esc_html( $link_text ); ?> <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ( !empty( $atts['cta_text'] ) ) : ?>
                <div class="ppic-gt-cta-wrapper">
                    <a href="<?php echo esc_url( $atts['cta_link'] ); ?>" class="ppic-gt-cta-btn">
                        <?php echo esc_html( $atts['cta_text'] ); ?> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_explore_global_training_element' );
function ppic_register_explore_global_training_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_data = array(
        array(
            'badge_icon'=> 'fas fa-globe', 
            'badge_text'=> 'ICAO Trainair Plus', 
            'title'     => 'Trainair Plus Programme (TPP)', 
            'desc'      => 'Program pelatihan instruktur dan manajemen penerbangan yang diakui ICAO. Mengintegrasikan TPEMS, kurikulum pengembangan keselamatan.', 
            'creds'     => 'In-class/Online, Bilingual', 
            'link_text' => 'Discover Programme', 
            'link_url'  => '#'
        ),
        array(
            'badge_icon'=> '', 
            'badge_text'=> 'ASTC', 
            'title'     => 'Aviation Security Training Centre', 
            'desc'      => 'Satu-satunya wakil ICAO di Indonesia untuk pelatihan keamanan penerbangan. Sertifikasi AVSEC, manajemen krisis, pencegahan campur tangan ilegal.', 
            'creds'     => 'Basic & Advance, ICAO Recognized', 
            'link_text' => 'Lihat Jadwal', 
            'link_url'  => '#'
        ),
        array(
            'badge_icon'=> '', 
            'badge_text'=> 'Full Flight Simulator', 
            'title'     => 'Type Rating & PPC (A320 & B737)', 
            'desc'      => 'Layanan FFS untuk Airbus A320 dan Boeing 737-800 NG. Tersedia Initial Type Rating, Recurrency, Proficiency Check dengan instruktur kapten.', 
            'creds'     => 'A320, B737-800NG, Capt. Instructor', 
            'link_text' => 'Daftar Pelatihan', 
            'link_url'  => '#'
        ),
        array(
            'badge_icon'=> '', 
            'badge_text'=> 'ICAO English', 
            'title'     => 'Aviation English Language Proficiency', 
            'desc'      => 'Uji kecakapan bahasa Inggris penerbangan (ICAO Rating 4-6) oleh rater profesional bersertifikat. Coaching clinic intensif sesuai ICAO DOC 9835.', 
            'creds'     => 'Official Test, Coaching Clinic', 
            'link_text' => 'Daftar Ujian & Coaching', 
            'link_url'  => '#'
        )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Explore Global Training', 'ppic-custom-element' ),
            'base'     => 'ppic_explore_global_training',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-admin-site-alt3',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Global Training Center',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'PPI Curug merupakan Associate Member ICAO Trainair Plus dan pemegang lisensi ASTC (Aviation Security Training Centre) — pusat pengembangan SDM penerbangan berstandar internasional di Asia Tenggara.',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA Bawah', 'ppic-custom-element' ),
                    'param_name' => 'cta_text',
                    'value'      => 'Lihat Katalog Pelatihan Lengkap',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'URL Tombol CTA Bawah', 'ppic-custom-element' ),
                    'param_name' => 'cta_link',
                    'value'      => 'katalog.html',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Pelatihan', 'ppic-custom-element' ),
                    'param_name' => 'programs',
                    'value'      => urlencode( wp_json_encode( $dummy_data ) ),
                    'params'     => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Ikon Badge (Opsional)', 'ppic-custom-element' ),
                            'param_name'  => 'badge_icon',
                            'description' => 'Contoh: fas fa-globe',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Teks Badge', 'ppic-custom-element' ),
                            'param_name'  => 'badge_text',
                            'description' => 'Contoh: ICAO Trainair Plus',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Nama Pelatihan', 'ppic-custom-element' ),
                            'param_name'  => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi Pelatihan', 'ppic-custom-element' ),
                            'param_name' => 'desc',
                        ),
                        array(
                            'type'        => 'textarea',
                            'heading'     => __( 'Credentials / Label', 'ppic-custom-element' ),
                            'param_name'  => 'creds',
                            'description' => 'Pisahkan dengan koma. Contoh: In-class/Online, Bilingual.',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Teks Tombol Card', 'ppic-custom-element' ),
                            'param_name' => 'link_text',
                            'value'      => 'Detail Program',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'URL Tombol Card', 'ppic-custom-element' ),
                            'param_name' => 'link_url',
                            'value'      => '#',
                        ),
                    ),
                ),
            ),
        )
    );
}