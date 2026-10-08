<?php
require_once __DIR__ . '/services/config.php';

$stat_kategori_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kategori");
$total_kategori = (int)(mysqli_fetch_assoc($stat_kategori_query)['total'] ?? 0);

$stat_produk_query = mysqli_query($conn, "SELECT COUNT(*) AS total, SUM(stok) AS total_stok, SUM(harga * stok) AS total_aset FROM produk");
$stat_produk = mysqli_fetch_assoc($stat_produk_query);
$total_produk = (int)($stat_produk['total'] ?? 0);
$total_stok = (int)($stat_produk['total_stok'] ?? 0);
$total_aset = (float)($stat_produk['total_aset'] ?? 0);

$stat_penjualan_query = mysqli_query($conn, "SELECT COUNT(*) AS total, SUM(jumlah) AS total_qty, SUM(CASE WHEN status_pembayaran = 'Lunas' THEN total_harga ELSE 0 END) AS total_omset, SUM(CASE WHEN status_pembayaran = 'Lunas' THEN 1 ELSE 0 END) AS total_lunas FROM penjualan");
$stat_penjualan = mysqli_fetch_assoc($stat_penjualan_query);
$total_penjualan = (int)($stat_penjualan['total'] ?? 0);
$total_qty_terjual = (int)($stat_penjualan['total_qty'] ?? 0);
$total_omset = (float)($stat_penjualan['total_omset'] ?? 0);
$total_lunas = (int)($stat_penjualan['total_lunas'] ?? 0);

$query_kategori = "SELECT k.*, COUNT(p.id_produk) AS total_produk_terkait 
                   FROM kategori k 
                   LEFT JOIN produk p ON k.id_kategori = p.id_kategori 
                   GROUP BY k.id_kategori 
                   ORDER BY k.id_kategori ASC";
$result_kategori = mysqli_query($conn, $query_kategori);

$query_produk = "SELECT p.*, k.nama_kategori 
                 FROM produk p 
                 INNER JOIN kategori k ON p.id_kategori = k.id_kategori 
                 ORDER BY p.id_produk ASC";
$result_produk = mysqli_query($conn, $query_produk);

$query_penjualan = "SELECT pj.*, p.nama_produk, p.harga AS harga_satuan, k.nama_kategori 
                    FROM penjualan pj 
                    INNER JOIN produk p ON pj.id_produk = p.id_produk 
                    INNER JOIN kategori k ON pj.id_kategori = k.id_kategori 
                    ORDER BY pj.tanggal_transaksi DESC, pj.id_penjualan DESC";
