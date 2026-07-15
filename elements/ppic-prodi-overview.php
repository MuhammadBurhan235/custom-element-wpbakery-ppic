<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_prodi_overview', 'ppic_prodi_overview_render' );
function ppic_prodi_overview_render( $atts, $content = null ) {
    $atts = shortcode_atts(
        array(
            'section_id'       => 'overview',
            'title'            => 'Overview',
            'title_icon'       => 'fas fa-home',
            'subtitle'         => 'Program Studi Teknik Mekanikal Bandar Udara (TMB) adalah program vokasi diploma tiga yang berfokus pada kompetensi mekanikal bandar udara sesuai regulasi nasional dan standar industri.',
            'akreditasi_text'  => 'Status Akreditasi: <strong>UNGGUL</strong> — LAM-TEKNIK',
            'sidebar_title'    => 'Sekilas TMB',
            'sidebar_items'    => '',
            'visi_title'       => 'Visi',
            'visi_text'        => 'Menjadi program studi yang mampu mencetak lulusan siap kerja di bidang Mekanikal Bandar Udara secara profesional serta unggul dan mampu bersaing di tingkat nasional dan internasional.',
            'misi_title'       => 'Misi',
            'misi_items'       => '',
            'el_class'         => '',
        ),
        $atts
    );

    $wrapper_id = ! empty( $atts['section_id'] ) ? ' id="' . esc_attr( trim( $atts['section_id'] ) ) . '"' : '';
    $wrapper_class = 'ppic-prodi-overview-section section-block' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    // Parse Sidebar Items
    $sidebar = vc_param_group_parse_atts( $atts['sidebar_items'] );
    if ( ! is_array( $sidebar ) ) $sidebar = array();

    // Parse Misi Items
    $misi = vc_param_group_parse_atts( $atts['misi_items'] );
    if ( ! is_array( $misi ) ) $misi = array();

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="ppic-overview-container">
            
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

            <div class="ppic-overview-grid">
                <!-- Kolom Kiri: Teks Utama & Badge Akreditasi -->
                <div class="ppic-overview-text">
                    <div class="ppic-rich-text">
                        <?php echo wpb_js_remove_wpautop( $content, true ); ?>
                    </div>

                    <?php if ( ! empty( $atts['akreditasi_text'] ) ) : ?>
                        <div class="ppic-akreditasi-badge">
                            <i class="fas fa-check-circle"></i> 
                            <span><?php echo wp_kses_post( $atts['akreditasi_text'] ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Kolom Kanan: Sidebar Sekilas Prodi -->
                <div class="ppic-overview-side">
                    <h4>
                        <i class="fas fa-info-circle"></i> 
                        <?php echo esc_html( $atts['sidebar_title'] ); ?>
                    </h4>
                    
                    <?php if ( ! empty( $sidebar ) ) : ?>
                        <ul>
                            <?php foreach ( $sidebar as $item ) : 
                                $icon  = isset( $item['icon'] ) ? trim( $item['icon'] ) : 'fas fa-check';
                                $label = isset( $item['label'] ) ? trim( $item['label'] ) : '';
                                $value = isset( $item['value'] ) ? trim( $item['value'] ) : '';
                                if ( empty( $label ) ) continue;
                            ?>
                                <li>
                                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                                    <div>
                                        <strong><?php echo esc_html( $label ); ?></strong><br />
                                        <?php echo esc_html( $value ); ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Visi & Misi Grid -->
            <div class="ppic-visi-misi-grid">
                <div class="ppic-vm-card visi-card">
                    <h4><i class="fas fa-eye"></i> <?php echo esc_html( $atts['visi_title'] ); ?></h4>
                    <p><?php echo wp_kses_post( $atts['visi_text'] ); ?></p>
                </div>
                
                <div class="ppic-vm-card misi-card">
                    <h4><i class="fas fa-bullseye"></i> <?php echo esc_html( $atts['misi_title'] ); ?></h4>
                    <?php if ( ! empty( $misi ) ) : ?>
                        <ul>
                            <?php foreach ( $misi as $item ) : 
                                $text = isset( $item['text'] ) ? trim( $item['text'] ) : '';
                                if ( empty( $text ) ) continue;
                            ?>
                                <li>
                                    <i class="fas fa-check"></i> 
                                    <span><?php echo wp_kses_post( $text ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_prodi_overview_element' );
function ppic_register_prodi_overview_element() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Default Content Utama
    $default_content = '<p><strong>Program Studi Teknik Mekanikal Bandar Udara</strong> merupakan salah satu program studi vokasi yang berada di Politeknik Penerbangan Indonesia Curug. Kekhususan program studi ini adalah untuk menghasilkan lulusan yang memiliki kompetensi bidang mekanikal bandar udara sesuai dengan <strong>Peraturan Menteri Perhubungan Nomor 37 Tahun 2021</strong>, meliputi fasilitas:</p>
<ul>
<li>Traction Equipment;</li>
<li>Air Conditioning System;</li>
<li>Water and Pump System;</li>
<li>Alat-Alat Besar dan Kendaraan Pertolongan Kecelakaan Penerbangan dan Pemadam Kebakaran (PKP-PK).</li>
</ul>
<p>Untuk mewujudkan lulusan yang diharapkan, program studi menyusun kurikulum dengan mempertimbangkan regulasi dari <strong>Kementerian Pendidikan Tinggi, Sains, dan Teknologi</strong> serta <strong>Kementerian Perhubungan</strong>, masukan dari <em>stakeholder</em> (industri penerbangan/bandara), asosiasi profesi, ikatan alumni, dan program studi sejenis.</p>
<p>Lulusan program studi akan mendapatkan <strong>ijazah</strong> yang diakui oleh Kementerian Pendidikan Tinggi, Sains, dan Teknologi serta <strong>sertifikat kompetensi</strong> yang diakui oleh Kementerian Perhubungan (yang menjadi persyaratan personel bandar udara).</p>';

    // Default Data Sidebar
    $dummy_sidebar = array(
        array( 'icon' => 'fas fa-layer-group', 'label' => 'Jenjang', 'value' => 'Diploma III (DIII)' ),
        array( 'icon' => 'fas fa-clock', 'label' => 'Masa Studi', 'value' => '6 Semester (3 Tahun)' ),
        array( 'icon' => 'fas fa-book', 'label' => 'Total SKS', 'value' => '109 SKS' ),
        array( 'icon' => 'fas fa-flask', 'label' => 'Metode', 'value' => 'Praktikum lebih dominan daripada teori' ),
        array( 'icon' => 'fas fa-building', 'label' => 'Regulasi', 'value' => 'Permenhub No. 37 Tahun 2021' ),
        array( 'icon' => 'fas fa-award', 'label' => 'Akreditasi', 'value' => 'UNGGUL (LAM-TEKNIK)' ),
    );

    // Default Data Misi
    $dummy_misi = array(
        array( 'text' => 'Menyelenggarakan pendidikan vokasi khususnya diploma Teknik Mekanikal Bandar Udara yang mampu berkompetisi secara mantap dalam hal pelayanan jasa di dunia penerbangan;' ),
        array( 'text' => 'Mengembangkan ilmu pengetahuan dan teknologi di bidang Teknik Mekanikal Bandar Udara melalui penelitian yang inovatif;' ),
        array( 'text' => 'Menyelenggarakan pengabdian kepada masyarakat sesuai dengan keilmuan Teknik Mekanikal Bandar Udara dalam rangka memajukan kesejahteraan masyarakat dan mencerdaskan kehidupan bangsa;' ),
        array( 'text' => 'Menjalin kerjasama sesuai dengan keilmuan Teknik Mekanikal Bandar Udara yang produktif dan berkelanjutan dengan Lembaga Pendidikan, pemerintah, dunia usaha dan dunia industri di tingkat nasional dan internasional.' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC Prodi Overview', 'ppic-custom-element' ),
            'base'     => 'ppic_prodi_overview',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-text-page',
            'params'   => array(
                // HEADER
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section ID (Untuk Target Sticky Nav)', 'ppic-custom-element' ),
                    'param_name'  => 'section_id',
                    'value'       => 'overview',
                    'description' => __( 'Pastikan ID ini sama dengan Target ID di elemen Sticky Nav.', 'ppic-custom-element' ),
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Section', 'ppic-custom-element' ),
                    'param_name'  => 'title',
                    'value'       => 'Overview',
                    'admin_label' => true,
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Ikon Judul (FontAwesome)', 'ppic-custom-element' ),
                    'param_name'  => 'title_icon',
                    'value'       => 'fas fa-home',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Subtitle Pendek', 'ppic-custom-element' ),
                    'param_name'  => 'subtitle',
                    'value'       => 'Program Studi Teknik Mekanikal Bandar Udara (TMB) adalah program vokasi diploma tiga yang berfokus pada kompetensi mekanikal bandar udara sesuai regulasi nasional dan standar industri.',
                    'group'       => __( 'Header', 'ppic-custom-element' ),
                ),
                // KONTEN UTAMA
                array(
                    'type'        => 'textarea_html',
                    'heading'     => __( 'Konten Teks Utama', 'ppic-custom-element' ),
                    'param_name'  => 'content',
                    'value'       => $default_content,
                    'group'       => __( 'Konten Utama', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Teks Badge Akreditasi (Bawah Teks)', 'ppic-custom-element' ),
                    'param_name'  => 'akreditasi_text',
                    'value'       => 'Status Akreditasi: <strong>UNGGUL</strong> — LAM-TEKNIK',
                    'description' => __( 'Gunakan tag &lt;strong&gt; untuk menebalkan teks.', 'ppic-custom-element' ),
                    'group'       => __( 'Konten Utama', 'ppic-custom-element' ),
                ),
                // SIDEBAR
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Sidebar', 'ppic-custom-element' ),
                    'param_name'  => 'sidebar_title',
                    'value'       => 'Sekilas TMB',
                    'group'       => __( 'Sidebar Kanan', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Info Sidebar', 'ppic-custom-element' ),
                    'param_name'  => 'sidebar_items',
                    'value'       => urlencode( wp_json_encode( $dummy_sidebar ) ),
                    'group'       => __( 'Sidebar Kanan', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textfield', 'heading' => 'Ikon FontAwesome', 'param_name' => 'icon'),
                        array('type' => 'textfield', 'heading' => 'Label (Atas)', 'param_name' => 'label', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'Nilai (Bawah)', 'param_name' => 'value'),
                    ),
                ),
                // VISI MISI
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Kartu Visi', 'ppic-custom-element' ),
                    'param_name'  => 'visi_title',
                    'value'       => 'Visi',
                    'group'       => __( 'Visi & Misi', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textarea',
                    'heading'     => __( 'Teks Visi', 'ppic-custom-element' ),
                    'param_name'  => 'visi_text',
                    'value'       => 'Menjadi program studi yang mampu mencetak lulusan siap kerja di bidang Mekanikal Bandar Udara secara profesional serta unggul dan mampu bersaing di tingkat nasional dan internasional.',
                    'group'       => __( 'Visi & Misi', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Judul Kartu Misi', 'ppic-custom-element' ),
                    'param_name'  => 'misi_title',
                    'value'       => 'Misi',
                    'group'       => __( 'Visi & Misi', 'ppic-custom-element' ),
                ),
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Daftar Poin Misi', 'ppic-custom-element' ),
                    'param_name'  => 'misi_items',
                    'value'       => urlencode( wp_json_encode( $dummy_misi ) ),
                    'group'       => __( 'Visi & Misi', 'ppic-custom-element' ),
                    'params'      => array(
                        array('type' => 'textarea', 'heading' => 'Teks Poin Misi', 'param_name' => 'text'),
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