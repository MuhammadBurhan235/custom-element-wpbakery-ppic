<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_kegiatan', 'ppic_prodi_kegiatan_render' );
function ppic_prodi_kegiatan_render( $atts ) {

    $dummy_kegiatan = array(
        array( 'caption' => 'Praktik di General Workshop', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1qZG13kjmNYVXXlgDpcY1BN0-k26qJ5jb' ),
        array( 'caption' => 'Praktik Pengelasan', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1jY6Cm8VSrmZgS3-q1QLdeRaz4uhpoWHk' ),
        array( 'caption' => 'Praktik Sistem AC', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1Ti6j9dyds0ddi_E_Yn0SGhSSNMnAkLUD' ),
        array( 'caption' => 'Praktik Water & Pump System', 'fallback_img' => 'https://lh3.googleusercontent.com/d/12l-TKRZ4MxTPUcCLsY6SbKDJobdHoS-3' ),
        array( 'caption' => 'Praktik PLC & Otomasi', 'fallback_img' => 'https://lh3.googleusercontent.com/d/19HUvtgE6xfQ4-7EqyD78eEXXx0TC3J7V' ),
        array( 'caption' => 'Praktik Alat Berat (Forklift)', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1XqyFlWjglYFZB9JAxhwAwdIZsgnHRHgk' ),
    );

    $atts = shortcode_atts(
        array(
            'section_id'  => 'kegiatan',
            'title'       => 'Kegiatan Taruna',
            'title_icon'  => 'fas fa-calendar-check',
            'subtitle'    => 'Berbagai kegiatan praktik, pelatihan, dan pengembangan diri yang diikuti oleh Taruna TMB selama masa studi.',
            'toggle_text' => 'Tampilkan Kegiatan',
            'items'       => urlencode( wp_json_encode( $dummy_kegiatan ) ),
            'el_class'    => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-kegiatan-section section-block alt' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater kegiatan
    $kegiatan_items = vc_param_group_parse_atts( $atts['items'] );
    if ( ! is_array( $kegiatan_items ) ) {
        $kegiatan_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-kegiatan-container">
            
            <h2 class="ppic-section-title">
                <?php if ( ! empty( $atts['title_icon'] ) ) : ?>
                    <i class="<?php echo esc_attr( $atts['title_icon'] ); ?>"></i> 
                <?php endif; ?>
                <?php echo esc_html( $atts['title'] ); ?>
            </h2>
            
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="ppic-section-sub">
                    <?php echo wp_kses_post( $atts['subtitle'] ); ?>
                </p>
            <?php endif; ?>

            <div class="ppic-kegiatan-wrapper">
                
                <!-- Toggle Header -->
                <div class="ppic-kegiatan-header" id="kegiatanToggle_<?php echo esc_attr($atts['section_id']); ?>">
                    <span class="toggle-label">
                        <i class="fas fa-calendar-alt"></i> 
                        <?php echo esc_html( $atts['toggle_text'] ); ?>
                    </span>
                    <span class="toggle-icon" id="kegiatanIcon_<?php echo esc_attr($atts['section_id']); ?>">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>

                <!-- Collapsible Content (Default Tertutup) -->
                <div class="ppic-kegiatan-collapse collapsed" id="kegiatanCollapse_<?php echo esc_attr($atts['section_id']); ?>">
                    <div class="ppic-kegiatan-collapse-inner">
                        <div class="ppic-kegiatan-grid">
                            <?php if ( ! empty( $kegiatan_items ) ) : ?>
                                <?php foreach ( $kegiatan_items as $item ) : 
                                    $caption = isset( $item['caption'] ) ? trim( $item['caption'] ) : 'Kegiatan';
                                    
                                    // Fallback gambar dummy jika gambar belum diunggah admin
                                    $img_url = isset( $item['fallback_img'] ) ? $item['fallback_img'] : 'https://lh3.googleusercontent.com/d/1qZG13kjmNYVXXlgDpcY1BN0-k26qJ5jb'; 
                                    
                                    if ( ! empty( $item['image'] ) ) {
                                        $uploaded_img = wp_get_attachment_image_url( $item['image'], 'medium_large' );
                                        if ( $uploaded_img ) {
                                            $img_url = $uploaded_img;
                                        }
                                    }
                                ?>
                                    <div class="ppic-kegiatan-card">
                                        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $caption ); ?>" loading="lazy" />
                                        <div class="kegiatan-caption"><?php echo esc_html( $caption ); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="grid-column: 1 / -1; text-align:center; color:#64748b;">Data kegiatan belum ditambahkan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php
    // JS Murni untuk Toggle Collapse Kegiatan
    static $ppic_kegiatan_js_loaded = false;
    if ( ! $ppic_kegiatan_js_loaded ) {
        $ppic_kegiatan_js_loaded = true;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kegiatanToggles = document.querySelectorAll('.ppic-kegiatan-header');
            
            kegiatanToggles.forEach(function(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const idPart = this.id.replace('kegiatanToggle_', '');
                    const collapseContent = document.getElementById('kegiatanCollapse_' + idPart);
                    const icon = document.getElementById('kegiatanIcon_' + idPart);
                    
                    if (!collapseContent) return;

                    if (collapseContent.classList.contains('collapsed')) {
                        // Buka (Expand)
                        collapseContent.classList.remove('collapsed');
                        collapseContent.style.maxHeight = collapseContent.scrollHeight + "px";
                        icon.style.transform = 'rotate(180deg)';
                        
                        setTimeout(() => {
                            collapseContent.style.maxHeight = "none";
                        }, 400); 
                    } else {
                        // Tutup (Collapse)
                        collapseContent.style.maxHeight = collapseContent.scrollHeight + "px"; 
                        setTimeout(() => {
                            collapseContent.style.maxHeight = "0px";
                        }, 10);
                        collapseContent.classList.add('collapsed');
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_kegiatan_element' );
function ppic_register_prodi_kegiatan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default data kegiatan menggunakan URL gambar dari referensi
    $dummy_kegiatan = array(
        array( 'caption' => 'Praktik di General Workshop', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1qZG13kjmNYVXXlgDpcY1BN0-k26qJ5jb' ),
        array( 'caption' => 'Praktik Pengelasan', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1jY6Cm8VSrmZgS3-q1QLdeRaz4uhpoWHk' ),
        array( 'caption' => 'Praktik Sistem AC', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1Ti6j9dyds0ddi_E_Yn0SGhSSNMnAkLUD' ),
        array( 'caption' => 'Praktik Water & Pump System', 'fallback_img' => 'https://lh3.googleusercontent.com/d/12l-TKRZ4MxTPUcCLsY6SbKDJobdHoS-3' ),
        array( 'caption' => 'Praktik PLC & Otomasi', 'fallback_img' => 'https://lh3.googleusercontent.com/d/19HUvtgE6xfQ4-7EqyD78eEXXx0TC3J7V' ),
        array( 'caption' => 'Praktik Alat Berat (Forklift)', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1XqyFlWjglYFZB9JAxhwAwdIZsgnHRHgk' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Kegiatan Taruna', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_kegiatan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-calendar-alt',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'kegiatan',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Kegiatan Taruna',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-calendar-check',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Berbagai kegiatan praktik, pelatihan, dan pengembangan diri yang diikuti oleh Taruna TMB selama masa studi.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol Toggle', 'ppic-custom-element' ),
                    'param_name'  => 'toggle_text',
                    'value'       => 'Tampilkan Kegiatan',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                // REPEATER KEGIATAN
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Kegiatan (Gambar & Teks)', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_kegiatan ) ),
                    'group'       => __( 'Data Kegiatan', 'ppic-custom-element' ),
                    'params'      => array(
                        array(
                            'type'        => 'attach_image', 
                            'heading'     => 'Upload Foto Kegiatan', 
                            'param_name'  => 'image'
                        ),
                        array(
                            'type'        => 'textfield', 
                            'heading'     => 'Nama / Caption Kegiatan', 
                            'param_name'  => 'caption', 
                            'admin_label' => true
                        ),
                        array(
                            'type'        => 'textfield', 
                            'heading'     => 'Fallback Image URL (Abaikan jika sudah upload gambar)', 
                            'param_name'  => 'fallback_img'
                        ),
                    ),
                ),
                // UMUM
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                ),
            ),
        )
    );
}