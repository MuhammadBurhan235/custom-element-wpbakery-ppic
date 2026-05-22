<?php
require_once __DIR__ . '/preview-bootstrap.php';

$hero_html = ppic_hero_section_render(
    array(
        'title'       => 'Preview PPIC Hero Slider',
        'description' => 'Halaman ini dipakai untuk preview lokal elemen hero tanpa perlu menjalankan WordPress penuh. Gambar di sisi kanan memakai slider dan semua slide dijaga dalam ukuran yang sama.',
        'btn_text'    => 'Buka Situs PPIC',
        'btn_link'    => 'url:https%3A%2F%2Fppicurug.ac.id%2F|title:Buka%20Situs%20PPIC|target:_blank',
        'images'      => '1,2,3',
    )
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview PPIC Hero</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            margin: 0;
            background: #091521;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .preview-shell {
            min-height: 100vh;
        }

        .preview-note {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 20px 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="preview-shell">
        <div class="preview-note">Preview lokal elemen hero. File ini memakai stub fungsi WordPress/WPBakery agar komponen bisa dirender langsung.</div>
        <?php echo $hero_html; ?>
    </div>
</body>
</html>