<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_kenapa_lokasi', 'ppic_kenapa_lokasi_render' );
function ppic_kenapa_lokasi_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'       => 'Lokasi Strategis & Konektivitas Transportasi',
            'subtitle'    => 'PPI Curug berada di jantung kawasan bisnis premium <strong>BSD, Gading Serpong, Citra Raya</strong>, dan dekat dengan bandara internasional, stasiun KRL, serta terminal bus. Kemudahan akses ini menjadikan kampus sangat terintegrasi dengan moda transportasi umum.',
            'map_lat'     => '-6.236873', // Koordinat PPI Curug
            'map_lng'     => '106.559388',
            'map_zoom'    => '11',
            'popup_title' => 'PPI Curug',
            'popup_desc'  => 'Politeknik Penerbangan Indonesia Campus',
            'legends'     => '',
            'el_id'       => '',
            'el_class'    => '',
        ),
        $atts
    );

    // Proses data legends
    $legends = vc_param_group_parse_atts( $atts['legends'] );

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-lokasi-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );
    
    // Unique ID untuk container peta
    $map_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-map-' ) : uniqid( 'ppic-map-' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-lokasi-container">
            <div class="ppic-lokasi-header">
                <h2 class="ppic-lokasi-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="ppic-lokasi-subtitle"><?php echo wp_kses_post( $atts['subtitle'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="ppic-map-wrapper">
                <div id="<?php echo esc_attr( $map_id ); ?>" class="ppic-interactive-map"></div>
            </div>

            <?php if ( ! empty( $legends ) && is_array( $legends ) ) : ?>
                <div class="ppic-lokasi-legend">
                    <?php foreach ( $legends as $legend ) : 
                        $color = isset( $legend['color'] ) ? trim( $legend['color'] ) : '#000000';
                        $label = isset( $legend['label'] ) ? trim( $legend['label'] ) : '';
                        
                        if ( empty( $label ) ) continue;
                        ?>
                        <div class="ppic-legend-item">
                            <div class="ppic-legend-color" style="background-color: <?php echo esc_attr( $color ); ?>;"></div>
                            <span><?php echo esc_html( $label ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    // Inline Script untuk inisialisasi Peta Leaflet secara dinamis
    static $leaflet_loaded = false;
    if ( ! $leaflet_loaded ) {
        $leaflet_loaded = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function initPPICMap(mapElementId, lat, lng, zoom, title, desc) {
                    var map = L.map(mapElementId, {
                        zoomControl: true,
                        scrollWheelZoom: true // MENGAKTIFKAN ZOOM MOUSE WHEEL
                    }).setView([lat, lng], zoom);

                    // Tile Layer (Gaya Light/Terang sesuai referensi)
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                        subdomains: 'abcd',
                        maxZoom: 19
                    }).addTo(map);

                    // Radius Lingkaran Putus-putus Base (8km)
                    L.circle([lat, lng], {
                        color: '#fdbb11',
                        fillColor: '#fdbb11',
                        fillOpacity: 0.05,
                        radius: 8000, 
                        weight: 1.5,
                        dashArray: '6, 8'
                    }).addTo(map);

                    // Custom Icon (Universitas PPI Curug Utama)
                    var curugIcon = L.divIcon({
                        html: '<i class="fas fa-university" style="font-size: 38px; color: #e03a2e; filter: drop-shadow(0px 3px 4px rgba(0,0,0,0.3));"></i>',
                        className: 'custom-div-icon',
                        iconSize: [38, 38],
                        iconAnchor: [19, 19]
                    });

                    // Marker Utama PPI Curug
                    var marker = L.marker([lat, lng], {icon: curugIcon}).addTo(map);
                    
                    if (title || desc) {
                        var popupContent = '<b style="color: #00305b; font-size: 15px;">' + title + '</b><br /><span style="font-size: 13px;">' + desc + '</span>';
                        marker.bindPopup(popupContent).openPopup();
                    }

                    // Tambahan marker dekoratif (Bisa diubah/koneksikan dengan PHP jika ingin dinamis)
                    var extraMarkers = [
                        // Kawasan Bisnis (Biru)
                        { coords: [-6.299446, 106.653457], icon: 'fa-city', color: '#2c7da0', size: 28 }, // BSD
                        { coords: [-6.241586, 106.628464], icon: 'fa-city', color: '#2c7da0', size: 28 }, // Gading Serpong
                        { coords: [-6.244301, 106.529813], icon: 'fa-city', color: '#2c7da0', size: 28 }, // Citra Raya
                        // Bandara (Ungu)
                        { coords: [-6.125556, 106.655833], icon: 'fa-plane-departure', color: '#6c3483', size: 30 }, // CGK
                        { coords: [-6.266209, 106.890693], icon: 'fa-plane-departure', color: '#6c3483', size: 30 }, // Halim
                        // Stasiun (Hijau)
                        { coords: [-6.179374, 106.633465], icon: 'fa-train', color: '#2e7d32', size: 24 }, // Tangerang
                        { coords: [-6.327885, 106.643257], icon: 'fa-train', color: '#2e7d32', size: 24 }, // Cisauk
                        { coords: [-6.319760, 106.674068], icon: 'fa-train', color: '#2e7d32', size: 24 }, // Rawa Buntu
                        // Terminal/Tol (Orange)
                        { coords: [-6.168541, 106.655291], icon: 'fa-bus', color: '#d97706', size: 24 }, // Poris
                        { coords: [-6.216315, 106.550186], icon: 'fa-road', color: '#d97706', size: 24 } // Tol Bitung
                    ];

                    var activeLine = null; // Penampung untuk garis hover

                    extraMarkers.forEach(function(em) {
                        var mIcon = L.divIcon({
                            html: '<i class="fas ' + em.icon + '" style="font-size: ' + em.size + 'px; color: ' + em.color + '; filter: drop-shadow(0px 2px 3px rgba(0,0,0,0.25)); transition: transform 0.2s;"></i>',
                            className: 'custom-div-icon',
                            iconSize: [em.size, em.size],
                            iconAnchor: [em.size/2, em.size/2]
                        });
                        
                        var extraMarker = L.marker(em.coords, {icon: mIcon}).addTo(map);

                        // EFEK HOVER: Munculkan garis putus-putus
                        extraMarker.on('mouseover', function(e) {
                            // Besarkan icon sedikit saat di-hover
                            e.target._icon.querySelector('i').style.transform = 'scale(1.2)';
                            
                            // Jika ada garis aktif sebelumnya, hapus dulu
                            if (activeLine) {
                                map.removeLayer(activeLine);
                            }
                            
                            // Buat polyline (garis putus-putus) baru dari kampus ke titik marker yang dihover
                            activeLine = L.polyline([[lat, lng], em.coords], {
                                color: '#fdbb11', // Warna kuning emas PPI Curug
                                weight: 3,
                                dashArray: '8, 8', // Jarak putus-putus
                                opacity: 0.8
                            }).addTo(map);
                        });

                        // HAPUS EFEK HOVER: Hilangkan garis saat mouse keluar
                        extraMarker.on('mouseout', function(e) {
                            e.target._icon.querySelector('i').style.transform = 'scale(1)';
                            
                            if (activeLine) {
                                map.removeLayer(activeLine);
                                activeLine = null;
                            }
                        });
                    });
                }

                function loadLeafletAndInit() {
                    var mapElementId = '<?php echo esc_js( $map_id ); ?>';
                    var lat = parseFloat('<?php echo esc_js( $atts['map_lat'] ); ?>');
                    var lng = parseFloat('<?php echo esc_js( $atts['map_lng'] ); ?>');
                    var zoom = parseInt('<?php echo esc_js( $atts['map_zoom'] ); ?>', 10);
                    var popupTitle = '<?php echo esc_js( $atts['popup_title'] ); ?>';
                    var popupDesc = '<?php echo esc_js( $atts['popup_desc'] ); ?>';

                    if (typeof L === 'undefined') {
                        // Memuat CSS Leaflet
                        var css = document.createElement('link');
                        css.rel = 'stylesheet';
                        css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                        document.head.appendChild(css);

                        // Memuat JS Leaflet
                        var script = document.createElement('script');
                        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                        script.onload = function() {
                            initPPICMap(mapElementId, lat, lng, zoom, popupTitle, popupDesc);
                        };
                        document.head.appendChild(script);
                    } else {
                        initPPICMap(mapElementId, lat, lng, zoom, popupTitle, popupDesc);
                    }
                }

                loadLeafletAndInit();
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_lokasi_konektivitas_element' );
function ppic_register_lokasi_konektivitas_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_legends = array(
        array( 'color' => '#e03a2e', 'label' => 'Kampus PPI Curug' ),
        array( 'color' => '#2c7da0', 'label' => 'Kawasan Bisnis (CBD)' ),
        array( 'color' => '#6c3483', 'label' => 'Bandara Internasional' ),
        array( 'color' => '#2e7d32', 'label' => 'Stasiun KRL' ),
        array( 'color' => '#d97706', 'label' => 'Terminal Bus / Gerbang Tol' )
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Kenapa - Lokasi & Konektivitas', 'ppic-custom-element' ),
            'base'     => 'ppic_kenapa_lokasi',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-location-alt',
            'params'   => array(
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Lokasi Strategis & Konektivitas Transportasi',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Subjudul', 'ppic-custom-element' ),
                    'param_name' => 'subtitle',
                    'value'      => 'PPI Curug berada di jantung kawasan bisnis premium <strong>BSD, Gading Serpong, Citra Raya</strong>, dan dekat dengan bandara internasional, stasiun KRL, serta terminal bus. Kemudahan akses ini menjadikan kampus sangat terintegrasi dengan moda transportasi umum.',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Latitude Peta', 'ppic-custom-element' ),
                    'param_name' => 'map_lat',
                    'value'      => '-6.236873',
                    'group'      => __( 'Pengaturan Peta', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Longitude Peta', 'ppic-custom-element' ),
                    'param_name' => 'map_lng',
                    'value'      => '106.559388',
                    'group'      => __( 'Pengaturan Peta', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Zoom Level', 'ppic-custom-element' ),
                    'param_name' => 'map_zoom',
                    'value'      => '11',
                    'description'=> 'Angka antara 1 hingga 18. Default: 11.',
                    'group'      => __( 'Pengaturan Peta', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Popup Marker', 'ppic-custom-element' ),
                    'param_name' => 'popup_title',
                    'value'      => 'PPI Curug',
                    'group'      => __( 'Pengaturan Peta', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Deskripsi Popup Marker', 'ppic-custom-element' ),
                    'param_name' => 'popup_desc',
                    'value'      => 'Politeknik Penerbangan Indonesia Campus',
                    'group'      => __( 'Pengaturan Peta', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Keterangan Legend Warna', 'ppic-custom-element' ),
                    'param_name' => 'legends',
                    'value'      => urlencode( wp_json_encode( $dummy_legends ) ),
                    'params'     => array(
                        array(
                            'type'       => 'colorpicker',
                            'heading'    => __( 'Warna Dot', 'ppic-custom-element' ),
                            'param_name' => 'color',
                            'value'      => '#e03a2e',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Label Keterangan', 'ppic-custom-element' ),
                            'param_name' => 'label',
                            'admin_label'=> true,
                        ),
                    ),
                ),
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'js_composer' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}