$result_penjualan = mysqli_query($conn, $query_penjualan);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk & Transaksi — Toko Online</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="app-header">
        <div class="header-inner">
            <div class="brand-section">
                <div class="brand-logo-mark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                </div>
                <div class="brand-meta">
                    <span class="brand-title">Toko Online</span>
                    <span class="env-pill">Production</span>
                </div>
            </div>

            <nav class="header-nav">
                <a href="#tabel-kategori" class="nav-tab">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z" />
                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor" />
                    </svg>
                    Kategori
                    <span class="tab-count"><?= $total_kategori; ?></span>
                </a>
                <a href="#tabel-produk" class="nav-tab">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m7.5 4.27 9 5.15" />
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                        <path d="m3.3 7 8.7 5 8.7-5" />
                        <path d="M12 22V12" />
                    </svg>
                    Produk
                    <span class="tab-count"><?= $total_produk; ?></span>
                </a>
                <a href="#tabel-penjualan" class="nav-tab">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="14" x="2" y="5" rx="2" />
                        <line x1="2" x2="22" y1="10" y2="10" />
                    </svg>
                    Penjualan
                    <span class="tab-count"><?= $total_penjualan; ?></span>
                </a>
            </nav>

            <div class="header-aside">
                <div class="status-indicator" title="Koneksi MariaDB Berjalan Normal">
                    <span class="pulse-dot"></span>
                    <span>MariaDB Connected</span>
                </div>
                <div class="user-badge">
                    Niam &bull; <strong>26081010039</strong>
                </div>
            </div>
        </div>
    </header>

    <main class="app-main">
        <section class="page-intro">
            <div class="intro-text-wrap">
                <div class="breadcrumbs">
                    <span>Sistem Basis Data</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                    <span>Relasi One-to-Many</span>
                </div>
                <h1 class="page-title">Katalog Produk & Transaksi Penjualan</h1>
                <p class="page-description">
                    Pengelolaan dan pemantauan relasi data antara entitas Master Kategori, Master Produk, dan Transaksi Penjualan yang sinkron secara real-time.
                </p>
            </div>

            <div class="page-actions">
                <button type="button" class="btn btn-secondary" onclick="window.print()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect width="12" height="8" x="6" y="14" />
                    </svg>
                    Cetak Laporan
                </button>
                <button type="button" class="btn btn-primary" onclick="window.location.reload()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                        <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16" />
                        <path d="M16 21h5v-5" />
                    </svg>
                    Refresh
                </button>
            </div>
        </section>

        <section class="metrics-band">
            <div class="metric-cell">
                <div class="metric-header">
                    <span class="metric-label">Total Omset Lunas</span>
                    <span class="metric-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                            <path d="M12 18V6" />
                        </svg>
                    </span>
                </div>
                <div class="metric-value">Rp <?= number_format($total_omset, 0, ',', '.'); ?></div>
                <div class="metric-meta">
                    Dari <strong><?= $total_lunas; ?></strong> transaksi berstatus lunas
                </div>
            </div>

            <div class="metric-cell">
                <div class="metric-header">
                    <span class="metric-label">Katalog Produk</span>
                    <span class="metric-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="7" x="3" y="3" rx="1" />
                            <rect width="7" height="7" x="14" y="3" rx="1" />
                            <rect width="7" height="7" x="14" y="14" rx="1" />
                            <rect width="7" height="7" x="3" y="14" rx="1" />
                        </svg>
                    </span>
                </div>
                <div class="metric-value"><?= $total_produk; ?> <span style="font-size: 0.9rem; font-weight: 500; color: var(--text-muted);">SKU</span></div>
                <div class="metric-meta">
                    Terbagi dalam <strong><?= $total_kategori; ?></strong> kategori aktif
                </div>
            </div>

            <div class="metric-cell">
                <div class="metric-header">
                    <span class="metric-label">Total Stok Fisik</span>
                    <span class="metric-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                        </svg>
                    </span>
                </div>
                <div class="metric-value"><?= number_format($total_stok, 0, ',', '.'); ?> <span style="font-size: 0.9rem; font-weight: 500; color: var(--text-muted);">Unit</span></div>
                <div class="metric-meta">
                    Nilai aset: <strong>Rp <?= number_format($total_aset, 0, ',', '.'); ?></strong>
                </div>
            </div>

            <div class="metric-cell">
                <div class="metric-header">
                    <span class="metric-label">Volume Terjual</span>
                    <span class="metric-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 3 3 9 4-4 4 4 7-9" />
                        </svg>
                    </span>
                </div>
                <div class="metric-value"><?= number_format($total_qty_terjual, 0, ',', '.'); ?> <span style="font-size: 0.9rem; font-weight: 500; color: var(--text-muted);">Item</span></div>
                <div class="metric-meta">
                    Rata-rata <strong><?= round($total_qty_terjual / max(1, $total_penjualan), 1); ?></strong> item per transaksi
                </div>
            </div>
        </section>

        <section class="filter-bar">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="search" id="quick-search-input" class="search-input" placeholder="Cari nama barang, kategori, pelanggan, atau nomor nota..." autocomplete="off">
            </div>

            <div class="filter-chips">
                <button type="button" class="filter-chip active" data-filter="all">Semua Data</button>
                <button type="button" class="filter-chip" data-filter="lunas">Hanya Lunas</button>
                <button type="button" class="filter-chip" data-filter="low-stock">Stok Rendah (&le; 25)</button>
            </div>

            <div class="search-summary-text" id="search-indicator">
                Menampilkan data aktif
            </div>
        </section>

        <section class="data-panel" id="tabel-kategori">
            <div class="panel-header">
                <div class="panel-title-group">
                    <span class="panel-badge">Tabel 1</span>
                    <div>
                        <h2 class="panel-title">Master Kategori</h2>
                        <p class="panel-subtitle">Entitas induk klasifikasi produk (Relasi 1:N)</p>
                    </div>
                </div>
                <div class="panel-tools">
                    <span class="count-pill"><?= mysqli_num_rows($result_kategori); ?> Baris</span>
                </div>
            </div>

            <div class="table-container">
                <table class="table-native" data-table-type="kategori">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th style="width: 140px;">Kode Kategori</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi Klasifikasi</th>
                            <th class="col-center" style="width: 130px;">Jumlah Produk</th>
                            <th class="col-center" style="width: 110px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_kategori) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_kategori)): ?>
                                <tr class="data-row" data-searchable="<?= htmlspecialchars(strtolower($row['kode_kategori'] . ' ' . $row['nama_kategori'] . ' ' . $row['deskripsi'])); ?>">
                                    <td class="col-id">#<?= htmlspecialchars($row['id_kategori']); ?></td>
                                    <td class="col-mono"><?= htmlspecialchars($row['kode_kategori']); ?></td>
                                    <td class="col-primary-text"><?= htmlspecialchars($row['nama_kategori']); ?></td>
                                    <td class="col-muted-desc"><?= htmlspecialchars($row['deskripsi'] ?? '-'); ?></td>
                                    <td class="col-center">
                                        <span class="tag-subtle">
                                            <?= (int)$row['total_produk_terkait']; ?> Produk
                                        </span>
                                    </td>
                                    <td class="col-center">
                                        <?php if ($row['status_kategori'] === 'Aktif'): ?>
                                            <span class="status-pill status-active">
                                                <span class="status-dot"></span> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="status-pill status-inactive">
                                                <span class="status-dot"></span> Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="table-empty-row">
                                    <div class="empty-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z" />
                                        </svg>
                                        <p>Belum ada data kategori tersimpan</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="data-panel" id="tabel-produk">
            <div class="panel-header">
                <div class="panel-title-group">
                    <span class="panel-badge">Tabel 2</span>
                    <div>
                        <h2 class="panel-title">Katalog Produk</h2>
                        <p class="panel-subtitle">Data produk terhubung dengan kategori via FOREIGN KEY</p>
                    </div>
                </div>
                <div class="panel-tools">
                    <span class="count-pill"><?= mysqli_num_rows($result_produk); ?> Baris</span>
                </div>
            </div>

            <div class="table-container">
                <table class="table-native" data-table-type="produk">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th style="width: 140px;">SKU / Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori (JOIN)</th>
                            <th class="col-numeric" style="width: 140px;">Harga Satuan</th>
                            <th class="col-center" style="width: 90px;">Stok</th>
                            <th style="width: 80px;">Satuan</th>
                            <th class="col-numeric" style="width: 150px;">Nilai Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_produk) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_produk)): ?>
                                <?php $nilai_stok = $row['harga'] * $row['stok']; ?>
                                <tr class="data-row"
                                    data-stok="<?= (int)$row['stok']; ?>"
                                    data-searchable="<?= htmlspecialchars(strtolower($row['kode_produk'] . ' ' . $row['nama_produk'] . ' ' . $row['nama_kategori'])); ?>">
                                    <td class="col-id">#<?= htmlspecialchars($row['id_produk']); ?></td>
                                    <td class="col-mono"><?= htmlspecialchars($row['kode_produk']); ?></td>
                                    <td class="col-primary-text"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                    <td>
                                        <span class="tag-subtle">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z" />
                                            </svg>
                                            <?= htmlspecialchars($row['nama_kategori']); ?>
                                        </span>
                                    </td>
                                    <td class="col-numeric">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td class="col-center">
                                        <span class="stock-value <?= ($row['stok'] <= 25) ? 'stock-low' : ''; ?>">
                                            <?= htmlspecialchars($row['stok']); ?>
                                        </span>
                                    </td>
                                    <td class="col-mono" style="color: var(--text-muted);"><?= htmlspecialchars($row['satuan']); ?></td>
                                    <td class="col-numeric" style="color: var(--text-secondary);">
                                        Rp <?= number_format($nilai_stok, 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="table-empty-row">
                                    <div class="empty-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                        </svg>
                                        <p>Belum ada produk tersimpan</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="data-panel" id="tabel-penjualan">
            <div class="panel-header">
                <div class="panel-title-group">
                    <span class="panel-badge">Tabel 3</span>
                    <div>
                        <h2 class="panel-title">Riwayat Transaksi Penjualan</h2>
                        <p class="panel-subtitle">Entitas transaksi relasional multi-tabel (JOIN Kategori & Produk)</p>
                    </div>
                </div>
                <div class="panel-tools">
                    <span class="count-pill"><?= mysqli_num_rows($result_penjualan); ?> Baris</span>
                </div>
            </div>

            <div class="table-container">
                <table class="table-native" data-table-type="penjualan">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th style="width: 150px;">No. Transaksi</th>
                            <th style="width: 110px;">Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kategori</th>
                            <th>Produk</th>
                            <th class="col-center" style="width: 60px;">Qty</th>
                            <th class="col-numeric" style="width: 140px;">Total Bayar</th>
                            <th style="width: 120px;">Metode</th>
                            <th class="col-center" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_penjualan) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_penjualan)): ?>
                                <tr class="data-row"
                                    data-status="<?= htmlspecialchars(strtolower($row['status_pembayaran'])); ?>"
                                    data-searchable="<?= htmlspecialchars(strtolower($row['nomor_transaksi'] . ' ' . $row['nama_pelanggan'] . ' ' . $row['nama_produk'] . ' ' . $row['nama_kategori'] . ' ' . $row['status_pembayaran'] . ' ' . $row['metode_pembayaran'])); ?>">
                                    <td class="col-id">#<?= htmlspecialchars($row['id_penjualan']); ?></td>
                                    <td class="col-mono"><?= htmlspecialchars($row['nomor_transaksi']); ?></td>
                                    <td class="col-mono" style="font-size: 0.8rem;"><?= date('d M Y', strtotime($row['tanggal_transaksi'])); ?></td>
                                    <td class="col-primary-text"><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                                    <td>
                                        <span class="tag-subtle"><?= htmlspecialchars($row['nama_kategori']); ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                    <td class="col-center col-mono" style="font-weight: 600;"><?= htmlspecialchars($row['jumlah']); ?></td>
                                    <td class="col-numeric">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td class="col-mono" style="font-size: 0.78rem; color: var(--text-secondary);"><?= htmlspecialchars($row['metode_pembayaran']); ?></td>
                                    <td class="col-center">
                                        <?php if ($row['status_pembayaran'] === 'Lunas'): ?>
                                            <span class="status-pill status-lunas">
                                                <span class="status-dot"></span> Lunas
                                            </span>
                                        <?php elseif ($row['status_pembayaran'] === 'Menunggu Pembayaran'): ?>
                                            <span class="status-pill status-pending">
                                                <span class="status-dot"></span> Menunggu
                                            </span>
                                        <?php else: ?>
                                            <span class="status-pill status-batal">
                                                <span class="status-dot"></span> Dibatalkan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="table-empty-row">
                                    <div class="empty-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect width="20" height="14" x="2" y="5" rx="2" />
                                            <line x1="2" x2="22" y1="10" y2="10" />
                                        </svg>
                                        <p>Belum ada data transaksi penjualan tersimpan</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="app-footer">
        <div class="footer-inner">
            <div>
                Sistem Manajemen Toko Online &bull; Penugasan Praktikum Web & Basis Data
            </div>
            <div class="footer-links">
                <span>NPM: <?= htmlspecialchars('26081010039'); ?></span>
                <span>&bull;</span>
                <span>Nama: <?= htmlspecialchars('Niam'); ?></span>
                <span>&bull;</span>
                <span>Database: db_tokoonline_26081010039</span>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            const searchInput = document.getElementById('quick-search-input');
            const filterChips = document.querySelectorAll('.filter-chip');
            const rows = document.querySelectorAll('.data-row');
            const indicator = document.getElementById('search-indicator');

            let currentQuery = '';
            let currentFilter = 'all';

            function applyFilters() {
                let visibleCount = 0;
                const q = currentQuery.trim().toLowerCase();

                rows.forEach(row => {
                    const text = row.getAttribute('data-searchable') || '';
                    const status = row.getAttribute('data-status') || '';
                    const stok = parseInt(row.getAttribute('data-stok') || '999', 10);

                    const matchesQuery = q === '' || text.includes(q);

                    let matchesChip = true;
                    if (currentFilter === 'lunas') {
                        matchesChip = status === 'lunas' || !row.hasAttribute('data-status');
                    } else if (currentFilter === 'low-stock') {
                        matchesChip = stok <= 25 || !row.hasAttribute('data-stok');
                    }

                    if (matchesQuery && matchesChip) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (q !== '' || currentFilter !== 'all') {
                    indicator.textContent = `Menampilkan ${visibleCount} baris sesuai filter`;
                } else {
                    indicator.textContent = 'Menampilkan seluruh data aktif';
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', e => {
                    currentQuery = e.target.value;
                    applyFilters();
                });
            }

            filterChips.forEach(chip => {
                chip.addEventListener('click', () => {
                    filterChips.forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    currentFilter = chip.getAttribute('data-filter');
                    applyFilters();
                });
            });
        })();
    </script>

</body>

</html>
<?php
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>