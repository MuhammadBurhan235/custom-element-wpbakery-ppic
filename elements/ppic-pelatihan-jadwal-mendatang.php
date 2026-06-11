<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_pelatihan_jadwal', 'ppic_pelatihan_jadwal_render' );
function ppic_pelatihan_jadwal_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'    => 'Jadwal Pelatihan Mendatang',
            'desc'     => 'Daftar jadwal pelatihan reguler di kampus PPI Curug. Untuk in-house training atau jadwal khusus, silakan hubungi kami.',
            'schedules'=> '',
            'btn_text' => 'Konsultasi Program Pelatihan',
            'btn_link' => 'url:https%3A%2F%2Fwa.me%2F6285156120178|target:_blank|title:Konsultasi%20Program',
            'btn_icon' => 'fab fa-whatsapp',
            'el_id'    => 'jadwal',
            'el_class' => '',
        ),
        $atts
    );

    // Proses data tabel jadwal
    $schedules = vc_param_group_parse_atts( $atts['schedules'] );

    // Parse URL Tombol Bawah
    $link = ( '||' !== $atts['btn_link'] ) ? vc_build_link( $atts['btn_link'] ) : '';
    $a_href   = ! empty( $link['url'] ) ? $link['url'] : '#';
    $a_title  = ! empty( $link['title'] ) ? $link['title'] : '';
    $a_target = ! empty( $link['target'] ) ? ' target="' . esc_attr( trim( $link['target'] ) ) . '"' : '';
    $a_rel    = ! empty( $link['rel'] ) ? ' rel="' . esc_attr( trim( $link['rel'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-jadwal-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-jadwal-container">
            
            <div class="ppic-jadwal-header">
                <h2 class="ppic-jadwal-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p class="ppic-jadwal-desc"><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="ppic-jadwal-table-wrapper">
                <table class="ppic-jadwal-table">
                    <thead>
                        <tr>
                            <th>Pelatihan</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( ! empty( $schedules ) && is_array( $schedules ) ) : ?>
                            <?php foreach ( $schedules as $sched ) : 
                                $name     = isset( $sched['name'] ) ? trim( $sched['name'] ) : '';
                                $date     = isset( $sched['date'] ) ? trim( $sched['date'] ) : '';
                                $duration = isset( $sched['duration'] ) ? trim( $sched['duration'] ) : '';
                                $st_text  = isset( $sched['status_text'] ) ? trim( $sched['status_text'] ) : '';
                                $st_color = isset( $sched['status_color'] ) ? trim( $sched['status_color'] ) : 'green';
                                $wa_text  = isset( $sched['wa_text'] ) ? trim( $sched['wa_text'] ) : 'Daftar';
                                $wa_link  = isset( $sched['wa_link'] ) ? trim( $sched['wa_link'] ) : '#';
                                
                                if ( empty( $name ) ) continue;
                                ?>
                                <tr>
                                    <td><strong><?php echo esc_html( $name ); ?></strong></td>
                                    <td><?php echo esc_html( $date ); ?></td>
                                    <td><?php echo esc_html( $duration ); ?></td>
                                    <td class="status-<?php echo esc_attr( $st_color ); ?>"><?php echo esc_html( $st_text ); ?></td>
                                    <td>
                                        <a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener noreferrer" class="btn-wa-table">
                                            <i class="fab fa-whatsapp" aria-hidden="true"></i> <?php echo esc_html( $wa_text ); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Belum ada jadwal pelatihan tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ( ! empty( $atts['btn_text'] ) ) : ?>
                <div class="ppic-jadwal-cta">
                    <a href="<?php echo esc_url( $a_href ); ?>" class="btn-jadwal-large" title="<?php echo esc_attr( $a_title ); ?>"<?php echo $a_target; ?><?php echo $a_rel; ?>>
                        <?php if ( ! empty( $atts['btn_icon'] ) ) : ?>
                            <i class="<?php echo esc_attr( $atts['btn_icon'] ); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                        <?php echo esc_html( $atts['btn_text'] ); ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_pelatihan_jadwal_element' );
function ppic_register_pelatihan_jadwal_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_schedules = array(
        array( 'name' => 'Basic AVSEC (Initial)', 'date' => '12 - 16 Mei 2025', 'duration' => '5 hari', 'status_text' => 'Tersedia', 'status_color' => 'green', 'wa_text' => 'Daftar', 'wa_link' => 'https://wa.me/6285156120178?text=Halo,%20saya%20daftar%20Basic%20AVSEC' ),
        array( 'name' => 'Type Rating Airbus A320 (Initial)', 'date' => '19 Mei - 13 Juni 2025', 'duration' => '4 minggu', 'status_text' => 'Tersedia', 'status_color' => 'green', 'wa_text' => 'Daftar', 'wa_link' => 'https://wa.me/6285156120178?text=Halo,%20saya%20daftar%20A320' ),
        array( 'name' => 'CASR 147 Category A1.3 (Piston)', 'date' => '26 - 30 Mei 2025', 'duration' => '5 hari', 'status_text' => 'Tersedia', 'status_color' => 'green', 'wa_text' => 'Daftar', 'wa_link' => 'https://wa.me/6285156120178?text=Halo,%20daftar%20A1.3' ),
        array( 'name' => 'ATC Aerodrome Control (Refresher)', 'date' => '2 - 13 Juni 2025', 'duration' => '10 hari', 'status_text' => 'Hampir Penuh', 'status_color' => 'orange', 'wa_text' => 'Tanya', 'wa_link' => 'https://wa.me/6285156120178?text=ATC%20Refresher%20Juni' ),
        array( 'name' => 'PKP-PK Basic & Junior', 'date' => '9 - 13 Juni 2025', 'duration' => '5 hari', 'status_text' => 'Tersedia', 'status_color' => 'green', 'wa_text' => 'Daftar', 'wa_link' => 'https://wa.me/6285156120178?text=PKP-PK%20Juni' ),
        array( 'name' => 'Dangerous Goods (Initial)', 'date' => '16 - 18 Juni 2025', 'duration' => '3 hari', 'status_text' => 'Tersedia', 'status_color' => 'green', 'wa_text' => 'Daftar', 'wa_link' => 'https://wa.me/6285156120178?text=DG%20Initial%20Juni' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Pelatihan - Jadwal', 'ppic-custom-element' ),
            'base'     => 'ppic_pelatihan_jadwal',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-calendar-alt',
            'params'   => array(
                // HEADER
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Jadwal Pelatihan Mendatang',
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'Daftar jadwal pelatihan reguler di kampus PPI Curug. Untuk in-house training atau jadwal khusus, silakan hubungi kami.',
                ),

                // TABEL JADWAL
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Baris Jadwal Pelatihan', 'ppic-custom-element' ),
                    'param_name' => 'schedules',
                    'value'      => urlencode( wp_json_encode( $dummy_schedules ) ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Nama Pelatihan', 'param_name' => 'name', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Tanggal', 'param_name' => 'date'),
                        array('type' => 'textfield', 'heading' => 'Durasi', 'param_name' => 'duration'),
                        array('type' => 'textfield', 'heading' => 'Teks Status', 'param_name' => 'status_text', 'value' => 'Tersedia'),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => 'Warna Status',
                            'param_name' => 'status_color',
                            'value'      => array(
                                'Hijau (Tersedia)'      => 'green',
                                'Orange (Hampir Penuh)' => 'orange',
                                'Merah (Penuh)'         => 'red',
                            )
                        ),
                        array('type' => 'textfield', 'heading' => 'Teks Tombol WA', 'param_name' => 'wa_text', 'value' => 'Daftar'),
                        array('type' => 'textfield', 'heading' => 'Link WA', 'param_name' => 'wa_link', 'value' => 'https://wa.me/6285156120178'),
                    ),
                ),

                // TOMBOL CTA BAWAH
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'btn_text',
                    'value'      => 'Konsultasi Program Pelatihan',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Ikon Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_icon',
                    'value'      => 'fab fa-whatsapp',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'Link URL Tombol', 'ppic-custom-element' ),
                    'param_name' => 'btn_link',
                    'value'      => 'url:https%3A%2F%2Fwa.me%2F6285156120178|target:_blank|title:Konsultasi%20Program',
                    'group'      => __( 'Tombol CTA', 'ppic-custom-element' ),
                ),

                // UMUM
                array(
                    'type'        => 'el_id',
                    'heading'     => __( 'Element ID', 'js_composer' ),
                    'param_name'  => 'el_id',
                    'value'       => 'jadwal',
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