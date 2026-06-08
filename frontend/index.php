<?php
session_start();

if (!isset($_SESSION['email_pengguna'])) {
    header("Location: login-page.php");
    exit();
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

$query_pesanan = mysqli_query($conn_penjualan, "SELECT id_pengguna FROM pesanan");
$total_pesanan = mysqli_num_rows($query_pesanan);

$query_best = mysqli_query($conn_gudang, "SELECT id_produk, jumlah_terjual FROM best_seller ORDER BY jumlah_terjual DESC LIMIT 1");
$best_seller = mysqli_fetch_array($query_best);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Penjualan & Manajemen Stok</title>
    <link rel="stylesheet" href="design.css">
</head>

<body class="halaman-pemesanan">

    <header class="navbar-customer">
        <div class="logo-box-nav">logo</div>
        <div class="profile-container">
            <div class="user-profile">
                <span class="user-avatar">👤</span>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['nama_pengguna'] ?? 'Customer'); ?></span>
                <a class="btn-logout-inline" href="../backend/logout.php" title="Logout">🚪</a>
            </div>
        </div>
    </header>

    <main class="dashboard-content">

        <div class="top-row">
            <section class="card-section riwayat-section">
                <h3>Selamat Datang</h3>
                <p style="margin:0 0 15px 0; color:#444;">Dashboard Utama Sistem Integrasi Penjualan & Gudang</p>
                <div style="display:flex; gap:15px;">
                    <a href="form-pesan.php" style="display:flex; align-items:center; gap:8px; background:#e05300; color:white; padding:12px 20px; border-radius:15px; font-weight:bold; text-decoration:none; box-shadow:0 3px 0 #b04100;">
                        🛒 Buat Pesanan Baru
                    </a>
                    <a href="../backend/status-pesanan.php" style="display:flex; align-items:center; gap:8px; background:#dfba92; color:#000; padding:12px 20px; border-radius:15px; font-weight:bold; text-decoration:none; box-shadow:0 3px 0 #c9a070;">
                        📋 Riwayat & Status Pesanan
                    </a>
                </div>
            </section>

            <section class="card-section pesan-lagi-section">
                <h3>Best Seller</h3>
                <div class="menu-card-horizontal">
                    <div class="img-placeholder">🔥</div>
                    <div class="menu-detail">
                        <?php if ($best_seller): ?>
                            <h4>ID Produk: <?php echo (int)$best_seller['id_produk']; ?></h4>
                            <span class="sold-count">Produk Terlaris Bulan Ini</span>
                            <span class="menu-price">Terjual: <?php echo (int)$best_seller['jumlah_terjual']; ?> pcs</span>
                        <?php else: ?>
                            <h4>Belum Ada Data</h4>
                            <span class="sold-count">-</span>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>

        <section class="card-section" style="margin-bottom:30px;">
            <h3>📊 Ringkasan Aktivitas Sistem</h3>
            <div style="display:flex; gap:20px; margin-top:10px;">
                <div style="background:white; border-radius:15px; padding:20px 30px; flex:1; text-align:center;">
                    <div style="font-size:36px; font-weight:bold; color:#e05300;"><?php echo $total_pesanan; ?></div>
                    <div style="font-size:14px; color:#555; margin-top:5px;">Total Pesanan</div>
                </div>
                <div style="background:white; border-radius:15px; padding:20px 30px; flex:1; text-align:center;">
                    <div style="font-size:36px; font-weight:bold; color:#e05300;">
                        <?php echo $best_seller ? (int)$best_seller['jumlah_terjual'] : 0; ?>
                    </div>
                    <div style="font-size:14px; color:#555; margin-top:5px;">Terjual (Best Seller)</div>
                </div>
                <div style="background:white; border-radius:15px; padding:20px 30px; flex:2; display:flex; align-items:center;">
                    <p style="margin:0; font-size:12px; color:#888;">
                        *Sistem ini menggunakan koneksi 2 database terpisah (db_penjualan & db_gudang) secara sinkron melalui localhost XAMPP.
                    </p>
                </div>
            </div>
        </section>

    </main>

</body>
</html>