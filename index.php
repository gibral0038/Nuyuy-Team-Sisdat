<?php 
// 1. Mulai session wajib di setiap halaman yang diproteksi
session_start();

// 2. Cek apakah variabel session 'id_pengguna' TIDAK ADA (!isset)
// Artinya, dia belum melewati proses-login.php dengan sukses
if (!isset($_SESSION['id_pengguna'])) {
    // Tendang otomatis ke halaman login
    header("Location: login-page.php");
    exit(); // Hentikan eksekusi kode di bawahnya
}

include("koneksi.php"); 

// Mengambil data ringkasan untuk dashboard (Opsional, agar halaman terlihat dinamis)
// 1. Hitung total pesanan dari DB Penjualan
$query_pesanan = mysqli_query($conn_penjualan, "SELECT id FROM pesanan");
$total_pesanan = mysqli_num_rows($query_pesanan);

// 2. Ambil produk terlaris saat ini dari DB Gudang (Tahap 3 dari desainmu)
$query_best = mysqli_query($conn_gudang, "SELECT id_produk, total_terjual FROM best_seller ORDER BY total_terjual DESC LIMIT 1");
$best_seller = mysqli_fetch_array($query_best);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Penjualan & Manajemen Stok</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        header { margin-bottom: 20px; }
        nav ul { list-style-type: none; padding: 0; }
        nav ul li { display: inline; margin-right: 15px; }
        nav ul li a { text-decoration: none; color: #007BFF; font-weight: bold; }
        nav ul li a:hover { text-decoration: underline; }
        .info-box { background-color: #f4f4f4; padding: 15px; border-radius: 5px; margin-top: 20px; border-left: 5px solid #28a745; }
    </style>
</head>
<nav>
    <ul>
        <li><a href="form-pesan.php">🛒 Buat Pesanan Baru</a></li>
        <li><a href="status-pesanan.php">📋 Lihat Riwayat & Status Pesanan</a></li>
        
        <li><a href="logout.php" style="color: red; font-weight: bold;">🚪 Keluar (Logout)</a></li>
    </ul>
</nav>
<body>

    <header>
        <h1>Sistem Integrasi Penjualan & Gudang</h1>
        <p>Selamat Datang di Dashboard Utama Aplikasi</p>
    </header>

    <!-- Navigasi Menu ala Petanikode -->
    <nav>
        <ul>
            <li><a href="form-pesan.php">🛒 Buat Pesanan Baru</a></li>
            <li><a href="status-pesanan.php">📋 Lihat Riwayat & Status Pesanan</a></li>
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
                            echo "ID Produk: " . $best_seller['id_produk'] . " (Terjual: " . $best_seller['total_terjual'] . ")";
                        } else {
                            echo "Belum ada data analisis best seller.";
                        }
                        ?>
                    </strong>
                </li>
            </ul>
        </div>
        
        <p style="color: gray; font-size: 12px; margin-top: 30px;">
            *Sistem ini menggunakan koneksi 2 database terpisah (db_penjualan & db_gudang) secara sinkron melalui localhost XAMPP.
        </p>
    </main>

</body>
</html>