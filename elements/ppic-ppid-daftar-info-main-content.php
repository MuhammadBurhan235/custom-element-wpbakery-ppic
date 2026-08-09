<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ppic_ppid_daftar_info_main', 'ppic_ppid_daftar_info_main_render' );
function ppic_ppid_daftar_info_main_render( $atts ) {
    $atts = shortcode_atts(
        array(
            'berkala_items'      => '',
            'setiapsaat_items'   => '',
            'btn_setiapsaat'     => 'Akses Informasi Setiap Saat',
            'url_setiapsaat'     => 'url:https%3A%2F%2Fdocs.google.com%2Fforms|target:_blank',
            'sertamerta_items'   => '',
            'dikecualikan_items' => '',
            'el_id'              => 'daftar-informasi',
            'el_class'           => '',
        ),
        $atts
    );

    // Parsing Param Groups (Amankan dengan pengecekan is_array)
    $berkala      = vc_param_group_parse_atts( $atts['berkala_items'] );
    $setiapsaat   = vc_param_group_parse_atts( $atts['setiapsaat_items'] );
    $sertamerta   = vc_param_group_parse_atts( $atts['sertamerta_items'] );
    $dikecualikan = vc_param_group_parse_atts( $atts['dikecualikan_items'] );

    if ( ! is_array( $berkala ) ) $berkala = array();
    if ( ! is_array( $setiapsaat ) ) $setiapsaat = array();
    if ( ! is_array( $sertamerta ) ) $sertamerta = array();
    if ( ! is_array( $dikecualikan ) ) $dikecualikan = array();

    // Parse URL Tombol Setiap Saat
    $link_ss = ( '||' !== $atts['url_setiapsaat'] ) ? vc_build_link( $atts['url_setiapsaat'] ) : '';
    $a_href_ss   = ! empty( $link_ss['url'] ) ? $link_ss['url'] : '#';
    $a_target_ss = ! empty( $link_ss['target'] ) ? ' target="' . esc_attr( trim( $link_ss['target'] ) ) . '"' : '';

    $wrapper_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
    $wrapper_class = 'ppic-daf-section ' . ( ! empty( $atts['el_class'] ) ? ' ' . esc_attr( trim( $atts['el_class'] ) ) : '' );

    ob_start(); ?>
    <section<?php echo $wrapper_id; ?> class="<?php echo $wrapper_class; ?>">
        <div class="container ppic-daf-container">
            
            <!-- Tambahkan ID untuk hash link: #berkala -->
            <div class="daf-accordion-item" id="berkala">
                <div class="daf-accordion-header" onclick="toggleDafAccordion(this)">
                    <h3><i class="fas fa-calendar-alt"></i> Informasi Berkala</h3>
                    <i class="fas fa-chevron-down arrow-icon"></i>
                </div>
                <div class="daf-accordion-content" style="display:none;">
                    <p class="daf-desc">Informasi yang wajib diumumkan secara rutin minimal 6 bulan sekali.</p>
                    
                    <div class="berkala-table-wrapper">
                        <div class="filter-sidebar" id="filterSidebar">
                            <button class="filter-btn active" data-cat="laporan"><i class="fas fa-file-alt"></i> Laporan &amp; Akuntabilitas</button>
                            <button class="filter-btn" data-cat="survei"><i class="fas fa-poll"></i> Survei Kepuasan</button>
                            <button class="filter-btn" data-cat="lhkpn"><i class="fas fa-file-invoice-dollar"></i> LHKPN Pimpinan</button>
                            <button class="filter-btn" data-cat="sarpras"><i class="fas fa-building"></i> Sarpras &amp; Aset</button>
                            <button class="filter-btn" data-cat="sdm"><i class="fas fa-users"></i> Informasi SDM</button>
                            <button class="filter-btn" data-cat="anggaran"><i class="fas fa-chart-bar"></i> Perencanaan &amp; Penganggaran</button>
                            <button class="filter-btn" data-cat="jdih"><i class="fas fa-book"></i> Kumpulan JDIH</button>
                        </div>
                        
                        <div id="dynamicTable_wrapper" class="dataTables_wrapper no-footer">
                            <div class="datatables-top-bar">
                                <div class="dataTables_length" id="dynamicTable_length">
                                    <label>Tampilkan 
                                        <select id="daf-limit-select" name="dynamicTable_length" aria-controls="dynamicTable">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> data
                                    </label>
                                </div>
                                <div id="dynamicTable_filter" class="dataTables_filter">
                                    <label>Cari: <input type="search" id="daf-search-input" placeholder="Ketik kata kunci..." aria-controls="dynamicTable"></label>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <!-- Class 'sorting' Dihapus dari kolom No, Preview, dan Unduh -->
                                <table id="dynamicTable" class="daf-info-table display nowrap dataTable no-footer dtr-inline" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">No.</th>
                                            <th class="sorting" id="sort-desc-btn" data-sort="asc" style="cursor:pointer;" title="Klik untuk mengurutkan A-Z">DESKRIPSI</th>
                                            <th style="width: 120px; text-align: center;">PREVIEW</th>
                                            <th style="width: 120px; text-align: center;">UNDUH</th>
                                        </tr>
                                    </thead>
                                    <tbody id="berkala-tbody">
                                        <?php if ( ! empty( $berkala ) ) : ?>
                                            <?php foreach ( $berkala as $item ) : 
                                                $desc = isset( $item['desc'] ) ? $item['desc'] : '';
                                                $file = isset( $item['file_url'] ) ? $item['file_url'] : '#';
                                                $cat  = isset( $item['filter_cat'] ) ? $item['filter_cat'] : 'laporan';
                                                if ( empty( $desc ) ) continue;
                                            ?>
                                            <tr class="berkala-row" data-cat="<?php echo esc_attr( $cat ); ?>" style="display:none;">
                                                <td class="row-number"></td>
                                                <td class="row-desc"><?php echo esc_html( $desc ); ?></td>
                                                <td style="text-align: center;">
                                                    <a class="daf-action-btn preview-btn preview-link" href="<?php echo esc_url( $file ); ?>" target="_blank"><i class="fas fa-eye"></i></a>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a class="daf-action-btn download-btn download-link" href="<?php echo esc_url( $file ); ?>" download><i class="fas fa-download"></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr id="empty-row-msg"><td colspan="4" style="text-align:center;">Data belum tersedia. Silakan isi di pengaturan elemen.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="datatables-bottom-bar">
                                <div class="dataTables_info" id="dynamicTable_info" role="status" aria-live="polite">
                                    Menampilkan 0 - 0 dari 0
                                </div>
                                <div class="dataTables_paginate paging_simple_numbers" id="dynamicTable_paginate">
                                    <a class="paginate_button previous disabled" aria-controls="dynamicTable" tabindex="-1">Previous</a>
                                    <span><a class="paginate_button current" aria-controls="dynamicTable" tabindex="0">1</a></span>
                                    <a class="paginate_button next disabled" aria-controls="dynamicTable" tabindex="-1">Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="daf-small-note">* Untuk dokumen selengkapnya, hubungi petugas PPID.</p>
                </div>
            </div>

            <!-- Tambahkan ID untuk hash link: #setiapsaat -->
            <div class="daf-accordion-item" id="setiapsaat">
                <div class="daf-accordion-header" onclick="toggleDafAccordion(this)">
                    <h3><i class="fas fa-clock"></i> Informasi Setiap Saat</h3>
                    <i class="fas fa-chevron-down arrow-icon"></i>
                </div>
                <div class="daf-accordion-content" style="display:none;">
                    <p class="daf-desc">Informasi yang wajib tersedia dan dapat diakses publik setiap saat.</p>
                    <div class="table-responsive">
                        <table class="daf-info-table full-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Daftar Informasi Setiap Saat</th>
                                    <th style="width: 150px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( ! empty( $setiapsaat ) ) : ?>
                                    <?php $no = 1; foreach ( $setiapsaat as $item ) : 
                                        $desc = isset( $item['desc'] ) ? $item['desc'] : '';
                                        if ( empty( $desc ) ) continue;
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo esc_html( $desc ); ?></td>
                                        <td><span class="daf-badge badge-green"><i class="fas fa-check-circle"></i> Tersedia</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="3" style="text-align:center;">Data belum tersedia. Silakan isi di pengaturan elemen.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ( ! empty( $atts['btn_setiapsaat'] ) ) : ?>
                        <div class="daf-cta-wrapper">
                            <a href="<?php echo esc_url( $a_href_ss ); ?>" class="daf-btn-primary"<?php echo $a_target_ss; ?>>
                                <i class="fas fa-external-link-alt"></i> <?php echo esc_html( $atts['btn_setiapsaat'] ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tambahkan ID untuk hash link: #sertamerta -->
            <div class="daf-accordion-item" id="sertamerta">
                <div class="daf-accordion-header" onclick="toggleDafAccordion(this)">
                    <h3><i class="fas fa-bolt"></i> Informasi Serta Merta</h3>
                    <i class="fas fa-chevron-down arrow-icon"></i>
                </div>
                <div class="daf-accordion-content" style="display:none;">
                    <p class="daf-desc">Informasi yang dapat mengancam hajat hidup orang banyak, ketertiban umum, atau keadaan darurat.</p>
                    <div class="table-responsive">
                        <table class="daf-info-table full-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Jenis Informasi Serta Merta</th>
                                    <th style="width: 250px;">Aksi / Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( ! empty( $sertamerta ) ) : ?>
                                    <?php $no = 1; foreach ( $sertamerta as $item ) : 
                                        $desc   = isset( $item['desc'] ) ? $item['desc'] : '';
                                        $status = isset( $item['status_tipe'] ) ? $item['status_tipe'] : 'file';
                                        $file   = isset( $item['file_url'] ) ? $item['file_url'] : '#';
                                        if ( empty( $desc ) ) continue;
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo esc_html( $desc ); ?></td>
                                        <td>
                                            <?php if ( $status === 'file' ) : ?>
                                                <div class="action-icons-group">
                                                    <a href="<?php echo esc_url( $file ); ?>" target="_blank" class="daf-action-btn preview-btn"><i class="fas fa-eye"></i></a>
                                                    <a href="<?php echo esc_url( $file ); ?>" download class="daf-action-btn download-btn"><i class="fas fa-download"></i></a>
                                                </div>
                                            <?php else : ?>
                                                <span class="daf-badge badge-yellow"><i class="fas fa-hourglass-half"></i> Belum tersedia</span>
                                                <div class="daf-table-note">Akan diumumkan melalui kanal resmi jika terjadi perubahan.</div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="3" style="text-align:center;">Data belum tersedia. Silakan isi di pengaturan elemen.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="daf-info-box warning">
                        <i class="fas fa-info-circle"></i> Informasi serta merta akan segera diumumkan melalui kanal resmi PPID dan media sosial PPI Curug apabila terjadi kondisi darurat.
                    </div>
                </div>
            </div>

            <!-- Tambahkan ID untuk hash link: #dikecualikan -->
            <div class="daf-accordion-item" id="dikecualikan">
                <div class="daf-accordion-header" onclick="toggleDafAccordion(this)">
                    <h3><i class="fas fa-lock"></i> Informasi Dikecualikan</h3>
                    <i class="fas fa-chevron-down arrow-icon"></i>
                </div>
                <div class="daf-accordion-content" style="display:none;">
                    <p class="daf-desc">Informasi yang bersifat rahasia dan tidak dapat diakses publik berdasarkan UU KIP Pasal 17.</p>
                    <div class="table-responsive">
                        <table class="daf-info-table full-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Kategori Informasi Dikecualikan</th>
                                    <th style="width: 180px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( ! empty( $dikecualikan ) ) : ?>
                                    <?php $no = 1; foreach ( $dikecualikan as $item ) : 
                                        $desc = isset( $item['desc'] ) ? $item['desc'] : '';
                                        if ( empty( $desc ) ) continue;
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo esc_html( $desc ); ?></td>
                                        <td><span class="daf-badge badge-red"><i class="fas fa-times-circle"></i> Dikecualikan</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="3" style="text-align:center;">Data belum tersedia. Silakan isi di pengaturan elemen.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="daf-info-box highlight">
                        <i class="fas fa-info-circle"></i> Informasi yang dikecualikan dapat diakses setelah jangka waktu pengecualian berakhir atau melalui mekanisme uji konsekuensi.
                    </div>
                </div>
            </div>

        </div>
    </section>

    <?php
    static $ppic_daf_js_printed = false;
    if ( ! $ppic_daf_js_printed ) {
        $ppic_daf_js_printed = true;
        ?>
        <script>
            // Fungsi Buka/Tutup Accordion
            function toggleDafAccordion(element) {
                var item = element.parentElement;
                var icon = element.querySelector('.arrow-icon');
                var content = item.querySelector('.daf-accordion-content');
                
                if (item.classList.contains('active')) {
                    item.classList.remove('active');
                    content.style.display = 'none';
                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                } else {
                    item.classList.add('active');
                    content.style.display = 'block';
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                }
            }

            // Fungsi Untuk Memeriksa dan Membuka Accordion Berdasarkan Hash URL
            function checkHashAndOpenAccordion() {
                var hash = window.location.hash;
                if (hash) {
                    var targetElement = document.querySelector(hash);
                    if (targetElement && targetElement.classList.contains('daf-accordion-item')) {
                        // Buka accordion target
                        if (!targetElement.classList.contains('active')) {
                            var header = targetElement.querySelector('.daf-accordion-header');
                            if (header) {
                                toggleDafAccordion(header);
                            }
                        }
                        
                        // Opsional: Tutup accordion lain jika ingin mode "satu terbuka" (single-open)
                        // document.querySelectorAll('.daf-accordion-item').forEach(function(item) {
                        //     if (item !== targetElement && item.classList.contains('active')) {
                        //         var otherHeader = item.querySelector('.daf-accordion-header');
                        //         if (otherHeader) toggleDafAccordion(otherHeader);
                        //     }
                        // });

                        // Scroll ke elemen dengan sedikit offset agar tidak tertutup header yang sticky
                        setTimeout(function() {
                            var offsetTop = targetElement.getBoundingClientRect().top + window.pageYOffset - 100; 
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }, 100); // Sedikit delay agar DOM punya waktu update
                    }
                }
            }

            // Panggil saat DOM siap
            document.addEventListener('DOMContentLoaded', function() {
                
                checkHashAndOpenAccordion(); // Periksa URL hash

                // [Script filter, search, paginasi lainnya tetap sama...]
                var filterBtns = document.querySelectorAll('#filterSidebar .filter-btn');
                var tbody = document.getElementById('berkala-tbody');
                var searchInput = document.getElementById('daf-search-input');
                var sortBtn = document.getElementById('sort-desc-btn');
                var limitSelect = document.getElementById('daf-limit-select');
                var prevBtn = document.querySelector('#dynamicTable_paginate .previous');
                var nextBtn = document.querySelector('#dynamicTable_paginate .next');
                var pageNumDisplay = document.querySelector('#dynamicTable_paginate .current');
                
                if(!tbody) return;

                var allRows = Array.from(tbody.querySelectorAll('tr.berkala-row'));
                var currentPage = 1;
                
                function refreshTable() {
                    var activeBtn = document.querySelector('#filterSidebar .filter-btn.active');
                    if(!activeBtn) return;

                    var targetCat = activeBtn.getAttribute('data-cat');
                    var searchQuery = searchInput ? searchInput.value.toLowerCase() : '';
                    var sortOrder = sortBtn ? sortBtn.getAttribute('data-sort') : 'asc';
                    var limit = limitSelect ? parseInt(limitSelect.value, 10) : 10;
                    
                    // 1. Sort A-Z / Z-A
                    allRows.sort(function(a, b) {
                        var textA = a.querySelector('.row-desc').innerText.toLowerCase();
                        var textB = b.querySelector('.row-desc').innerText.toLowerCase();
                        return (sortOrder === 'asc') ? textA.localeCompare(textB) : textB.localeCompare(textA);
                    });
                    allRows.forEach(row => tbody.appendChild(row));

                    // 2. Kumpulkan data yang cocok dengan Kategori & Pencarian
                    var matchedRows = [];
                    allRows.forEach(function(row) {
                        var rowCat = row.getAttribute('data-cat');
                        var rowText = row.querySelector('.row-desc').innerText.toLowerCase();
                        
                        if(rowCat === targetCat && rowText.indexOf(searchQuery) > -1) {
                            matchedRows.push(row);
                        } else {
                            row.style.display = 'none'; 
                        }
                    });

                    // 3. Kalkulasi Pagination & Limit Data
                    var totalMatched = matchedRows.length;
                    var totalPages = Math.ceil(totalMatched / limit);
                    
                    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
                    if (currentPage < 1) currentPage = 1;

                    var startIndex = (currentPage - 1) * limit;
                    var endIndex = startIndex + limit;
                    var displayedCount = 0;

                    // 4. Tampilkan HANYA data yang masuk dalam rentang halaman ini
                    matchedRows.forEach(function(row, index) {
                        if (index >= startIndex && index < endIndex) {
                            row.style.display = 'table-row'; 
                            displayedCount++;
                            
                            var numCell = row.querySelector('.row-number');
                            if(numCell) numCell.innerText = (index + 1);
                            
                            row.classList.remove('odd', 'even');
                            row.classList.add((index + 1) % 2 !== 0 ? 'odd' : 'even');
                        } else {
                            row.style.display = 'none'; 
                        }
                    });

                    // 5. Pesan Kosong
                    var emptyRow = document.getElementById('empty-row-msg');
                    if (totalMatched === 0) {
                        if(!emptyRow) {
                            tbody.insertAdjacentHTML('beforeend', '<tr id="empty-row-msg"><td colspan="4" style="text-align:center; padding: 20px;">Dokumen tidak ditemukan.</td></tr>');
                        } else {
                            emptyRow.style.display = 'table-row';
                            emptyRow.querySelector('td').innerText = searchQuery !== '' ? 'Pencarian tidak ditemukan.' : 'Dokumen untuk kategori ini belum tersedia.';
                        }
                    } else {
                        if(emptyRow) emptyRow.style.display = 'none';
                    }

                    // 6. Update UI Info & Tombol Halaman
                    var infoText = document.getElementById('dynamicTable_info');
                    if(infoText) {
                        var startDisplay = totalMatched > 0 ? startIndex + 1 : 0;
                        var endDisplay = startIndex + displayedCount;
                        infoText.innerText = 'Menampilkan ' + startDisplay + ' - ' + endDisplay + ' dari ' + totalMatched;
                    }

                    if(pageNumDisplay) pageNumDisplay.innerText = currentPage;

                    if(prevBtn) {
                        if (currentPage <= 1) prevBtn.classList.add('disabled');
                        else prevBtn.classList.remove('disabled');
                    }
                    if(nextBtn) {
                        if (currentPage >= totalPages) nextBtn.classList.add('disabled');
                        else nextBtn.classList.remove('disabled');
                    }
                }

                if(filterBtns.length > 0) {
                    filterBtns.forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            filterBtns.forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                            if(searchInput) searchInput.value = '';
                            currentPage = 1; 
                            refreshTable();
                        });
                    });
                }

                if(searchInput) {
                    searchInput.addEventListener('input', function() {
                        currentPage = 1; 
                        refreshTable();
                    });
                }

                if(limitSelect) {
                    limitSelect.addEventListener('change', function() {
                        currentPage = 1; 
                        refreshTable();
                    });
                }

                if(sortBtn) {
                    sortBtn.addEventListener('click', function() {
                        var currentOrder = this.getAttribute('data-sort');
                        var newOrder = (currentOrder === 'asc') ? 'desc' : 'asc';
                        this.setAttribute('data-sort', newOrder);
                        
                        if(newOrder === 'asc') {
                            this.classList.replace('sorting_desc', 'sorting_asc');
                        } else {
                            this.classList.replace('sorting_asc', 'sorting_desc');
                        }
                        refreshTable();
                    });
                }

                if(prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        if (!this.classList.contains('disabled')) {
                            currentPage--;
                            refreshTable();
                        }
                    });
                }

                if(nextBtn) {
                    nextBtn.addEventListener('click', function() {
                        if (!this.classList.contains('disabled')) {
                            currentPage++;
                            refreshTable();
                        }
                    });
                }

                refreshTable();
            });

            // Deteksi perubahan hash jika pengguna mengklik link dari halaman yang sama
            window.addEventListener('hashchange', checkHashAndOpenAccordion);
        </script>
        <?php
    }

    return ob_get_clean();
}

