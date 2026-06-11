<?php
session_start();
include("koneksi.php");

// Proteksi Keamanan: Pastikan yang mengakses file ini murni customer yang sudah login
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'customer') {
    die("Akses Ilegal: Anda harus login sebagai customer.");
}

// Cek apakah tombol checkout sudah diklik
if(isset($_POST['checkout'])){

    // Ambil data dari form
    $id_produk = (int)$_POST['id_produk'];
    $jumlah_beli = (int)$_POST['jumlah_beli'];
    
    // Ambil ID pengguna asli yang sedang login dari Session
    $id_customer = $_SESSION['id_pengguna']; 

    // Mulai transaksi untuk menjaga konsistensi 2 Database
    mysqli_begin_transaction($conn_penjualan);
    mysqli_begin_transaction($conn_gudang);

    try {
        // TAHAP 0: VALIDASI KEAMANAN STOK & HARGA
        $id_produk = (int)$id_produk;
        $jumlah_beli = (int)$jumlah_beli;
        if ($id_produk <= 0 || $jumlah_beli <= 0) {
            throw new Exception("Input tidak valid.");
        }

        // Ambil harga produk dari database (jangan dari input user - bisa dimanipulasi)
        $qProduk = mysqli_query($conn_gudang, "SELECT harga_produk FROM produk WHERE id_produk = '$id_produk' LIMIT 1");
        $dataProduk = mysqli_fetch_assoc($qProduk);
        if (!$dataProduk) {
            throw new Exception("Produk tidak ditemukan.");
        }
        $harga_produk = (int)$dataProduk['harga_produk'];
        $total_bayar = $harga_produk * $jumlah_beli;

        // Mengecek ketersediaan stok di tabel gudang sebelum memproses apapun
        $cekStok = mysqli_query($conn_gudang, "SELECT stok_sekarang FROM gudang WHERE id_produk = '$id_produk' FOR UPDATE");
        $dataStok = mysqli_fetch_assoc($cekStok);

        if(!$dataStok || (int)$dataStok['stok_sekarang'] < $jumlah_beli) {
            throw new Exception("Jumlah beli tidak valid atau stok tidak mencukupi.");
        }




        // TAHAP 1: DATABASE PENJUALAN (mengikuti schema backend/penjualan.sql)
        // 1) Pesanan (status awal: pending)
        $sql1 = "INSERT INTO pesanan (id_pengguna, status_pesanan, tanggal_pesanan) VALUES ('$id_customer', 'pending', CURDATE())";
        $q1 = mysqli_query($conn_penjualan, $sql1);
        if (!$q1) {
            throw new Exception('Gagal insert pesanan: ' . mysqli_error($conn_penjualan));
        }
        $id_pesanan_baru = mysqli_insert_id($conn_penjualan);

        // 2) Detail pesanan (harga_total = total_bayar)
        $sql2 = "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_total) VALUES ('$id_pesanan_baru', '$id_produk', '$jumlah_beli', '$total_bayar')";
        $q2 = mysqli_query($conn_penjualan, $sql2);
        if (!$q2) {
            throw new Exception('Gagal insert detail pesanan: ' . mysqli_error($conn_penjualan));
        }

        // 3) Pembayaran (metode_pembayaran default)
        $sql3 = "INSERT INTO pembayaran (id_pesanan, metode_pembayaran, jumlah_pembayaran) VALUES ('$id_pesanan_baru', 'cash', '$total_bayar')";
        $q3 = mysqli_query($conn_penjualan, $sql3);
        if (!$q3) {
            throw new Exception('Gagal insert pembayaran: ' . mysqli_error($conn_penjualan));
        }


        // TAHAP 2: SINKRONISASI KE DATABASE GUDANG
        // 4. Potong Stok Gudang ($stok_sekarang = $stok_sekarang - $jumlah_dipesan)
        $sql4 = "UPDATE gudang SET stok_sekarang = stok_sekarang - $jumlah_beli, tanggal_update = CURDATE() WHERE id_produk = '$id_produk'";
        $q4 = mysqli_query($conn_gudang, $sql4);
        if (!$q4) {
            throw new Exception('Gagal update stok gudang: ' . mysqli_error($conn_gudang));
        }
        if (mysqli_affected_rows($conn_gudang) == 0) {
            throw new Exception('Stok gudang tidak ditemukan untuk produk ini');
        }

        // 4b. Update stok di tabel produk juga (sinkronisasi dengan tampilan frontend)
        $sql4b = "UPDATE produk SET stok_produk = stok_produk - $jumlah_beli WHERE id_produk = '$id_produk'";
        $q4b = mysqli_query($conn_gudang, $sql4b);
        if (!$q4b) {
            throw new Exception('Gagal update stok produk: ' . mysqli_error($conn_gudang));
        }

        // 5. Catat ke Laporan Penjualan
        $sql5 = "INSERT INTO laporan_penjualan (id_produk, jumlah_terjual, tanggal_laporan) VALUES ('$id_produk', '$jumlah_beli', CURDATE())";
        $q5 = mysqli_query($conn_gudang, $sql5);
        if (!$q5) {
            throw new Exception('Gagal insert laporan penjualan: ' . mysqli_error($conn_gudang));
        }

        // 6. Update Best Seller (top terjual)
        // Schema: best_seller(id_produk, jumlah_terjual, periode)
        // Karena periode di skema enum butuh nilai bulan/tahun, kita gunakan 'tahun' default.
        // Jika baris belum ada => INSERT
        // Jika sudah ada => UPDATE increment
        $sqlBestCek = "SELECT id_best_seller FROM best_seller WHERE id_produk = '$id_produk' LIMIT 1";
        $qBestCek = mysqli_query($conn_gudang, $sqlBestCek);
        if (!$qBestCek) {
            throw new Exception('Gagal cek best_seller: ' . mysqli_error($conn_gudang));
        }

        if (mysqli_num_rows($qBestCek) > 0) {
            $sqlBestUpd = "UPDATE best_seller SET jumlah_terjual = jumlah_terjual + '$jumlah_beli' WHERE id_produk = '$id_produk'";
            $qBestUpd = mysqli_query($conn_gudang, $sqlBestUpd);
            if (!$qBestUpd) {
                throw new Exception('Gagal update best_seller: ' . mysqli_error($conn_gudang));
            }
        } else {
            $sqlBestIns = "INSERT INTO best_seller (id_produk, jumlah_terjual, periode) VALUES ('$id_produk', '$jumlah_beli', 'tahun')";
            $qBestIns = mysqli_query($conn_gudang, $sqlBestIns);
            if (!$qBestIns) {
                throw new Exception('Gagal insert best_seller: ' . mysqli_error($conn_gudang));
            }
        }


        // Jika semua 5 tahap sukses dilalui tanpa error, commit/permanenkan perubahan di kedua DB
        mysqli_commit($conn_penjualan);
        mysqli_commit($conn_gudang);

        // Alihkan kembali ke dashboard customer dengan pesan sukses
        header('Location: ../frontend/form-pesan.php?pesan=berhasil');

    } catch (Exception $e) {
        // Jika ada satu saja tahap yang gagal atau manipulasi stok, BATALKAN SEMUANYA
        mysqli_rollback($conn_penjualan);
        mysqli_rollback($conn_gudang);
        
        // Alihkan kembali dengan pesan error
        error_log("[CHECKOUT ERROR] " . $e->getMessage());
        header('Location: ../frontend/form-pesan.php?pesan=gagal&error=' . urlencode($e->getMessage()));
        exit();
    }

} else {
    die("Akses dilarang...");
}
?>