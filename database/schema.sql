CREATE DATABASE IF NOT EXISTS db_tokoonline_26081010039;

USE db_tokoonline_26081010039;

DROP TABLE IF EXISTS penjualan;

DROP TABLE IF EXISTS produk;

DROP TABLE IF EXISTS kategori;

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    kode_kategori VARCHAR(20) NOT NULL UNIQUE,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    status_kategori ENUM ('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    kode_produk VARCHAR(20) NOT NULL UNIQUE,
    nama_produk VARCHAR(150) NOT NULL,
    harga DECIMAL(12, 2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    satuan VARCHAR(20) NOT NULL DEFAULT 'Pcs',
    CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori) REFERENCES kategori (id_kategori) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE penjualan (
    id_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    nomor_transaksi VARCHAR(30) NOT NULL UNIQUE,
    id_kategori INT NOT NULL,
    id_produk INT NOT NULL,
    nama_pelanggan VARCHAR(100) NOT NULL,
    tanggal_transaksi DATE NOT NULL,
    jumlah INT NOT NULL,
    total_harga DECIMAL(12, 2) NOT NULL,
    metode_pembayaran ENUM (
        'Transfer Bank',
        'E-Wallet',
        'Kartu Kredit',
        'COD'
    ) NOT NULL,
    status_pembayaran ENUM ('Lunas', 'Menunggu Pembayaran', 'Dibatalkan') NOT NULL DEFAULT 'Lunas',
    CONSTRAINT fk_penjualan_kategori FOREIGN KEY (id_kategori) REFERENCES kategori (id_kategori) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_penjualan_produk FOREIGN KEY (id_produk) REFERENCES produk (id_produk) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    kategori (
        kode_kategori,
        nama_kategori,
        deskripsi,
        status_kategori
    )
VALUES
    (
        'KAT-ELEC',
        'Elektronik & Gadget',
        'Perangkat komputer, ponsel pintar, audio, dan periferal digital',
        'Aktif'
    ),
    (
        'KAT-FASH',
        'Fashion & Busana',
        'Pakaian pria, pakaian wanita, jaket, dan aksesori gaya hidup',
        'Aktif'
    ),
    (
        'KAT-FOOD',
        'Makanan & Minuman',
        'Kopi nusantara, makanan ringan, teh herbal, dan camilan organik',
        'Aktif'
    ),
    (
        'KAT-HOME',
        'Perlengkapan Rumah',
        'Dekorasi interior, lampu meja belajar, dan perabotan ruang kerja',
        'Aktif'
    ),
    (
        'KAT-BOOK',
        'Buku & Alat Tulis',
        'Buku teks teknologi, novel inspiratif, dan perlengkapan studi',
        'Aktif'
    ),
    (
        'KAT-SPRT',
        'Olahraga & Hobi',
        'Matras fitness, botol minum stainless, dan perlengkapan olahraga luar ruangan',
        'Aktif'
    );

INSERT INTO
    produk (
        id_kategori,
        kode_produk,
        nama_produk,
        harga,
        stok,
        satuan
    )
VALUES
    (
        1,
        'PRD-ELC-001',
        'Keyboard Mekanikal TKL RGB',
        495000.00,
        30,
        'Unit'
    ),
    (
        1,
        'PRD-ELC-002',
        'Wireless Mouse Ergonomic Silent',
        235000.00,
        45,
        'Unit'
    ),
    (
        2,
        'PRD-FSH-001',
        'Kemeja Katun Oxford Slim Fit',
        189000.00,
        40,
        'Pcs'
    ),
    (
        2,
        'PRD-FSH-002',
        'Jaket Bomber Windbreaker Navy',
        285000.00,
        25,
        'Pcs'
    ),
    (
        3,
        'PRD-FOD-001',
        'Biji Kopi Arabika Aceh Gayo 250g',
        89000.00,
        60,
        'Pack'
    ),
    (
        4,
        'PRD-HOM-001',
        'Lampu Meja LED Touch Sensor Minimalis',
        145000.00,
        20,
        'Unit'
    ),
    (
        5,
        'PRD-BOK-001',
        'Buku Mahir Pemrograman Web Modern (PHP & MySQL)',
        125000.00,
        35,
        'Buku'
    ),
    (
        6,
        'PRD-SPT-001',
        'Matras Yoga Anti-Slip Tebal 8mm',
        155000.00,
        18,
        'Pcs'
    );

INSERT INTO
    penjualan (
        nomor_transaksi,
        id_kategori,
        id_produk,
        nama_pelanggan,
        tanggal_transaksi,
        jumlah,
        total_harga,
        metode_pembayaran,
        status_pembayaran
    )
VALUES
    (
        'TRX-20261001-001',
        1,
        1,
        'Budi Pratama',
        '2026-10-01',
        2,
        990000.00,
        'Transfer Bank',
        'Lunas'
    ),
    (
        'TRX-20261002-002',
        2,
        3,
        'Siti Nurhaliza',
        '2026-10-02',
        1,
        189000.00,
        'E-Wallet',
        'Lunas'
    ),
    (
        'TRX-20261003-003',
        3,
        5,
        'Rian Hidayat',
        '2026-10-03',
        3,
        267000.00,
        'COD',
        'Lunas'
    ),
    (
        'TRX-20261004-004',
        1,
        2,
        'Dewi Sartika',
        '2026-10-04',
        1,
        235000.00,
        'Transfer Bank',
        'Lunas'
    ),
    (
        'TRX-20261005-005',
        5,
        7,
        'Ahmad Fauzi',
        '2026-10-05',
        2,
        250000.00,
        'E-Wallet',
        'Lunas'
    ),
    (
        'TRX-20261006-006',
        4,
        6,
        'Indah Permatasari',
        '2026-10-06',
        1,
        145000.00,
        'Kartu Kredit',
        'Lunas'
    ),
    (
        'TRX-20261007-007',
        2,
        4,
        'Bayu Wicaksono',
        '2026-10-07',
        1,
        285000.00,
        'E-Wallet',
        'Menunggu Pembayaran'
    ),
    (
        'TRX-20261008-008',
        6,
        8,
        'Citra Kirana',
        '2026-10-08',
        2,
        310000.00,
        'Transfer Bank',
        'Lunas'
    );

SELECT
    id_produk,
    kode_produk,
    nama_produk,
    harga,
    stok,
    satuan
FROM
    produk
WHERE
    stok > 20
ORDER BY
    harga DESC;

SELECT
    pj.nomor_transaksi,
    pj.tanggal_transaksi,
    pj.nama_pelanggan,
    k.nama_kategori,
    p.nama_produk,
    p.harga AS harga_satuan,
    pj.jumlah,
    pj.total_harga,
    pj.metode_pembayaran,
    pj.status_pembayaran
FROM
    penjualan pj
    INNER JOIN produk p ON pj.id_produk = p.id_produk
    INNER JOIN kategori k ON pj.id_kategori = k.id_kategori
ORDER BY
    pj.tanggal_transaksi DESC;

UPDATE produk
SET
    stok = stok - 2,
    harga = 499000.00
WHERE
    id_produk = 1;

DELETE FROM penjualan
WHERE
    status_pembayaran = 'Dibatalkan';
