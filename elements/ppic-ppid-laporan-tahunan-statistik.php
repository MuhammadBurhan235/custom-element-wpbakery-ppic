<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_laporan_tahunan_statistik', 'ppic_laporan_tahunan_statistik_render' );
function ppic_laporan_tahunan_statistik_render( $atts ) {
    $atts = shortcode_atts(
        array(
            // KARTU 1: LAPORAN TAHUNAN
            'c1_title'        => 'Laporan Tahunan PPID',
            'c1_desc'         => 'Dokumen resmi laporan kinerja dan pengelolaan informasi publik setiap tahun.',
            'c1_items'        => '',
            'c1_btn_text'     => 'Arsip Lengkap',
            'c1_btn_url'      => 'url:%23',
            
            // KARTU 2: STATISTIK
            'c2_title'        => 'Statistik Layanan Informasi',
            'c2_desc'         => 'Data permintaan, keberatan, dan sengketa informasi publik periode 2020–2024.',
            'c2_stat1_label'  => 'Permintaan Masuk',
            'c2_stat2_label'  => 'Tingkat Penyelesaian',
            'c2_stat3_label'  => 'Keberatan Diajukan',
            
            // INPUT MATEMATIS & GRAFIK
            'c2_chart_items'  => '',
            'c2_total_selesai'=> '342', // Data Selesai (Untuk hitung persentase)
            'c2_total_keberatan'=> '12',  // Data Keberatan
            'c2_chart_note'   => 'Tren jumlah permintaan informasi 5 tahun terakhir',
            
            // UMUM
            'el_id'           => '',
            'el_class'        => '',
        ),
        $atts
    );

    // Fallback Dummy Data Laporan
    $dummy_laporan = array(
        array( 'tahun' => '2024', 'judul' => 'Laporan Tahunan PPID PPI Curug 2024', 'url' => '#' ),
        array( 'tahun' => '2023', 'judul' => 'Laporan Tahunan PPID PPI Curug 2023', 'url' => '#' ),
        array( 'tahun' => '2022', 'judul' => 'Laporan Tahunan PPID PPI Curug 2022', 'url' => '#' ),
    );

    // Fallback Dummy Data Grafik
    $dummy_chart = array(
        array( 'tahun' => '2020', 'jumlah' => '42' ),
        array( 'tahun' => '2021', 'jumlah' => '58' ),
        array( 'tahun' => '2022', 'jumlah' => '74' ),
        array( 'tahun' => '2023', 'jumlah' => '88' ),
        array( 'tahun' => '2024', 'jumlah' => '85' ),
    );

    // Parse Data Tabel Laporan (Kartu 1) dengan Fallback
    $laporan_items = vc_param_group_parse_atts( $atts['c1_items'] );
    if ( empty( $laporan_items ) || ! is_array( $laporan_items ) ) {
        $laporan_items = $dummy_laporan;
    }

    // Parse Data Grafik Tahunan (Kartu 2) dengan Fallback
    $chart_items = vc_param_group_parse_atts( $atts['c2_chart_items'] );
    if ( empty( $chart_items ) || ! is_array( $chart_items ) ) {
        $chart_items = $dummy_chart;
    }

    // Parse URL Tombol Arsip
    $link_arsip = ( '||' !== $atts['c1_btn_url'] ) ? vc_build_link( $atts['c1_btn_url'] ) : '';
    $a_href     = ! empty( $link_arsip['url'] ) ? $link_arsip['url'] : '#';
    $a_target   = ! empty( $link_arsip['target'] ) ? ' target="' . esc_attr( trim( $link_arsip['target'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-lapstat-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // ==========================================
    // LOGIKA EKSTRAKSI & PERHITUNGAN MATEMATIS
    // ==========================================
    $chart_labels_arr = array();
    $chart_data_arr   = array();

    // 1. Ekstrak Data dari Param Group Grafik
    foreach ( $chart_items as $c_item ) {
        if ( isset( $c_item['tahun'] ) && $c_item['tahun'] !== '' ) {
            $chart_labels_arr[] = trim( $c_item['tahun'] );
            $chart_data_arr[]   = intval( $c_item['jumlah'] ?? 0 );
        }
    }
    
    // 2. Hitung Total Permintaan Masuk (Sum otomatis dari array jumlah)
    $total_permintaan = array_sum( $chart_data_arr );
    
    // 3. Hitung Persentase Tingkat Penyelesaian
    $total_selesai = intval( $atts['c2_total_selesai'] );
    $persentase_selesai = 0;
    if ( $total_permintaan > 0 ) {
        $persentase_selesai = round( ( $total_selesai / $total_permintaan ) * 100, 1 );
    }

    // 4. Data Keberatan
    $total_keberatan = intval( $atts['c2_total_keberatan'] );

    // Format Data JSON untuk disisipkan ke script JS Chart
    $chart_labels_json = json_encode( $chart_labels_arr );
    $chart_data_json   = json_encode( $chart_data_arr );
    $canvas_id = 'chart_' . uniqid();

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-lapstat-container">
            <div class="lapstat-grid">
                
                <!-- CARD 1: LAPORAN TAHUNAN -->
                <div class="lapstat-card">
                    <div class="lapstat-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h3 class="lapstat-title"><?php echo esc_html( $atts['c1_title'] ); ?></h3>
                    <p class="lapstat-desc"><?php echo esc_html( $atts['c1_desc'] ); ?></p>
                    
                    <div class="lapstat-table-wrapper">
                        <table class="lapstat-table">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">Tahun</th>
                                    <th>Judul Laporan</th>
                                    <th style="width: 80px; text-align: right;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( ! empty( $laporan_items ) ) : ?>
                                    <?php foreach ( $laporan_items as $item ) : 
                                        $tahun = isset( $item['tahun'] ) ? $item['tahun'] : '';
                                        $judul = isset( $item['judul'] ) ? $item['judul'] : '';
                                        $url   = isset( $item['url'] ) ? $item['url'] : '#';
                                        if( empty($tahun) && empty($judul) ) continue;
                                    ?>
                                    <tr>
                                        <td class="col-tahun"><?php echo esc_html( $tahun ); ?></td>
                                        <td class="col-judul"><?php echo esc_html( $judul ); ?></td>
                                        <td style="text-align: right;">
                                            <a href="<?php echo esc_url( $url ); ?>" class="btn-pdf-download" download>
                                                <i class="fas fa-download"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="3" style="text-align:center; padding:20px;">Belum ada laporan tahunan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ( ! empty( $atts['c1_btn_text'] ) ) : ?>
                    <div class="lapstat-footer-action">
                        <a href="<?php echo esc_url( $a_href ); ?>" class="btn-arsip-lengkap"<?php echo $a_target; ?>>
                            <i class="fas fa-archive"></i> <?php echo esc_html( $atts['c1_btn_text'] ); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- CARD 2: STATISTIK LAYANAN -->
                <div class="lapstat-card">
                    <h3 class="lapstat-title"><?php echo esc_html( $atts['c2_title'] ); ?></h3>
                    <p class="lapstat-desc"><?php echo esc_html( $atts['c2_desc'] ); ?></p>
                    
                    <div class="lapstat-stats-row">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo esc_html( $total_permintaan ); ?></div>
                            <div class="stat-label"><?php echo esc_html( $atts['c2_stat1_label'] ); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo esc_html( $persentase_selesai ); ?>%</div>
                            <div class="stat-label"><?php echo esc_html( $atts['c2_stat2_label'] ); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo esc_html( $total_keberatan ); ?></div>
                            <div class="stat-label"><?php echo esc_html( $atts['c2_stat3_label'] ); ?></div>
                        </div>
                    </div>
                    
                    <div class="lapstat-chart-container">
                        <canvas id="<?php echo esc_attr( $canvas_id ); ?>"></canvas>
                    </div>

                    <?php if ( ! empty( $atts['c2_chart_note'] ) ) : ?>
                    <p class="lapstat-chart-note">
                        <i class="fas fa-chart-line"></i> <?php echo esc_html( $atts['c2_chart_note'] ); ?>
                    </p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var labels = <?php echo $chart_labels_json; ?>;
            var dataValues = <?php echo $chart_data_json; ?>;
            var canvasId = '<?php echo esc_js( $canvas_id ); ?>';

            function renderChart() {
                var ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Permintaan Informasi',
                            data: dataValues,
                            borderColor: '#fdbb11',
                            backgroundColor: 'rgba(253, 187, 17, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#00305b',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    usePointStyle: false,
                                    font: { family: 'Inter', size: 11 },
                                    color: '#64748b'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8', stepSize: 10 }
                            },
                            x: {
                                border: { display: false },
                                grid: { display: false },
                                ticks: { font: { family: 'Inter', size: 12 }, color: '#64748b' }
                            }
                        }
                    }
                });
            }

            if (typeof Chart === 'undefined') {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.onload = renderChart;
                document.head.appendChild(script);
            } else {
                renderChart();
            }
        });
    </script>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_laporan_tahunan_statistik_element' );
