<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_main_function_normalize_icon_class( $icon_class ) {
    $icon_class = trim( (string) $icon_class );

    if ( '' === $icon_class ) {
        return 'fas fa-check';
    }

    $parts = preg_split( '/\s+/', $icon_class );
    $parts = array_values( array_unique( array_filter( $parts ) ) );

    $style_aliases = array(
        'fa-solid' => 'fas',
        'fa-regular' => 'far',
        'fa-brands' => 'fab',
        'fa-light' => 'fal',
        'fa-duotone' => 'fad',
        'fa-thin' => 'fat',
        'fa' => 'fas',
    );

    $style_class = '';
    $icon_name = '';
    $icon_aliases = array(
        'fa-chalkboard' => 'fa-chalkboard-teacher',
    );

    foreach ( $parts as $part ) {
        if ( isset( $style_aliases[ $part ] ) ) {
            $style_class = $style_aliases[ $part ];
            continue;
        }

        if ( preg_match( '/^fa[srbldtk]?$/', $part ) ) {
            $style_class = $part;
            continue;
        }

        if ( 0 === strpos( $part, 'fa-' ) ) {
            $icon_name = $part;
        }
    }

    if ( '' === $icon_name ) {
        return 'fas fa-check';
    }

    if ( isset( $icon_aliases[ $icon_name ] ) ) {
        $icon_name = $icon_aliases[ $icon_name ];
    }

    if ( '' === $style_class ) {
        $style_class = 'fas';
    }

    return trim( $style_class . ' ' . $icon_name );
}

function ppic_main_function_default_icon_map() {
    return array(
        'Penyusunan rencana & program pendidikan' => 'fas fa-chalkboard-teacher',
        'Penyelenggaraan pendidikan vokasi' => 'fas fa-graduation-cap',
        'Penelitian & pengabdian masyarakat' => 'fas fa-flask',
        'Pemeriksaan intern' => 'fas fa-clipboard-list',
        'Penjaminan mutu' => 'fas fa-chart-line',
        'Administrasi akademik & ketarunaan' => 'fas fa-user-graduate',
        'Keuangan, umum & kerjasama' => 'fas fa-coins',
        'Pengembangan program, data & evaluasi' => 'fas fa-search',
        'Pembangunan karakter' => 'fas fa-hands-helping',
        'Unit penunjang & pengembangan usaha' => 'fas fa-building',
        'Administrasi umum' => 'fas fa-file-alt',
        'Pembinaan sivitas & lingkungan' => 'fas fa-users',
        'Evaluasi & pelaporan' => 'fas fa-chart-bar',
    );
}

function ppic_main_function_get_default_icon_by_title( $title ) {
    $map = ppic_main_function_default_icon_map();
    $title = trim( (string) $title );

    return isset( $map[ $title ] ) ? $map[ $title ] : 'fas fa-check';
}

