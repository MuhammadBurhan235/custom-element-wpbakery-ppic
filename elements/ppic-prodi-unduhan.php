<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_unduhan', 'ppic_prodi_unduhan_render' );
function ppic_prodi_unduhan_render( $atts ) {

    $dummy_unduhan = array(
        array( 'name' => 'Kurikulum TMB 2025', 'size' => 'PDF, 2.4 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Panduan Praktikum', 'size' => 'PDF, 1.8 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Modul Sistem AC', 'size' => 'PDF, 3.1 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Modul Pompa & Pemipaan', 'size' => 'PDF, 2.7 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Pedoman PKL', 'size' => 'PDF, 1.2 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Brosur TMB 2025', 'size' => 'PDF, 4.0 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
    );

    $atts = shortcode_atts(
        array(
            'section_id'  => 'downloads',
            'title'       => 'Unduhan',
            'title_icon'  => 'fas fa-download',
            'subtitle'    => 'Temukan dokumen pendukung yang kamu butuhkan untuk mengenal lebih dalam Program Studi Teknik Mekanikal Bandar Udara.',
            'toggle_icon' => 'fas fa-file-pdf',
            'toggle_text' => 'Temukan dokumen pendukung yang kamu butuhkan',
            'items'       => urlencode( wp_json_encode( $dummy_unduhan ) ),
            'el_class'    => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-unduhan-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse data repeater unduhan
    $unduhan_items = vc_param_group_parse_atts( $atts['items'] );
    if ( ! is_array( $unduhan_items ) ) {
        $unduhan_items = array();
    }

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-unduhan-container">
            
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

            <div class="ppic-unduhan-wrapper">
                
                <!-- Toggle Header -->
                <div class="ppic-unduhan-header" id="unduhanToggle_<?php echo esc_attr($atts['section_id']); ?>">
                    <span class="toggle-label">
                        <?php if ( ! empty( $atts['toggle_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['toggle_icon'] ); ?>"></i>
                        <?php endif; ?>
                        📄 <?php echo esc_html( $atts['toggle_text'] ); ?>
                    </span>
                    <span class="toggle-icon" id="unduhanIcon_<?php echo esc_attr($atts['section_id']); ?>">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>

                <!-- Collapsible Content (Default Tertutup) -->
                <div class="ppic-unduhan-collapse collapsed" id="unduhanCollapse_<?php echo esc_attr($atts['section_id']); ?>">
                    <div class="ppic-unduhan-collapse-inner">
                        <div class="ppic-unduhan-list">
                            <?php if ( ! empty( $unduhan_items ) ) : ?>
                                <?php foreach ( $unduhan_items as $item ) : 
                                    $name = isset( $item['name'] ) ? trim( $item['name'] ) : 'Dokumen';
                                    $size = isset( $item['size'] ) ? trim( $item['size'] ) : 'PDF';
                                    $icon = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-file-pdf';
                                    
                                    // Parse Link
                                    $file_link = isset( $item['file_link'] ) ? $item['file_link'] : '';
                                    $link_data = vc_build_link( $file_link );
                                    $a_href    = ! empty( $link_data['url'] ) ? $link_data['url'] : '#';
                                    $a_target  = ! empty( $link_data['target'] ) ? ' target="' . esc_attr( trim( $link_data['target'] ) ) . '"' : ' target="_blank"';
                                    
                                    if ( empty( $name ) ) continue;
                                ?>
                                    <div class="ppic-unduhan-item">
                                        <div class="item-left">
                                            <i class="<?php echo esc_attr( $icon ); ?>"></i>
                                            <div class="info">
                                                <div class="name"><?php echo esc_html( $name ); ?></div>
                                                <div class="size"><?php echo esc_html( $size ); ?></div>
                                            </div>
                                        </div>
                                        <a href="<?php echo esc_url( $a_href ); ?>"<?php echo $a_target; ?> class="btn-download">Unduh</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="text-align:center; color:#64748b; padding: 20px;">Data unduhan belum ditambahkan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php
    // JS Murni untuk Toggle Collapse Unduhan
    static $ppic_unduhan_js_loaded = false;
    if ( ! $ppic_unduhan_js_loaded ) {
        $ppic_unduhan_js_loaded = true;
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unduhanToggles = document.querySelectorAll('.ppic-unduhan-header');
            
            unduhanToggles.forEach(function(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const idPart = this.id.replace('unduhanToggle_', '');
                    const collapseContent = document.getElementById('unduhanCollapse_' + idPart);
                    const icon = document.getElementById('unduhanIcon_' + idPart);
                    
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

add_action( 'vc_before_init', 'ppic_register_prodi_unduhan_element' );
function ppic_register_prodi_unduhan_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default data dari HTML klien
    $dummy_unduhan = array(
        array( 'name' => 'Kurikulum TMB 2025', 'size' => 'PDF, 2.4 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Panduan Praktikum', 'size' => 'PDF, 1.8 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Modul Sistem AC', 'size' => 'PDF, 3.1 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Modul Pompa & Pemipaan', 'size' => 'PDF, 2.7 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Pedoman PKL', 'size' => 'PDF, 1.2 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
        array( 'name' => 'Brosur TMB 2025', 'size' => 'PDF, 4.0 MB', 'icon' => 'fas fa-file-pdf', 'file_link' => 'url:%23|target:_blank' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Unduhan', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_unduhan',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-download',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'downloads',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Unduhan',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-download',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Temukan dokumen pendukung yang kamu butuhkan untuk mengenal lebih dalam Program Studi Teknik Mekanikal Bandar Udara.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Baris Toggle', 'ppic-custom-element' ),
                    'param_name'  => 'toggle_icon',
                    'value'       => 'fas fa-file-pdf',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Baris Toggle', 'ppic-custom-element' ),
                    'param_name'  => 'toggle_text',
                    'value'       => 'Temukan dokumen pendukung yang kamu butuhkan',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                // REPEATER UNDUHAN
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar File Unduhan', 'ppic-custom-element' ),
                    'param_name'  => 'items',
                    'value'       => urlencode( wp_json_encode( $dummy_unduhan ) ),
                    'group'       => __( 'Data Dokumen', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textfield', 'heading' => 'Nama Dokumen', 'param_name' => 'name', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Ukuran & Format (Contoh: PDF, 2.4 MB)', 'param_name' => 'size'),
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon', 'value' => 'fas fa-file-pdf'),
                        array('type' => 'vc_link', 'heading' => 'URL File / Link Download', 'param_name' => 'file_link'),
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