function ppic_register_laporan_tahunan_statistik_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_laporan = array(
        array( 'tahun' => '2024', 'judul' => 'Laporan Tahunan PPID PPI Curug 2024', 'url' => '#' ),
        array( 'tahun' => '2023', 'judul' => 'Laporan Tahunan PPID PPI Curug 2023', 'url' => '#' ),
        array( 'tahun' => '2022', 'judul' => 'Laporan Tahunan PPID PPI Curug 2022', 'url' => '#' ),
    );

    $dummy_chart = array(
        array( 'tahun' => '2020', 'jumlah' => '42' ),
        array( 'tahun' => '2021', 'jumlah' => '58' ),
        array( 'tahun' => '2022', 'jumlah' => '74' ),
        array( 'tahun' => '2023', 'jumlah' => '88' ),
        array( 'tahun' => '2024', 'jumlah' => '85' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Laporan & Statistik', 'ppic-custom-element' ),
            'base'     => 'ppic_laporan_tahunan_statistik',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-chart-line',
            'params'   => array(
                // KARTU 1: LAPORAN
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_title',
                    'value'      => 'Laporan Tahunan PPID',
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Kartu 1', 'ppic-custom-element' ),
                    'param_name' => 'c1_desc',
                    'value'      => 'Dokumen resmi laporan kinerja dan pengelolaan informasi publik setiap tahun.',
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Laporan Tahunan', 'ppic-custom-element' ),
                    'param_name' => 'c1_items',
                    'value'      => urlencode( wp_json_encode( $dummy_laporan ) ),
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Tahun', 'param_name' => 'tahun', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Judul Laporan', 'param_name' => 'judul', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'URL File PDF', 'param_name' => 'url'),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol Arsip', 'ppic-custom-element' ),
                    'param_name' => 'c1_btn_text',
                    'value'      => 'Arsip Lengkap',
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tombol Arsip', 'ppic-custom-element' ),
                    'param_name' => 'c1_btn_url',
                    'value'      => 'url:%23',
                    'group'      => __( 'Kartu Laporan', 'ppic-custom-element' ),
                ),

                // KARTU 2: STATISTIK
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_title',
                    'value'      => 'Statistik Layanan Informasi',
                    'group'      => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi Kartu 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_desc',
                    'value'      => 'Data permintaan, keberatan, dan sengketa informasi publik periode 2020–2024.',
                    'group'      => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Data Grafik Tahunan', 'ppic-custom-element' ),
                    'param_name' => 'c2_chart_items',
                    'value'      => urlencode( wp_json_encode( $dummy_chart ) ),
                    'description'=> __( 'Isi Tahun dan Jumlah. Total permintaan akan otomatis dihitung dari sini.', 'ppic-custom-element' ),
                    'group'      => __( 'Kartu Statistik', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Tahun', 'param_name' => 'tahun', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Jumlah Permintaan Masuk', 'param_name' => 'jumlah', 'admin_label' => true),
                    ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Total Permintaan Diselesaikan', 'ppic-custom-element' ),
                    'param_name'  => 'c2_total_selesai',
                    'value'       => '342',
                    'description' => 'Angka ini digunakan sistem untuk menghitung "Tingkat Penyelesaian (%)" secara otomatis.',
                    'group'       => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Total Keberatan Diajukan', 'ppic-custom-element' ),
                    'param_name'  => 'c2_total_keberatan',
                    'value'       => '12',
                    'group'       => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Label Kotak 1', 'ppic-custom-element' ),
                    'param_name' => 'c2_stat1_label',
                    'value'      => 'Permintaan Masuk',
                    'group'      => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Label Kotak 2', 'ppic-custom-element' ),
                    'param_name' => 'c2_stat2_label',
                    'value'      => 'Tingkat Penyelesaian',
                    'group'      => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Label Kotak 3', 'ppic-custom-element' ),
                    'param_name' => 'c2_stat3_label',
                    'value'      => 'Keberatan Diajukan',
                    'group'      => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Catatan Bawah Grafik', 'ppic-custom-element' ),
                    'param_name' => 'c2_chart_note',
                    'value'      => 'Tren jumlah permintaan informasi 5 tahun terakhir',
                    'group'      => __( 'Kartu Statistik', 'ppic-custom-element' ),
                ),

                // UMUM
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