// 1. Render Shortcode
add_shortcode( 'ppic_main_function', 'ppic_main_function_render' );
function ppic_main_function_render( $atts ) {
    // Memaksa WPBakery memuat CSS FontAwesome
    if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
        vc_icon_element_fonts_enqueue( 'fontawesome' );
    }

    // 13 Data Default (Menggunakan 'icon_fontawesome' sebagai key)
    $default_items = urlencode( wp_json_encode( array(
        array( 'icon_class' => 'fas fa-chalkboard-teacher', 'item_title' => 'Penyusunan rencana & program pendidikan', 'item_desc' => 'Merumuskan rencana strategis dan program akademik.' ),
        array( 'icon_class' => 'fas fa-graduation-cap', 'item_title' => 'Penyelenggaraan pendidikan vokasi', 'item_desc' => 'Menyelenggarakan pendidikan vokasi unggul di bidang penerbangan.' ),
        array( 'icon_class' => 'fas fa-flask', 'item_title' => 'Penelitian & pengabdian masyarakat', 'item_desc' => 'Penelitian terapan dan pengabdian masyarakat.' ),
        array( 'icon_class' => 'fas fa-clipboard-list', 'item_title' => 'Pemeriksaan intern', 'item_desc' => 'Pemeriksaan internal secara berkala.' ),
        array( 'icon_class' => 'fas fa-chart-line', 'item_title' => 'Penjaminan mutu', 'item_desc' => 'Pengembangan sistem penjaminan mutu.' ),
        array( 'icon_class' => 'fas fa-user-graduate', 'item_title' => 'Administrasi akademik & ketarunaan', 'item_desc' => 'Pengelolaan administrasi akademik dan pembinaan taruna.' ),
        array( 'icon_class' => 'fas fa-coins', 'item_title' => 'Keuangan, umum & kerjasama', 'item_desc' => 'Pengelolaan keuangan, urusan umum, dan kemitraan.' ),
        array( 'icon_class' => 'fas fa-search', 'item_title' => 'Pengembangan program, data & evaluasi', 'item_desc' => 'Pengembangan program, data, dan evaluasi kinerja.' ),
        array( 'icon_class' => 'fas fa-hands-helping', 'item_title' => 'Pembangunan karakter', 'item_desc' => 'Pembinaan karakter dan kepribadian taruna.' ),
        array( 'icon_class' => 'fas fa-building', 'item_title' => 'Unit penunjang & pengembangan usaha', 'item_desc' => 'Mengelola unit penunjang dan mengembangkan usaha.' ),
        array( 'icon_class' => 'fas fa-file-alt', 'item_title' => 'Administrasi umum', 'item_desc' => 'Tata kelola administrasi secara profesional.' ),
        array( 'icon_class' => 'fas fa-users', 'item_title' => 'Pembinaan sivitas & lingkungan', 'item_desc' => 'Membina sivitas akademika dan hubungan eksternal.' ),
        array( 'icon_class' => 'fas fa-chart-bar', 'item_title' => 'Evaluasi & pelaporan', 'item_desc' => 'Evaluasi menyeluruh dan pelaporan kinerja.' ),
    ) ) );

    $atts = shortcode_atts(
        array(
            'title'           => 'Fungsi',
            'title_highlight' => 'PPI Curug',
            'subtitle'        => '13 fungsi utama yang menjadi landasan operasional institusi.',
            'function_items'  => $default_items,
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Parse data dari param_group
    $items = array();
    if ( ! empty( $atts['function_items'] ) ) {
        $items = vc_param_group_parse_atts( $atts['function_items'] );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'tugasfungsi-section ' . esc_attr( $atts['el_class'] );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container">
            <h2 class="section-title"><?php echo esc_html( $atts['title'] ); ?> <span><?php echo esc_html( $atts['title_highlight'] ); ?></span></h2>
            <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                <p class="section-sub"><?php echo esc_html( $atts['subtitle'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
                <div class="fungsi-grid">
                    <?php foreach ( $items as $item ) : ?>
                        <?php 
                        // Fallback ke 'icon' jika ada sisa data lama, jika tidak pakai 'icon_fontawesome'
                        $icon_class = ! empty( $item['icon_class'] ) ? $item['icon_class'] : ( ! empty( $item['icon_fontawesome'] ) ? $item['icon_fontawesome'] : ( ! empty( $item['icon'] ) ? $item['icon'] : 'fas fa-check' ) );
                        $icon_class = ppic_main_function_normalize_icon_class( $icon_class );
                        
                        $title = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
                        $desc  = ! empty( $item['item_desc'] ) ? $item['item_desc'] : '';

                        if ( 'fab fa-accessible-icon' === $icon_class ) {
                            $icon_class = ppic_main_function_get_default_icon_by_title( $title );
                        }
                        ?>
                        <div class="fungsi-card">
                            <div class="fungsi-icon">
                                <i class="<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></i>
                            </div>
                            <div class="fungsi-text">
                                <h4><?php echo esc_html( $title ); ?></h4>
                                <?php if ( ! empty( $desc ) ) : ?>
                                    <p><?php echo esc_html( $desc ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// 2. Mapping ke WPBakery
add_action( 'vc_before_init', 'ppic_main_function_map' );
function ppic_main_function_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $default_items = urlencode( wp_json_encode( array(
        array( 'icon_class' => 'fas fa-chalkboard-teacher', 'item_title' => 'Penyusunan rencana & program pendidikan', 'item_desc' => 'Merumuskan rencana strategis dan program akademik.' ),
        array( 'icon_class' => 'fas fa-graduation-cap', 'item_title' => 'Penyelenggaraan pendidikan vokasi', 'item_desc' => 'Menyelenggarakan pendidikan vokasi unggul di bidang penerbangan.' ),
        array( 'icon_class' => 'fas fa-flask', 'item_title' => 'Penelitian & pengabdian masyarakat', 'item_desc' => 'Penelitian terapan dan pengabdian masyarakat.' ),
        array( 'icon_class' => 'fas fa-clipboard-list', 'item_title' => 'Pemeriksaan intern', 'item_desc' => 'Pemeriksaan internal secara berkala.' ),
        array( 'icon_class' => 'fas fa-chart-line', 'item_title' => 'Penjaminan mutu', 'item_desc' => 'Pengembangan sistem penjaminan mutu.' ),
        array( 'icon_class' => 'fas fa-user-graduate', 'item_title' => 'Administrasi akademik & ketarunaan', 'item_desc' => 'Pengelolaan administrasi akademik dan pembinaan taruna.' ),
        array( 'icon_class' => 'fas fa-coins', 'item_title' => 'Keuangan, umum & kerjasama', 'item_desc' => 'Pengelolaan keuangan, urusan umum, dan kemitraan.' ),
        array( 'icon_class' => 'fas fa-search', 'item_title' => 'Pengembangan program, data & evaluasi', 'item_desc' => 'Pengembangan program, data, dan evaluasi kinerja.' ),
        array( 'icon_class' => 'fas fa-hands-helping', 'item_title' => 'Pembangunan karakter', 'item_desc' => 'Pembinaan karakter dan kepribadian taruna.' ),
        array( 'icon_class' => 'fas fa-building', 'item_title' => 'Unit penunjang & pengembangan usaha', 'item_desc' => 'Mengelola unit penunjang dan mengembangkan usaha.' ),
        array( 'icon_class' => 'fas fa-file-alt', 'item_title' => 'Administrasi umum', 'item_desc' => 'Tata kelola administrasi secara profesional.' ),
        array( 'icon_class' => 'fas fa-users', 'item_title' => 'Pembinaan sivitas & lingkungan', 'item_desc' => 'Membina sivitas akademika dan hubungan eksternal.' ),
        array( 'icon_class' => 'fas fa-chart-bar', 'item_title' => 'Evaluasi & pelaporan', 'item_desc' => 'Evaluasi menyeluruh dan pelaporan kinerja.' ),
    ) ) );

    vc_map(
        array(
            'name'     => __( 'PPIC Fungsi Utama', 'ppic-custom-element' ),
            'base'     => 'ppic_main_function',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-grid-view',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama (Teks Hitam)', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Fungsi',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Highlight (Teks Kuning)', 'ppic-custom-element' ),
                    'param_name' => 'title_highlight',
                    'value'      => 'PPI Curug',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Sub-judul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => '13 fungsi utama yang menjadi landasan operasional institusi.',
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Fungsi', 'ppic-custom-element' ),
                    'param_name' => 'function_items',
                    'value'      => $default_items,
                    'params'     => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Class Icon Font Awesome', 'ppic-custom-element' ),
                            'param_name' => 'icon_class',
                            'value'      => 'fas fa-check',
                            'description'=> __( 'Contoh: fas fa-graduation-cap', 'ppic-custom-element' ),
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Judul Fungsi', 'ppic-custom-element' ),
                            'param_name'  => 'item_title',
                            'admin_label' => true,
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Deskripsi Singkat', 'ppic-custom-element' ),
                            'param_name' => 'item_desc',
                        ),
                    ),
                ),
                array(
                    'type'       => 'el_id',
                    'heading'    => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                ),
            ),
        )
    );
}