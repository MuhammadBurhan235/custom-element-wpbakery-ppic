<?php
require_once __DIR__ . '/preview-bootstrap.php';

$sections = array(
    array(
        'id' => 'dosen-hero',
        'title' => 'PPIC Dosen Hero',
        'html' => ppic_dosen_hero_render(
            array(
                'title_prefix' => 'Profil',
                'title_highlight' => 'Dosen & Instruktur',
                'description' => 'Preview lokal untuk elemen hero halaman dosen, agar layout dan copy bisa dicek tanpa WordPress penuh.',
                'button_text' => 'Kembali ke Beranda',
                'button_link' => 'url:https%3A%2F%2Fppicurug.ac.id%2F|title:Kembali%20ke%20Beranda',
            )
        ),
    ),
    array(
        'id' => 'dosen-directory',
        'title' => 'PPIC Dosen Directory',
        'html' => ppic_dosen_directory_render(
            array(
                'data_source' => 'spreadsheet',
                'spreadsheet_file' => '9001',
            )
        ),
    ),
    array(
        'id' => 'hero',
        'title' => 'PPIC Hero',
        'html' => ppic_hero_section_render(
            array(
                'images' => '1,2,3',
                'btn_link' => 'url:https%3A%2F%2Fppicurug.ac.id%2F|title:Jelajahi%20Program|target:_blank',
            )
        ),
    ),
    array(
        'id' => 'stats',
        'title' => 'PPIC Stats',
        'html' => ppic_stats_section_render( array() ),
    ),
    array(
        'id' => 'accreditation',
        'title' => 'PPIC Accreditation',
        'html' => ppic_accreditation_section_render( array() ),
    ),
    array(
        'id' => 'director',
        'title' => 'PPIC Director Greeting',
        'html' => ppic_director_greeting_render(
            array(
                'image' => '4',
            )
        ),
    ),
    array(
        'id' => 'featured-programs',
        'title' => 'PPIC Featured Programs',
        'html' => ppic_featured_programs_render( array() ),
    ),
    array(
        'id' => 'instagram',
        'title' => 'PPIC Instagram Feed',
        'html' => ppic_instagram_feed_render( array() ),
    ),
    array(
        'id' => 'why',
        'title' => 'PPIC Why',
        'html' => ppic_why_section_render(
            array(
                'image' => '5',
                'cta_link' => 'url:https%3A%2F%2Fppicurug.ac.id%2Ftentang%2F|title:Belum%20yakin%3F%20Jelajahi%20lebih%20dalam',
            )
        ),
    ),
    array(
        'id' => 'news',
        'title' => 'PPIC News Grid',
        'html' => ppic_news_grid_render( array() ),
    ),
    array(
        'id' => 'explore-programs',
        'title' => 'PPIC Explore Programs',
        'html' => ppic_explore_programs_render(
            array(
                'btn_link' => 'url:https%3A%2F%2Fppicurug.ac.id%2F|title:Kembali%20ke%20Beranda',
            )
        ),
    ),
    array(
        'id' => 'rpl-programs',
        'title' => 'PPIC RPL Programs',
        'html' => ppic_rpl_programs_render(
            array(
                'btn_link' => 'url:https%3A%2F%2Fppicurug.ac.id%2Frpl%2F|title:Daftar%20RPL',
            )
        ),
    ),
    array(
        'id' => 'about',
        'title' => 'PPIC About',
        'html' => ppic_about_section_render(
            array(
                'btn_link' => 'url:https%3A%2F%2Fppicurug.ac.id%2F|title:Kembali%20ke%20Beranda',
            )
        ),
    ),
    array(
        'id' => 'timeline',
        'title' => 'PPIC Timeline',
        'html' => ppic_timeline_section_render(
            array(
                'timeline_items' => ppic_preview_param_group(
                    array(
                        array(
                            'year' => '1952',
                            'item_title' => 'Akademi Penerbangan Indonesia Didirikan',
                            'item_desc' => 'Tonggak awal pendidikan penerbangan sipil Indonesia yang kemudian berkembang menjadi kampus Curug.',
                        ),
                        array(
                            'year' => '1954',
                            'item_title' => 'Kampus Pindah ke Curug',
                            'item_desc' => 'Lokasi Curug menjadi identitas kuat institusi hingga sekarang.',
                        ),
                        array(
                            'year' => '2019',
                            'item_title' => 'Transformasi Menjadi PPI Curug',
                            'item_desc' => 'Penguatan pendidikan vokasi dan profesi berbasis kebutuhan industri penerbangan modern.',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'id' => 'visi-misi',
        'title' => 'PPIC Visi Misi',
        'html' => ppic_visi_misi_section_render(
            array(
                'misi_items' => ppic_preview_param_group(
                    array(
                        array( 'misi_text' => 'Menyelenggarakan pendidikan penerbangan yang relevan dengan industri global.' ),
                        array( 'misi_text' => 'Mendorong penelitian terapan untuk inovasi transportasi udara.' ),
                        array( 'misi_text' => 'Membangun pengabdian masyarakat dan kerja sama strategis berkelanjutan.' ),
                    )
                ),
            )
        ),
    ),
    array(
        'id' => 'excellence',
        'title' => 'PPIC Excellence',
        'html' => ppic_excellence_section_render(
            array(
                'excellence_items' => ppic_preview_param_group(
                    array(
                        array( 'letter' => 'E', 'item_title' => 'Knowledge', 'item_desc' => 'Penguasaan ilmu dan standar keselamatan penerbangan.' ),
                        array( 'letter' => 'X', 'item_title' => 'Experience', 'item_desc' => 'Jam terbang pengalaman praktik yang terukur.' ),
                        array( 'letter' => 'C', 'item_title' => 'Service', 'item_desc' => 'Budaya pelayanan profesional dan responsif.' ),
                        array( 'letter' => 'L', 'item_title' => 'Leadership', 'item_desc' => 'Karakter kepemimpinan untuk lingkungan operasional dinamis.' ),
                        array( 'letter' => 'N', 'item_title' => 'Innovation', 'item_desc' => 'Kemampuan beradaptasi dan membangun solusi baru.' ),
                        array( 'letter' => 'E', 'item_title' => 'Ethics', 'item_desc' => 'Integritas sebagai fondasi profesi penerbangan.' ),
                    )
                ),
            )
        ),
    ),
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Semua Elemen PPIC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        :root {
            --preview-bg: #08131d;
            --preview-card: #0f1f2e;
            --preview-line: rgba(255, 255, 255, 0.12);
            --preview-text: #eff5fb;
            --preview-muted: rgba(239, 245, 251, 0.7);
            --preview-accent: #f8b425;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background:
                radial-gradient(circle at top, rgba(248, 180, 37, 0.16), transparent 24%),
                linear-gradient(180deg, #07111a 0%, #0a1621 100%);
            color: var(--preview-text);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .preview-page {
            width: 100%;
        }

        .preview-header {
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(14px);
            background: rgba(8, 19, 29, 0.9);
            border-bottom: 1px solid var(--preview-line);
        }

        .preview-header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .preview-title h1 {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
        }

        .preview-title p {
            margin: 6px 0 0;
            color: var(--preview-muted);
            font-size: 14px;
        }

        .preview-nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
        }

        .preview-nav a {
            padding: 9px 12px;
            border: 1px solid var(--preview-line);
            border-radius: 999px;
            color: var(--preview-text);
            text-decoration: none;
            font-size: 13px;
            transition:
                border-color 0.25s ease,
                background-color 0.25s ease,
                color 0.25s ease;
        }

        .preview-nav a:hover {
            border-color: rgba(248, 180, 37, 0.7);
            background: rgba(248, 180, 37, 0.12);
            color: #ffffff;
        }

        .preview-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .preview-download {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 16px;
            border-radius: 999px;
            background: var(--preview-accent);
            color: #0b1823;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                background-color 0.25s ease;
            box-shadow: 0 14px 28px rgba(248, 180, 37, 0.2);
        }

        .preview-download:hover {
            background: #ffd36d;
            color: #0b1823;
            transform: translateY(-1px);
        }

        .preview-content {
            padding: 28px 20px 80px;
        }

        .preview-section {
            max-width: 1280px;
            margin: 0 auto 28px;
            padding: 18px;
            border: 1px solid var(--preview-line);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.03);
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.18);
        }

        .preview-section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(248, 180, 37, 0.14);
            color: #ffe1a0;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .preview-section canvas,
        .preview-section img {
            max-width: 100%;
        }

        @media (max-width: 991px) {
            .preview-header-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .preview-actions {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            .preview-nav {
                justify-content: flex-start;
            }
        }

        @media (max-width: 575px) {
            .preview-content {
                padding-left: 12px;
                padding-right: 12px;
            }

            .preview-section {
                padding: 12px;
                border-radius: 20px;
            }

            .preview-title h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="preview-page">
        <header class="preview-header">
            <div class="preview-header-inner">
                <div class="preview-title">
                    <h1>Preview Semua Elemen PPIC</h1>
                    <p>Halaman ini merender semua elemen plugin tanpa WordPress penuh, termasuk preview elemen dosen dengan sumber data CSV lokal.</p>
                </div>
                <div class="preview-actions">
                    <a class="preview-download" href="download-plugin.php">
                        <span aria-hidden="true">&#8681;</span>
                        Download ZIP Plugin
                    </a>
                    <nav class="preview-nav">
                        <?php foreach ( $sections as $section ) : ?>
                            <a href="#<?php echo esc_attr( $section['id'] ); ?>"><?php echo esc_html( $section['title'] ); ?></a>
                        <?php endforeach; ?>
                    </nav>
                </div>
            </div>
        </header>

        <main class="preview-content">
            <?php foreach ( $sections as $section ) : ?>
                <section id="<?php echo esc_attr( $section['id'] ); ?>" class="preview-section">
                    <div class="preview-section-label"><?php echo esc_html( $section['title'] ); ?></div>
                    <?php echo $section['html']; ?>
                </section>
            <?php endforeach; ?>
        </main>
    </div>
</body>
</html>