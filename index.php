<?php
$app_title = 'PPIC Local Development Server';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars( $app_title, ENT_QUOTES, 'UTF-8' ); ?></title>
    <style>
        :root {
            --bg: #08131d;
            --panel: rgba(10, 23, 34, 0.84);
            --line: rgba(255, 255, 255, 0.12);
            --text: #eef5fb;
            --muted: rgba(238, 245, 251, 0.72);
            --accent: #f8b425;
            --accent-dark: #0a1621;
            --secondary: #1b6fb8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(circle at top, rgba(248, 180, 37, 0.16), transparent 24%),
                linear-gradient(180deg, #07111a 0%, #0a1621 100%);
            color: var(--text);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .dev-panel {
            width: min(760px, 100%);
            padding: 32px;
            border: 1px solid var(--line);
            border-radius: 28px;
            background: var(--panel);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(16px);
        }

        .dev-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(248, 180, 37, 0.14);
            color: #ffe1a0;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        h1 {
            margin: 18px 0 12px;
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.1;
        }

        p {
            margin: 0;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.7;
        }

        .dev-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 28px;
        }

        .dev-action {
            display: block;
            padding: 18px 20px;
            border-radius: 20px;
            text-decoration: none;
            border: 1px solid var(--line);
            transition:
                transform 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background-color 0.2s ease;
        }

        .dev-action:hover {
            transform: translateY(-2px);
            border-color: rgba(248, 180, 37, 0.55);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.16);
        }

        .dev-action-primary {
            background: linear-gradient(135deg, #f8b425 0%, #ffd36d 100%);
            color: var(--accent-dark);
        }

        .dev-action-secondary {
            background: rgba(27, 111, 184, 0.12);
            color: var(--text);
        }

        .dev-action strong {
            display: block;
            margin-bottom: 8px;
            font-size: 20px;
            line-height: 1.3;
        }

        .dev-action span {
            display: block;
            font-size: 14px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .dev-links {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .dev-links a {
            color: #9fd0ff;
        }

        @media (max-width: 640px) {
            .dev-panel {
                padding: 24px;
                border-radius: 22px;
            }

            .dev-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="dev-panel">
        <div class="dev-badge">Development Server Active</div>
        <h1><?php echo htmlspecialchars( $app_title, ENT_QUOTES, 'UTF-8' ); ?></h1>
        <p>Gunakan tombol di bawah untuk membuka halaman preview seluruh elemen atau mengunduh ZIP plugin tanpa file preview.</p>

        <div class="dev-actions">
            <a class="dev-action dev-action-secondary" href="preview-all-elements.php">
                <strong>Open Preview</strong>
                <span>Masuk ke halaman preview semua elemen plugin.</span>
            </a>
            <a class="dev-action dev-action-primary" href="download-plugin.php">
                <strong>Download ZIP Plugin</strong>
                <span>Unduh paket plugin siap pasang dengan nama file berisi versi plugin.</span>
            </a>
        </div>

        <div class="dev-links">
            <div><a href="http://127.0.0.1:8080/preview-all-elements.php">http://127.0.0.1:8080/preview-all-elements.php</a></div>
            <div><a href="http://127.0.0.1:8080/download-plugin.php">http://127.0.0.1:8080/download-plugin.php</a></div>
        </div>
    </main>
</body>
</html>