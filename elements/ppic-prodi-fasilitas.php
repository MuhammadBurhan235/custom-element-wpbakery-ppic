<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_fasilitas', 'ppic_prodi_fasilitas_render' );
function ppic_prodi_fasilitas_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'section_id'  => 'fasilitas',
            'title'       => 'Fasilitas',
            'title_icon'  => 'fas fa-building',
            'subtitle'    => 'Berbagai fasilitas penunjang pembelajaran dan praktik tersedia untuk mendukung kompetensi Taruna TMB.',
            'toggle_text' => 'Tampilkan Fasilitas',
            'items'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-fasilitas-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater fasilitas
    $fasilitas_items = vc_param_group_parse_atts( $atts['items'] );
    if ( ! is_array( $fasilitas_items ) ) {
        $fasilitas_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-fasilitas-container">
            
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

            <div class="ppic-fasilitas-wrapper">
                
                <!-- Toggle Header -->
                <div class="ppic-fasilitas-header" id="fasilitasToggle_<?php echo esc_attr($atts['section_id']); ?>">
                    <span class="toggle-label">
                        <i class="fas fa-chevron-circle-down"></i> 
                        <?php echo esc_html( $atts['toggle_text'] ); ?>
                    </span>
                    <span class="toggle-icon" id="fasilitasIcon_<?php echo esc_attr($atts['section_id']); ?>">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>

                <!-- Collapsible Content (Default Tertutup) -->
                <div class="ppic-fasilitas-collapse collapsed" id="fasilitasCollapse_<?php echo esc_attr($atts['section_id']); ?>">
                    <div class="ppic-fasilitas-collapse-inner">
                        <div class="ppic-fasilitas-grid">
                            <?php if ( ! empty( $fasilitas_items ) ) : ?>
                                <?php foreach ( $fasilitas_items as $item ) : 
                                    $caption = isset( $item['caption'] ) ? trim( $item['caption'] ) : 'Fasilitas';
                                    
                                    // Fallback gambar dummy jika gambar belum diunggah admin
                                    $img_url = isset( $item['fallback_img'] ) ? $item['fallback_img'] : 'https://lh3.googleusercontent.com/d/1p8kbLsYSTpIbjtJlonDoIrv74APE7tBk'; 
                                    
                                    if ( ! empty( $item['image'] ) ) {
                                        $uploaded_img = wp_get_attachment_image_url( $item['image'], 'medium_large' );
                                        if ( $uploaded_img ) {
                                            $img_url = $uploaded_img;
                                        }
                                    }
                                ?>
                                    <div class="ppic-fasilitas-card">
                                        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $caption ); ?>" loading="lazy" />
                                        <div class="fasilitas-caption"><?php echo esc_html( $caption ); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="grid-column: 1 / -1; text-align:center; color:#64748b;">Data fasilitas belum ditambahkan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php
    // JS Murni untuk Toggle Collapse Fasilitas
    static $ppic_fasilitas_js_loaded = false;
    if ( ! $ppic_fasilitas_js_loaded ) {
        $ppic_fasilitas_js_loaded = true;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fasilitasToggles = document.querySelectorAll('.ppic-fasilitas-header');
            
            fasilitasToggles.forEach(function(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const idPart = this.id.replace('fasilitasToggle_', '');
                    const collapseContent = document.getElementById('fasilitasCollapse_' + idPart);
                    const icon = document.getElementById('fasilitasIcon_' + idPart);
                    
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

add_action( 'vc_before_init', 'ppic_register_prodi_fasilitas_element' );
function ppic_register_prodi_fasilitas_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default data fasilitas menggunakan URL gambar dari referensi
    $dummy_fasilitas = array(
        array( 'caption' => 'Ruang Kelas (5 ruangan)', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1p8kbLsYSTpIbjtJlonDoIrv74APE7tBk' ),
        array( 'caption' => 'Ruang Perpustakaan', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1qZG13kjmNYVXXlgDpcY1BN0-k26qJ5jb' ),
        array( 'caption' => 'General Workshop', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1jY6Cm8VSrmZgS3-q1QLdeRaz4uhpoWHk' ),
        array( 'caption' => 'Welding Workshop', 'fallback_img' => 'https://lh3.googleusercontent.com/d/1Ti6j9dyds0ddi_E_Yn0SGhSSNMnAkLUD' ),
        array( 'caption' => 'Lab Water & Pump System', 'fallback_img' => 'https://lh3.googleusercontent.com/d/12l-TKRZ4MxTPUcCLsY6SbKDJobdHoS-3' ),
        array( 'caption' => 'Lab Elektromekanikal', 'fallback_img' => 'https://lh3.googleusercontent.com/d/19HUvtgE6xfQ4-7EqyD78eEXXx0TC3J7V' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Fasilitas', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_fasilitas',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-building',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'fasilitas',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Fasilitas',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-building',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Berbagai fasilitas penunjang pembelajaran dan praktik tersedia untuk mendukung kompetensi Taruna TMB.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Tombol Toggle', 'ppic-custom-element' ),
                    'param_name'  => 'toggle_text',
                    'value'       => 'Tampilkan Fasilitas',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                // REPEATER FASILITAS
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Fasilitas (Gambar & Teks)', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_fasilitas ) ),
                    'group'       => __( 'Data Fasilitas', 'ppic-custom-element' ),
                    'params'      => array(
                        array(
                            'type'        => 'attach_image', 
                            'heading'     => 'Upload Foto Fasilitas', 
                            'param_name'  => 'image'
                        ),
                        array(
                            'type'        => 'textfield', 
                            'heading'     => 'Nama / Caption Fasilitas', 
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