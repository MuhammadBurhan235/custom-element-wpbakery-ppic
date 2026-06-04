<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ppic_dosen_directory_split_values( $value ) {
    $value = trim( (string) $value );

    if ( '' === $value ) {
        return array();
    }

    $parts = preg_split( '/\r\n|\r|\n|,/', $value );
    $results = array();

    foreach ( $parts as $part ) {
        $part = trim( wp_strip_all_tags( $part ) );

        if ( '' !== $part ) {
            $results[] = $part;
        }
    }

    return array_values( array_unique( $results ) );
}

function ppic_dosen_directory_prepare_email_href( $value ) {
    $value = trim( (string) $value );

    if ( '' === $value ) {
        return '';
    }

    if ( 0 === stripos( $value, 'mailto:' ) ) {
        return sanitize_email( substr( $value, 7 ) ) ? $value : '';
    }

    return sanitize_email( $value ) ? 'mailto:' . sanitize_email( $value ) : '';
}

add_shortcode( 'ppic_dosen_directory', 'ppic_dosen_directory_render' );
function ppic_dosen_directory_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'search_placeholder' => 'Cari nama / keahlian...',
            'sort_label' => 'Urutkan:',
            'filter_prodi_label' => 'Program Studi',
            'filter_jabatan_label' => 'Jabatan Fungsional',
            'filter_sertifikasi_label' => 'Sertifikasi Pendidik',
            'no_results_text' => 'Tidak ada dosen yang cocok dengan filter saat ini.',
            'lecturers' => urlencode(
                wp_json_encode(
                    array(
                        array(
                            'name' => 'Ahmad Kosasih, S.T., M.T',
                            'jabatan' => 'Lektor',
                            'prodi' => 'Teknik Listrik Bandara',
                            'bio' => 'Dosen Teknik Listrik Bandara dengan pengalaman mengajar lebih dari 21 tahun. Berpengalaman dalam sistem tenaga bandara. Aktif dalam penelitian terapan dan pembinaan mahasiswa vokasi.',
                            'lama_mengajar' => '21 tahun (sejak 2009)',
                            'pendidikan' => "S2 Teknik Penerbangan\nS1 Teknik Listrik Bandara\nSertifikasi Pendidik",
                            'kepakaran' => "Sistem tenaga bandara\nInstruktur ahli\nPublikasi ilmiah\nBersertifikat pendidik",
                            'scholar_url' => 'https://scholar.google.com/citations?user=ahmadkosasih13',
                            'sinta_id' => '300000',
                            'linkedin_url' => 'https://linkedin.com/in/ahmadkosasih13',
                            'email' => 'ahmad.kosasih@ppicurug.ac.id',
                            'sertifikasi' => 'Ya',
                        ),
                        array(
                            'name' => 'Zulina Kurniawati, S.SiT, M.Si',
                            'jabatan' => 'Lektor',
                            'prodi' => 'Teknik Mekanikal Bandar Udara',
                            'bio' => 'Dosen Teknik Mekanikal Bandar Udara dengan pengalaman mengajar lebih dari 18 tahun. Berpengalaman dalam HVAC. Aktif dalam penelitian terapan dan pembinaan mahasiswa vokasi.',
                            'lama_mengajar' => '18 tahun (sejak 2018)',
                            'pendidikan' => "S2 Teknik Penerbangan\nS1 Teknik Mekanikal Bandar Udara\nSertifikasi Pendidik",
                            'kepakaran' => "HVAC\nInstruktur ahli\nPublikasi ilmiah\nBersertifikat pendidik",
                            'scholar_url' => 'https://scholar.google.com/citations?user=zulinakurniawati10',
                            'sinta_id' => '309179',
                            'linkedin_url' => 'https://linkedin.com/in/zulinakurniawati10',
                            'email' => 'zulina.kurniawati@ppicurug.ac.id',
                            'sertifikasi' => 'Ya',
                        ),
                    )
                )
            ),
            'el_id' => '',
            'el_class' => '',
        ),
        $atts
    );

    $lecturers = vc_param_group_parse_atts( $atts['lecturers'] );

    if ( empty( $lecturers ) || ! is_array( $lecturers ) ) {
        return '';
    }

    $items = array();
    $prodi_options = array();
    $jabatan_options = array();
    $sertifikasi_options = array();

    foreach ( $lecturers as $lecturer ) {
        $name = isset( $lecturer['name'] ) ? trim( $lecturer['name'] ) : '';

        if ( '' === $name ) {
            continue;
        }

        $jabatan = isset( $lecturer['jabatan'] ) ? trim( $lecturer['jabatan'] ) : '';
        $prodi = isset( $lecturer['prodi'] ) ? trim( $lecturer['prodi'] ) : '';
        $bio = isset( $lecturer['bio'] ) ? trim( $lecturer['bio'] ) : '';
        $lama_mengajar = isset( $lecturer['lama_mengajar'] ) ? trim( $lecturer['lama_mengajar'] ) : '';
        $pendidikan = ppic_dosen_directory_split_values( isset( $lecturer['pendidikan'] ) ? $lecturer['pendidikan'] : '' );
        $kepakaran = ppic_dosen_directory_split_values( isset( $lecturer['kepakaran'] ) ? $lecturer['kepakaran'] : '' );
        $scholar_url = isset( $lecturer['scholar_url'] ) ? esc_url( trim( $lecturer['scholar_url'] ) ) : '';
        $sinta_id = isset( $lecturer['sinta_id'] ) ? trim( $lecturer['sinta_id'] ) : '';
        $linkedin_url = isset( $lecturer['linkedin_url'] ) ? esc_url( trim( $lecturer['linkedin_url'] ) ) : '';
        $email = isset( $lecturer['email'] ) ? trim( $lecturer['email'] ) : '';
        $email_href = ppic_dosen_directory_prepare_email_href( $email );
        $sertifikasi = isset( $lecturer['sertifikasi'] ) && 'Tidak' === trim( $lecturer['sertifikasi'] ) ? 'Tidak' : 'Ya';
        $photo_id = ! empty( $lecturer['photo'] ) ? absint( $lecturer['photo'] ) : 0;
        $photo_url = '';

        if ( $photo_id ) {
            $photo_data = wp_get_attachment_image_src( $photo_id, 'medium' );

            if ( ! empty( $photo_data[0] ) ) {
                $photo_url = $photo_data[0];
            }
        }

        if ( '' !== $prodi ) {
            $prodi_options[] = $prodi;
        }

        if ( '' !== $jabatan ) {
            $jabatan_options[] = $jabatan;
        }

        $sertifikasi_options[] = $sertifikasi;

        $items[] = array(
            'name' => $name,
            'jabatan' => $jabatan,
            'prodi' => $prodi,
            'bio' => $bio,
            'lama_mengajar' => $lama_mengajar,
            'pendidikan' => $pendidikan,
            'kepakaran' => $kepakaran,
            'scholar_url' => $scholar_url,
            'sinta_id' => $sinta_id,
            'linkedin_url' => $linkedin_url,
            'email' => $email,
            'email_href' => $email_href,
            'sertifikasi' => $sertifikasi,
            'photo_url' => $photo_url,
            'search_blob' => strtolower( implode( ' ', array_merge( array( $name, $jabatan, $prodi, $bio ), $pendidikan, $kepakaran ) ) ),
        );
    }

    if ( empty( $items ) ) {
        return '';
    }

    natcasesort( $prodi_options );
    natcasesort( $jabatan_options );
    $prodi_options = array_values( array_unique( $prodi_options ) );
    $jabatan_options = array_values( array_unique( $jabatan_options ) );
    $sertifikasi_options = array_values( array_unique( $sertifikasi_options ) );
    sort( $sertifikasi_options );

    $directory_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'ppic-dosen-directory-' ) : uniqid( 'ppic-dosen-directory-' );
    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-dosen-directory-section' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start();
    ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>" data-dosen-directory id="<?php echo esc_attr( $directory_id ); ?>">
        <div class="ppic-dosen-directory__container">
            <aside class="ppic-dosen-directory__sidebar">
                <div class="ppic-dosen-directory__search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="text" class="ppic-dosen-directory__search-input" placeholder="<?php echo esc_attr( $atts['search_placeholder'] ); ?>">
                </div>

                <div class="ppic-dosen-filter-group is-open">
                    <button type="button" class="ppic-dosen-filter-toggle">
                        <span><?php echo esc_html( $atts['filter_prodi_label'] ); ?></span>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="ppic-dosen-filter-options">
                        <?php foreach ( $prodi_options as $option ) : ?>
                            <label>
                                <input type="checkbox" class="ppic-dosen-filter-checkbox" data-filter="prodi" value="<?php echo esc_attr( $option ); ?>">
                                <span><?php echo esc_html( $option ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="ppic-dosen-filter-group is-open">
                    <button type="button" class="ppic-dosen-filter-toggle">
                        <span><?php echo esc_html( $atts['filter_jabatan_label'] ); ?></span>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="ppic-dosen-filter-options">
                        <?php foreach ( $jabatan_options as $option ) : ?>
                            <label>
                                <input type="checkbox" class="ppic-dosen-filter-checkbox" data-filter="jabatan" value="<?php echo esc_attr( $option ); ?>">
                                <span><?php echo esc_html( $option ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="ppic-dosen-filter-group is-open">
                    <button type="button" class="ppic-dosen-filter-toggle">
                        <span><?php echo esc_html( $atts['filter_sertifikasi_label'] ); ?></span>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="ppic-dosen-filter-options">
                        <?php foreach ( $sertifikasi_options as $option ) : ?>
                            <label>
                                <input type="checkbox" class="ppic-dosen-filter-checkbox" data-filter="sertifikasi" value="<?php echo esc_attr( $option ); ?>">
                                <span><?php echo 'Ya' === $option ? 'Sudah Sertifikasi' : 'Belum Sertifikasi'; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <div class="ppic-dosen-directory__results">
                <div class="ppic-dosen-directory__results-header">
                    <div class="ppic-dosen-directory__count">Menampilkan <?php echo esc_html( count( $items ) ); ?> dari <?php echo esc_html( count( $items ) ); ?> dosen &amp; instruktur</div>
                    <label class="ppic-dosen-directory__sort-wrap">
                        <span><?php echo esc_html( $atts['sort_label'] ); ?></span>
                        <select class="ppic-dosen-directory__sort">
                            <option value="nameAsc">Nama (A-Z)</option>
                            <option value="nameDesc">Nama (Z-A)</option>
                        </select>
                    </label>
                </div>

                <div class="ppic-dosen-directory__grid">
                    <?php foreach ( $items as $item ) : ?>
                        <article
                            class="ppic-dosen-card"
                            data-name="<?php echo esc_attr( strtolower( $item['name'] ) ); ?>"
                            data-jabatan="<?php echo esc_attr( strtolower( $item['jabatan'] ) ); ?>"
                            data-prodi="<?php echo esc_attr( strtolower( $item['prodi'] ) ); ?>"
                            data-sertifikasi="<?php echo esc_attr( strtolower( $item['sertifikasi'] ) ); ?>"
                            data-search="<?php echo esc_attr( $item['search_blob'] ); ?>"
                        >
                            <div class="ppic-dosen-card__header">
                                <div class="ppic-dosen-card__avatar">
                                    <?php if ( ! empty( $item['photo_url'] ) ) : ?>
                                        <img src="<?php echo esc_url( $item['photo_url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                                    <?php else : ?>
                                        <i class="fas fa-user-tie" aria-hidden="true"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="ppic-dosen-card__headcopy">
                                    <h3 class="ppic-dosen-card__name"><?php echo esc_html( $item['name'] ); ?></h3>
                                    <div class="ppic-dosen-card__badges">
                                        <?php if ( ! empty( $item['jabatan'] ) ) : ?>
                                            <span class="ppic-dosen-card__badge is-title"><?php echo esc_html( $item['jabatan'] ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $item['prodi'] ) ) : ?>
                                            <span class="ppic-dosen-card__badge is-dept"><i class="fas fa-building" aria-hidden="true"></i><?php echo esc_html( $item['prodi'] ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ( ! empty( $item['bio'] ) ) : ?>
                                <p class="ppic-dosen-card__bio"><?php echo esc_html( $item['bio'] ); ?></p>
                            <?php endif; ?>

                            <div class="ppic-dosen-card__details">
                                <?php if ( ! empty( $item['lama_mengajar'] ) ) : ?>
                                    <div class="ppic-dosen-card__detail">
                                        <span class="ppic-dosen-card__label"><i class="fas fa-chalkboard" aria-hidden="true"></i>Lama Mengajar</span>
                                        <span class="ppic-dosen-card__value"><?php echo esc_html( $item['lama_mengajar'] ); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $item['pendidikan'] ) ) : ?>
                                    <div class="ppic-dosen-card__detail">
                                        <span class="ppic-dosen-card__label"><i class="fas fa-graduation-cap" aria-hidden="true"></i>Pendidikan</span>
                                        <div class="ppic-dosen-card__tags is-education">
                                            <?php foreach ( $item['pendidikan'] as $tag ) : ?>
                                                <span class="ppic-dosen-card__tag is-education"><?php echo esc_html( $tag ); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ( ! empty( $item['kepakaran'] ) ) : ?>
                                <div class="ppic-dosen-card__expertise">
                                    <span class="ppic-dosen-card__label"><i class="fas fa-microchip" aria-hidden="true"></i>Kepakaran</span>
                                    <div class="ppic-dosen-card__tags">
                                        <?php foreach ( $item['kepakaran'] as $tag ) : ?>
                                            <span class="ppic-dosen-card__tag"><?php echo esc_html( $tag ); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="ppic-dosen-card__footer">
                                <div class="ppic-dosen-card__footer-primary">
                                    <div class="ppic-dosen-card__academic-links">
                                        <?php if ( ! empty( $item['scholar_url'] ) ) : ?>
                                            <a href="<?php echo esc_url( $item['scholar_url'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-graduation-cap" aria-hidden="true"></i>Google Scholar</a>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $item['sinta_id'] ) ) : ?>
                                            <span><i class="fas fa-scroll" aria-hidden="true"></i>SINTA: <?php echo esc_html( $item['sinta_id'] ); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="ppic-dosen-card__certification<?php echo 'Ya' === $item['sertifikasi'] ? '' : ' is-pending'; ?>">
                                        <i class="fas fa-certificate" aria-hidden="true"></i>
                                        <span><?php echo 'Ya' === $item['sertifikasi'] ? 'Bersertifikasi Pendidik' : 'Belum Sertifikasi Pendidik'; ?></span>
                                    </div>
                                </div>

                                <div class="ppic-dosen-card__contact-links">
                                    <?php if ( ! empty( $item['linkedin_url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $item['linkedin_url'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin" aria-hidden="true"></i>LinkedIn</a>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $item['email_href'] ) ) : ?>
                                        <a href="<?php echo esc_url( $item['email_href'] ); ?>"><i class="fas fa-envelope" aria-hidden="true"></i>Email</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="ppic-dosen-directory__empty" hidden><?php echo esc_html( $atts['no_results_text'] ); ?></div>
            </div>
        </div>
    </section>
    <?php

    static $script_printed = false;

    if ( ! $script_printed ) {
        $script_printed = true;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var directories = document.querySelectorAll('[data-dosen-directory]');

                directories.forEach(function (directory) {
                    var searchInput = directory.querySelector('.ppic-dosen-directory__search-input');
                    var checkboxes = directory.querySelectorAll('.ppic-dosen-filter-checkbox');
                    var sortSelect = directory.querySelector('.ppic-dosen-directory__sort');
                    var resultCount = directory.querySelector('.ppic-dosen-directory__count');
                    var grid = directory.querySelector('.ppic-dosen-directory__grid');
                    var emptyState = directory.querySelector('.ppic-dosen-directory__empty');
                    var cards = Array.prototype.slice.call(directory.querySelectorAll('.ppic-dosen-card'));
                    var totalItems = cards.length;

                    var getSelectedMap = function () {
                        var selected = {
                            prodi: [],
                            jabatan: [],
                            sertifikasi: []
                        };

                        checkboxes.forEach(function (checkbox) {
                            if (checkbox.checked && selected[checkbox.dataset.filter]) {
                                selected[checkbox.dataset.filter].push((checkbox.value || '').toLowerCase());
                            }
                        });

                        return selected;
                    };

                    var matchesFilter = function (card, values, key) {
                        if (!values.length) {
                            return true;
                        }

                        return values.indexOf((card.dataset[key] || '').toLowerCase()) !== -1;
                    };

                    var updateDirectory = function () {
                        var selected = getSelectedMap();
                        var query = ((searchInput && searchInput.value) || '').trim().toLowerCase();
                        var visibleCards = [];

                        cards.forEach(function (card) {
                            var haystack = (card.dataset.search || '').toLowerCase();
                            var isVisible = matchesFilter(card, selected.prodi, 'prodi')
                                && matchesFilter(card, selected.jabatan, 'jabatan')
                                && matchesFilter(card, selected.sertifikasi, 'sertifikasi')
                                && (!query || haystack.indexOf(query) !== -1);

                            card.hidden = !isVisible;

                            if (isVisible) {
                                visibleCards.push(card);
                            }
                        });

                        visibleCards.sort(function (leftCard, rightCard) {
                            var leftName = leftCard.dataset.name || '';
                            var rightName = rightCard.dataset.name || '';
                            var direction = sortSelect && sortSelect.value === 'nameDesc' ? -1 : 1;
                            return leftName.localeCompare(rightName) * direction;
                        });

                        visibleCards.forEach(function (card) {
                            grid.appendChild(card);
                        });

                        if (resultCount) {
                            resultCount.textContent = 'Menampilkan ' + visibleCards.length + ' dari ' + totalItems + ' dosen & instruktur';
                        }

                        if (emptyState) {
                            emptyState.hidden = visibleCards.length > 0;
                        }
                    };

                    if (searchInput) {
                        searchInput.addEventListener('input', updateDirectory);
                    }

                    checkboxes.forEach(function (checkbox) {
                        checkbox.addEventListener('change', updateDirectory);
                    });

                    if (sortSelect) {
                        sortSelect.addEventListener('change', updateDirectory);
                    }

                    directory.querySelectorAll('.ppic-dosen-filter-toggle').forEach(function (toggleButton) {
                        toggleButton.addEventListener('click', function () {
                            this.parentElement.classList.toggle('is-open');
                        });
                    });

                    updateDirectory();
                });
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_dosen_directory_map' );
function ppic_dosen_directory_map() {
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    vc_map(
        array(
            'name' => __( 'PPIC Dosen Directory', 'ppic-custom-element' ),
            'base' => 'ppic_dosen_directory',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon' => 'dashicons dashicons-groups',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Placeholder Pencarian', 'ppic-custom-element' ),
                    'param_name' => 'search_placeholder',
                    'value' => 'Cari nama / keahlian...',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Sort', 'ppic-custom-element' ),
                    'param_name' => 'sort_label',
                    'value' => 'Urutkan:',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Filter Program Studi', 'ppic-custom-element' ),
                    'param_name' => 'filter_prodi_label',
                    'value' => 'Program Studi',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Filter Jabatan', 'ppic-custom-element' ),
                    'param_name' => 'filter_jabatan_label',
                    'value' => 'Jabatan Fungsional',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Label Filter Sertifikasi', 'ppic-custom-element' ),
                    'param_name' => 'filter_sertifikasi_label',
                    'value' => 'Sertifikasi Pendidik',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Teks Empty State', 'ppic-custom-element' ),
                    'param_name' => 'no_results_text',
                    'value' => 'Tidak ada dosen yang cocok dengan filter saat ini.',
                ),
                array(
                    'type' => 'param_group',
                    'heading' => __( 'Daftar Dosen', 'ppic-custom-element' ),
                    'param_name' => 'lecturers',
                    'params' => array(
                        array(
                            'type' => 'attach_image',
                            'heading' => __( 'Foto Dosen', 'ppic-custom-element' ),
                            'param_name' => 'photo',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Nama Lengkap', 'ppic-custom-element' ),
                            'param_name' => 'name',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Jabatan Fungsional', 'ppic-custom-element' ),
                            'param_name' => 'jabatan',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Program Studi', 'ppic-custom-element' ),
                            'param_name' => 'prodi',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Bio Singkat', 'ppic-custom-element' ),
                            'param_name' => 'bio',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Lama Mengajar', 'ppic-custom-element' ),
                            'param_name' => 'lama_mengajar',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Pendidikan', 'ppic-custom-element' ),
                            'param_name' => 'pendidikan',
                            'description' => __( 'Pisahkan per baris atau koma.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Kepakaran', 'ppic-custom-element' ),
                            'param_name' => 'kepakaran',
                            'description' => __( 'Pisahkan per baris atau koma.', 'ppic-custom-element' ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Google Scholar URL', 'ppic-custom-element' ),
                            'param_name' => 'scholar_url',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'SINTA ID', 'ppic-custom-element' ),
                            'param_name' => 'sinta_id',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'LinkedIn URL', 'ppic-custom-element' ),
                            'param_name' => 'linkedin_url',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Email', 'ppic-custom-element' ),
                            'param_name' => 'email',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Sertifikasi Pendidik', 'ppic-custom-element' ),
                            'param_name' => 'sertifikasi',
                            'value' => array(
                                __( 'Ya', 'ppic-custom-element' ) => 'Ya',
                                __( 'Tidak', 'ppic-custom-element' ) => 'Tidak',
                            ),
                            'std' => 'Ya',
                        ),
                    ),
                ),
                array(
                    'type' => 'el_id',
                    'heading' => __( 'Element ID', 'js_composer' ),
                    'param_name' => 'el_id',
                    'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to w3c specification).', 'js_composer' ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'el_class',
                    'description' => __( 'Style particular content element differently by adding a class name and referring to it in custom CSS.', 'js_composer' ),
                ),
            ),
        )
    );
}