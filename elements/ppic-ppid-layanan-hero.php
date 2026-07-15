<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_layanan_hero', 'ppic_ppid_layanan_hero_render' );
function ppic_ppid_layanan_hero_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'          => 'Layanan Informasi Publik',
            'desc'           => 'PPID PPI Curug memberikan akses informasi yang mudah, transparan, dan akuntabel bagi seluruh pemohon informasi publik',
            'card_title'     => 'Jadwal Pelayanan Informasi',
            'schedule_items' => '',
            'card_note'      => 'Layanan informasi tetap dapat diakses secara daring melalui formulir permintaan online.',
            'el_id'          => '',
            'el_class'       => '',
        ),
        $atts
    );

    // Parsing Param Group untuk Jadwal
    $schedule_items = vc_param_group_parse_atts( $atts['schedule_items'] );
    
    // Fallback Data Dummy jika kosong
    if ( empty( $schedule_items ) || ! is_array( $schedule_items ) ) {
        $schedule_items = array(
            array(
                'day_text'   => 'Senin – Kamis',
                'status'     => 'buka',
                'time_text'  => '09.00 - 16.00 WIB',
                'break_text' => 'Istirahat 12.00 - 13.00 WIB',
            ),
            array(
                'day_text'   => 'Jum\'at',
                'status'     => 'buka',
                'time_text'  => '09.00 - 16.00 WIB',
                'break_text' => 'Istirahat 11.30 - 13.00 WIB',
            ),
            array(
                'day_text'   => 'Sabtu, Minggu & Hari Besar',
                'status'     => 'tutup',
                'time_text'  => 'Pelayanan Tutup',
                'break_text' => '',
            ),
        );
    }

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-layanan-hero ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container layanan-hero-grid">
            
            <div class="layanan-hero-content">
                <h1><?php echo esc_html( $atts['title'] ); ?></h1>
                <?php if ( ! empty( $atts['desc'] ) ) : ?>
                    <p><?php echo wp_kses_post( $atts['desc'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="layanan-schedule-card">
                <h3><i class="fas fa-clock"></i> <?php echo esc_html( $atts['card_title'] ); ?></h3>
                
                <div class="schedule-list">
                    <?php foreach ( $schedule_items as $item ) : 
                        $day    = isset( $item['day_text'] ) ? $item['day_text'] : '';
                        $status = isset( $item['status'] ) ? $item['status'] : 'buka';
                        $time   = isset( $item['time_text'] ) ? $item['time_text'] : '';
                        $break  = isset( $item['break_text'] ) ? $item['break_text'] : '';
                        if ( empty( $day ) ) continue;
                    ?>
                        <div class="schedule-item">
                            <div class="schedule-day">
                                <i class="far fa-calendar-alt"></i> <?php echo esc_html( $day ); ?>
                            </div>
                            
                            <?php if ( $status === 'buka' ) : ?>
                                <div class="schedule-time">
                                    <i class="far fa-clock"></i> <?php echo esc_html( $time ); ?>
                                </div>
                                <?php if ( ! empty( $break ) ) : ?>
                                    <div class="break-time">
                                        <i class="fas fa-square"></i> <?php echo esc_html( $break ); ?>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <div class="schedule-time closed">
                                    <i class="fas fa-times"></i> <?php echo esc_html( $time ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( ! empty( $atts['card_note'] ) ) : ?>
                    <div class="schedule-note">
                        <i class="fas fa-info-circle"></i> <?php echo esc_html( $atts['card_note'] ); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_layanan_hero_element' );
function ppic_register_ppid_layanan_hero_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    $dummy_schedule = array(
        array( 'day_text' => 'Senin – Kamis', 'status' => 'buka', 'time_text' => '09.00 - 16.00 WIB', 'break_text' => 'Istirahat 12.00 - 13.00 WIB' ),
        array( 'day_text' => 'Jum\'at', 'status' => 'buka', 'time_text' => '09.00 - 16.00 WIB', 'break_text' => 'Istirahat 11.30 - 13.00 WIB' ),
        array( 'day_text' => 'Sabtu, Minggu & Hari Besar', 'status' => 'tutup', 'time_text' => 'Pelayanan Tutup', 'break_text' => '' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Layanan - Hero', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_layanan_hero',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-clock',
            'params'   => array(
                // KONTEN KIRI
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Utama', 'ppic-custom-element' ),
                    'param_name' => 'title',
                    'value'      => 'Layanan Informasi Publik',
                    'group'      => __( 'Teks Header', 'ppic-custom-element' ),
                    'admin_label'=> true,
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Deskripsi', 'ppic-custom-element' ),
                    'param_name' => 'desc',
                    'value'      => 'PPID PPI Curug memberikan akses informasi yang mudah, transparan, dan akuntabel bagi seluruh pemohon informasi publik',
                    'group'      => __( 'Teks Header', 'ppic-custom-element' ),
                ),

                // KARTU JADWAL (KANAN)
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Judul Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_title',
                    'value'      => 'Jadwal Pelayanan Informasi',
                    'group'      => __( 'Kartu Jadwal', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Daftar Jadwal', 'ppic-custom-element' ),
                    'param_name' => 'schedule_items',
                    'value'      => urlencode( wp_json_encode( $dummy_schedule ) ),
                    'group'      => __( 'Kartu Jadwal', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Hari', 'param_name' => 'day_text', 'admin_label' => true),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => 'Status Pelayanan',
                            'param_name' => 'status',
                            'value'      => array( 'Buka' => 'buka', 'Tutup' => 'tutup' )
                        ),
                        array('type' => 'textfield', 'heading' => 'Jam Layanan / Keterangan Tutup', 'param_name' => 'time_text'),
                        array(
                            'type'       => 'textfield', 
                            'heading'    => 'Jam Istirahat', 
                            'param_name' => 'break_text', 
                            'dependency' => array( 'element' => 'status', 'value' => 'buka' )
                        ),
                    ),
                ),
                array(
                    'type'       => 'textarea',
                    'heading'    => __( 'Catatan Bawah Kartu', 'ppic-custom-element' ),
                    'param_name' => 'card_note',
                    'value'      => 'Layanan informasi tetap dapat diakses secara daring melalui formulir permintaan online.',
                    'group'      => __( 'Kartu Jadwal', 'ppic-custom-element' ),
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