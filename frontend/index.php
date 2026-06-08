<?php
// 1. Mulai session wajib di setiap halaman yang diproteksi
session_start();

// 2. Cek apakah variabel session 'email_pengguna' TIDAK ADA (!isset)
// Artinya, dia belum melewati proses-login.php dengan sukses
if (!isset($_SESSION['email_pengguna'])) {
    // Tendang otomatis ke halaman login
    header("Location: login-page.php");
    exit(); // Hentikan eksekusi kode di bawahnya
}

if (isset($_SESSION['role_pengguna'])) {
    $role_aktif = strtolower($_SESSION['role_pengguna']);
    if ($role_aktif == 'admin') {
        header("Location: admin-page.php");
        exit();
    } else if ($role_aktif == 'supplier') {
        header("Location: supplier-page.php");
        exit();
    }
}

include("../backend/koneksi.php");

// Mengambil data ringkasan untuk dashboard (Opsional, agar halaman terlihat dinamis)
// 1. Hitung total pesanan dari DB Penjualan
$query_pesanan = mysqli_query($conn_penjualan, "SELECT id_pengguna FROM pesanan");
$total_pesanan = mysqli_num_rows($query_pesanan);

// 2. Ambil produk terlaris saat ini dari DB Gudang (Tahap 3 dari desainmu)
$query_best = mysqli_query($conn_gudang, "SELECT id_produk, jumlah_terjual FROM best_seller ORDER BY jumlah_terjual DESC LIMIT 1");
$best_seller = mysqli_fetch_array($query_best);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Penjualan & Manajemen Stok</title>
    <link rel="Stylesheet" href="design.css">
</head>

<body class="index-php">
    <nav class="top-logout-nav">
        <ul>
            <li><a class="btn-logout" href="../backend/logout.php">🚪 Logout</a></li>
        </ul>
    </nav>

    <header>
        <h1>Sistem Integrasi Penjualan & Gudang</h1>
        <p>Selamat Datang di Dashboard Utama Aplikasi</p>
    </header>

    <!-- Navigasi Menu ala Petanikode -->
    <nav>
        <ul>
            <li><a href="form-pesan.php">🛒 Buat Pesanan Baru</a></li>
            <li><a href="../backend/status-pesanan.php">📋 Lihat Riwayat & Status Pesanan</a></li>
        </ul>
    </nav>

    <main>
        <div class="info-box">
            <h3>📊 Ringkasan Aktivitas Sistem (Real-time):</h3>
            <ul>
                <li>Total Transaksi di Database Penjualan: <strong><?php echo $total_pesanan; ?> pesanan</strong></li>
                <li>
                    Produk Terlaris Bulan Ini (Database Gudang):
                    <strong>
                        <?php
                        if ($best_seller) {
                            echo "ID Produk: " . $best_seller['id_produk'] . " (Terjual: " . $best_seller['jumlah_terjual'] . ")";
                        } else {
                            echo "Belum ada data analisis best seller.";
                        }
                        ?>
                    </strong>
                </li>
            </ul>
        </div>

        <p class="sistem-note">
            *Sistem ini menggunakan koneksi 2 database terpisah (db_penjualan & db_gudang) secara sinkron melalui
            localhost XAMPP.
        </p>
    </main>

</body>

</html>