add_action( 'vc_before_init', 'ppic_register_ppid_daftar_info_main_element' );
function ppic_register_ppid_daftar_info_main_element() {
    // ... [Bagian ini tetap sama seperti sebelumnya] ...
    if ( ! function_exists( 'vc_map' ) ) {
        return;
    }

    // Pilihan Kategori Dropdown WPBakery
    $kategori_berkala = array(
        'Laporan & Akuntabilitas' => 'laporan',
        'Survei Kepuasan'         => 'survei',
        'LHKPN Pimpinan'          => 'lhkpn',
        'Sarpras & Aset'          => 'sarpras',
        'Informasi SDM'           => 'sdm',
        'Perencanaan & Penganggaran'=> 'anggaran',
        'Kumpulan JDIH'           => 'jdih',
    );

    // ==========================================
    // DUMMY DATA: TEPAT 3 DATA PER KATEGORI TAB
    // ==========================================
    $dummy_berkala = array(
        array( 'desc' => 'Laporan Keuangan Audited 2023', 'file_url' => '#', 'filter_cat' => 'laporan' ),
        array( 'desc' => 'Laporan Kinerja Instansi Pemerintah (LKIP) 2024', 'file_url' => '#', 'filter_cat' => 'laporan' ),
        array( 'desc' => 'Laporan Akuntabilitas Tahun 2022', 'file_url' => '#', 'filter_cat' => 'laporan' ),
        
        array( 'desc' => 'Indeks Kepuasan Masyarakat 2022', 'file_url' => '#', 'filter_cat' => 'survei' ),
        array( 'desc' => 'Indeks Kepuasan Masyarakat 2023', 'file_url' => '#', 'filter_cat' => 'survei' ),
        array( 'desc' => 'Indeks Kepuasan Masyarakat 2024', 'file_url' => '#', 'filter_cat' => 'survei' ),
    );

    $dummy_setiapsaat = array(
        array( 'desc' => 'Pedoman Mutu' ),
        array( 'desc' => 'Peraturan, Keputusan, dan Kebijakan Direktur' ),
        array( 'desc' => 'Perjanjian dengan pihak ketiga' ),
    );

    $dummy_sertamerta = array(
        array( 'desc' => 'Jalur Evakuasi dan titik kumpul', 'status_tipe' => 'file', 'file_url' => '#' ),
        array( 'desc' => 'Informasi peringatan dini bencana alam', 'status_tipe' => 'belum', 'file_url' => '' ),
        array( 'desc' => 'Perubahan pelaksanaan layanan publik', 'status_tipe' => 'belum', 'file_url' => '' ),
    );

    $dummy_dikecualikan = array(
        array( 'desc' => 'Data pribadi pegawai, taruna, peserta didik' ),
        array( 'desc' => 'Rekam medis dan data kesehatan' ),
        array( 'desc' => 'Hasil assessment dan evaluasi jabatan' ),
    );

    vc_map(
        array(
            'name'     => __( 'PPIC PPID Daftar Info - Konten', 'ppic-custom-element' ),
            'base'     => 'ppic_ppid_daftar_info_main',
            'category' => __( 'PPIC Elements', 'ppic-custom-element' ),
            'icon'     => 'dashicons dashicons-list-view',
            'params'   => array(
                // 1. BERKALA
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Tabel: Informasi Berkala', 'ppic-custom-element' ),
                    'param_name' => 'berkala_items',
                    'value'      => urlencode( wp_json_encode( $dummy_berkala ) ),
                    'group'      => __( 'Berkala', 'ppic-custom-element' ),
                    'params'     => array(
                        array(
                            'type'        => 'dropdown',
                            'heading'     => 'Pilih Kategori Filter',
                            'param_name'  => 'filter_cat',
                            'value'       => $kategori_berkala,
                            'description' => 'Pilih kategori agar dokumen ini muncul saat tombol filter diklik.',
                            'admin_label' => true,
                        ),
                        array('type' => 'textfield', 'heading' => 'Deskripsi Dokumen', 'param_name' => 'desc', 'admin_label' => true),
                        array('type' => 'textfield', 'heading' => 'URL File PDF/Lampiran', 'param_name' => 'file_url'),
                    ),
                ),
                // 2. SETIAP SAAT
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Tabel: Informasi Setiap Saat', 'ppic-custom-element' ),
                    'param_name' => 'setiapsaat_items',
                    'value'      => urlencode( wp_json_encode( $dummy_setiapsaat ) ),
                    'group'      => __( 'Setiap Saat', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Daftar Informasi', 'param_name' => 'desc', 'admin_label' => true),
                    ),
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => __( 'Teks Tombol CTA Setiap Saat', 'ppic-custom-element' ),
                    'param_name' => 'btn_setiapsaat',
                    'value'      => 'Akses Informasi Setiap Saat',
                    'group'      => __( 'Setiap Saat', 'ppic-custom-element' ),
                ),
                array(
                    'type'       => 'vc_link',
                    'heading'    => __( 'URL Tombol CTA', 'ppic-custom-element' ),
                    'param_name' => 'url_setiapsaat',
                    'value'      => 'url:https%3A%2F%2Fdocs.google.com%2Fforms|target:_blank',
                    'group'      => __( 'Setiap Saat', 'ppic-custom-element' ),
                ),
                // 3. SERTA MERTA
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Tabel: Informasi Serta Merta', 'ppic-custom-element' ),
                    'param_name' => 'sertamerta_items',
                    'value'      => urlencode( wp_json_encode( $dummy_sertamerta ) ),
                    'group'      => __( 'Serta Merta', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Jenis Informasi', 'param_name' => 'desc', 'admin_label' => true),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => 'Status Informasi',
                            'param_name' => 'status_tipe',
                            'value'      => array(
                                'File Tersedia (Tombol Mata & Unduh)' => 'file',
                                'Belum Tersedia (Badge Kuning)' => 'belum',
                            )
                        ),
                        array('type' => 'textfield', 'heading' => 'URL File (Hanya jika File Tersedia)', 'param_name' => 'file_url'),
                    ),
                ),
                // 4. DIKECUALIKAN
                array(
                    'type'       => 'param_group',
                    'heading'    => __( 'Tabel: Informasi Dikecualikan', 'ppic-custom-element' ),
                    'param_name' => 'dikecualikan_items',
                    'value'      => urlencode( wp_json_encode( $dummy_dikecualikan ) ),
                    'group'      => __( 'Dikecualikan', 'ppic-custom-element' ),
                    'params'     => array(
                        array('type' => 'textfield', 'heading' => 'Kategori Informasi Dikecualikan', 'param_name' => 'desc', 'admin_label' => true),
                    ),
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