<?php

$host     = "127.0.0.1";
$user     = "root";
$password = "root";
$database = "db_tokoonline_26081010039";
$port     = 3306;

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect($host, $user, $password, $database, $port);

if (!$conn && $password !== "") {
    $conn = @mysqli_connect($host, $user, "", $database, $port);
}

if (!$conn) {
    http_response_code(500);
    $err_msg = htmlspecialchars(mysqli_connect_error());
    die("<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Koneksi Basis Data Gagal — Toko Online</title>
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&display=swap' rel='stylesheet'>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            line-height: 1.5;
        }
        .error-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
            max-width: 580px;
            width: 100%;
            padding: 2rem;
        }
        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 600;
            font-family: ui-monospace, monospace;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 2px 8px;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .error-badge svg { width: 13px; height: 13px; }
        .error-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0.4rem;
        }
        .error-desc {
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 1.25rem;
        }
        .diag-box {
            background: #0f172a;
            color: #f8fafc;
            border-radius: 6px;
            padding: 0.85rem 1rem;
            font-family: ui-monospace, monospace;
            font-size: 0.78rem;
            margin-bottom: 1.25rem;
            overflow-x: auto;
        }
        .diag-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.35rem;
        }
        .diag-row:last-child { margin-bottom: 0; }
        .diag-label { color: #94a3b8; min-width: 80px; }
        .diag-val { color: #fca5a5; font-weight: 600; word-break: break-all; }
        .steps-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.5rem;
        }
        .steps-list {
            list-style: none;
            counter-reset: step;
            margin-bottom: 1.5rem;
        }
        .steps-list li {
            counter-increment: step;
            font-size: 0.825rem;
            color: #475569;
            margin-bottom: 0.45rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .steps-list li::before {
            content: counter(step);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 17px;
            height: 17px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            border-radius: 50%;
            font-size: 0.68rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .steps-list code {
            font-family: ui-monospace, monospace;
            background: #f1f5f9;
            padding: 1px 5px;
            border-radius: 4px;
            color: #0f172a;
            font-size: 0.78rem;
        }
        .action-row {
            display: flex;
            gap: 0.75rem;
            border-top: 1px solid #e2e8f0;
            padding-top: 1.25rem;
        }
        .btn-retry {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #0f172a;
            color: #ffffff;
            border: none;
            padding: 0.5rem 0.95rem;
            border-radius: 6px;
            font-size: 0.825rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s ease;
        }
        .btn-retry:hover { background: #334155; }
        .btn-retry svg { width: 14px; height: 14px; }
    </style>
</head>
<body>
    <div class='error-card'>
        <div class='error-badge'>
            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                <circle cx='12' cy='12' r='10'/>
                <line x1='12' x2='12' y1='8' y2='12'/>
                <line x1='12' x2='12.01' y1='16' y2='16'/>
            </svg>
            500 &bull; Database Connection Error
        </div>
        <h1 class='error-title'>Koneksi Basis Data Gagal</h1>
        <p class='error-desc'>Aplikasi web tidak dapat terhubung ke server MariaDB/MySQL menggunakan konfigurasi saat ini.</p>
        
        <div class='diag-box'>
            <div class='diag-row'><span class='diag-label'>Host</span><span class='diag-val' style='color:#e2e8f0;'>$host:$port</span></div>
            <div class='diag-row'><span class='diag-label'>Database</span><span class='diag-val' style='color:#e2e8f0;'>$database</span></div>
            <div class='diag-row'><span class='diag-label'>Error</span><span class='diag-val'>$err_msg</span></div>
        </div>

        <div class='steps-title'>Panduan Pemecahan Masalah:</div>
        <ol class='steps-list'>
            <li>Pastikan layanan database aktif (<code>sudo systemctl start mariadb</code> atau kontrol panel XAMPP).</li>
            <li>Pastikan database telah di-import: <code>mariadb -u root -p &lt; database/schema.sql</code>.</li>
            <li>Periksa kecocokan user dan password di file <code>services/config.php</code>.</li>
        </ol>

        <div class='action-row'>
            <button type='button' class='btn-retry' onclick='window.location.reload()'>
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                    <path d='M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8'/>
                    <path d='M3 3v5h5'/>
                    <path d='M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16'/>
                    <path d='M16 21h5v-5'/>
                </svg>
                Coba Hubungkan Kembali
            </button>
        </div>
    </div>
</body>
</html>");
}

mysqli_set_charset($conn, "utf8mb4");
