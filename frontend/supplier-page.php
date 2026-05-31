<?php
session_start();
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'supplier') {
    header("Location: login-page.php");
    exit();
}
$id_supplier_aktif = $_SESSION['id_pengguna'];
include("../backend/koneksi.php"); // Memperbaiki typo .hpp menjadi .php

// Query data produk milik supplier
$queryProduk = mysqli_query($conn_gudang, "SELECT * FROM produk pd JOIN supplier sp ON pd.id_supplier = sp.id_supplier");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman 4 - supplier main</title>
    <link rel="stylesheet" href="design.css?v=1.3">
</head>
<body class="halaman-supplier">

    <header class="navbar-supplier">
        <div class="logo-box-nav">logo</div>
        <div class="navbar-right-side">
            <span class="truck-icon">🚚</span>
            <div class="user-profile">
                <span class="user-avatar">👤</span>
                <span class="user-name"><?php echo $_SESSION['nama_pengguna'] ?? 'Username'; ?></span>
            </div>
        </div>
    </header>

    <main class="supplier-dashboard-content">
        
        <section class="supplier-column">
            <h2>Bahan Baku</h2>
            <div class="card-list-box">
                
                <?php
                if (mysqli_num_rows($queryProduk) > 0) {
                    while ($produk = mysqli_fetch_array($queryProduk)) {
                        $stok = $produk['stok_produk'];
                        $max_kapasitas = 100; // Standar maksimal progress bar
                ?>
                <div class="item-list-row">
                    <div class="img-mini-placeholder">🌄</div>
                    <div class="item-row-detail">
                        <div class="row-title-flex">
                            <span class="item-name"><?php echo $produk['nama_produk']; ?></span>
                            <span class="item-qty">jumlah</span>
                        </div>
                        <progress value="<?php echo $stok; ?>" max="<?php echo $max_kapasitas; ?>"></progress>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    // Jika database kosong, ini contoh dummy agar layout tetap terisi sesuai mockup gambar
                    for ($i=1; $i<=6; $i++) {
                        $dummy_values = [90, 15, 60, 50, 95, 40];
                ?>
                <div class="item-list-row">
                    <div class="img-mini-placeholder">🌄</div>
                    <div class="item-row-detail">
                        <div class="row-title-flex">
                            <span class="item-name">nama bahan</span>
                            <span class="item-qty">jumlah</span>
                        </div>
                        <progress value="<?php echo $dummy_values[$i-1]; ?>" max="100"></progress>
                    </div>
                </div>
                <?php
                    }
                }
                ?>

            </div>
        </section>

        <section class="supplier-column">
            <h2>Pesanan</h2>
            <div class="card-list-box">
                <div class="table-header-row">
                    <span>nama pemesan</span>
                    <span>Item</span>
                    <span>Status</span>
                </div>
                
                <?php for($i=1; $i<=6; $i++): ?>
                <div class="item-list-row align-center">
                    <div class="img-mini-placeholder">🌄</div>
                    <div class="pesan-row-detail">
                        <span class="buyer-name">nama pemesan</span>
                        <span class="buyer-item">pesanan jumlah</span>
                    </div>
                    <span class="status-tag">status</span>
                </div>
                <?php endfor; ?>

            </div>
        </section>

        <section class="supplier-column flex-column-gap">
            
            <div class="sub-target-box">
                <h2>Target</h2>
                <div class="target-info">
                    <span class="target-price">Rp. ........... / Rp ...........</span>
                    <span class="target-percentage">...%</span>
                </div>
                <progress value="65" max="100" class="progress-target"></progress>
            </div>

            <div class="sub-laporan-box">
                <h2>Laporan</h2>
                <div class="table-header-row border-bottom">
                    <span>Menu</span>
                    <span>Penjualan</span>
                </div>

                <?php for($i=1; $i<=4; $i++): ?>
                <div class="item-list-row align-center small-padding">
                    <div class="img-mini-placeholder icon-small">🌄</div>
                    <span class="report-menu-name">nama menu</span>
                    <span class="report-total">total</span>
                </div>
                <?php endfor; ?>
            </div>

        </section>

    </main>

</body>
</html>