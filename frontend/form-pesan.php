<?php
session_start();
// Proteksi halaman: Tendang jika belum login atau bukan customer
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'customer') {
    header("Location: login-page.php");
    exit();
}

include("../backend/koneksi.php");
$id_customer = $_SESSION['id_pengguna'];

// 1. Ambil data Riwayat Pesanan Customer (maks 6 terbaru)
$queryRiwayat = mysqli_query(
    $conn_penjualan,
    "SELECT
        ps.id_pesanan,
        ps.status_pesanan,
        ps.tanggal_pesanan,
        p.nama_produk
     FROM pesanan ps
     JOIN detail_pesanan dp
        ON ps.id_pesanan = dp.id_pesanan
     JOIN db_gudang.produk p
        ON dp.id_produk = p.id_produk
     WHERE ps.id_pengguna = '$id_customer'
     ORDER BY ps.id_pesanan DESC
     LIMIT 6"
);
$riwayat_data = [];
while ($row = mysqli_fetch_array($queryRiwayat)) {
    $riwayat_data[] = $row;
}
$total_riwayat = count($riwayat_data);
$titik_tengah = ceil($total_riwayat / 2);

// 2. Ambil data Menu yang stoknya masih ada (sumber stok utama: gudang.stok_sekarang)
$queryKatalog = mysqli_query(
    $conn_gudang,
    "SELECT p.id_produk, p.nama_produk, p.deskripsi_produk, p.harga_produk, IFNULL(b.jumlah_terjual,0) AS terjual, g.stok_sekarang AS stok_produk 
     FROM produk p 
     LEFT JOIN gudang g ON g.id_produk = p.id_produk 
     LEFT JOIN best_seller b ON p.id_produk = b.id_produk 
     WHERE g.stok_sekarang > 0"
);

// 3. Ambil 1 pesanan terakhir untuk seksi "Pesan Lagi"
$queryPesanLagi = mysqli_query(
    $conn_penjualan,
    "SELECT d.id_produk, g.nama_produk, g.harga_produk 
     FROM detail_pesanan d 
     JOIN pesanan ps ON d.id_pesanan = ps.id_pesanan 
     JOIN (SELECT id_produk, nama_produk, harga_produk FROM db_gudang.produk) g ON d.id_produk = g.id_produk 
     WHERE ps.id_pengguna = '$id_customer' 
     ORDER BY ps.id_pesanan DESC LIMIT 1"
);
$pesan_lagi = mysqli_fetch_array($queryPesanLagi);
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer - Pemesanan</title>
    <link rel="stylesheet" href="design.css">
</head>

<body class="halaman-pemesanan">

    <div class="page-container">

    <header class="navbar-customer">
        <div class="logo-box-nav">
            <img src="../asset/Logo-web.png" alt="Gambar Saya">
        </div>
        
        <div class="search-container">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Cari menu...">
        </div>
        
        <div class="profile-container">
            <span class="cart-icon">🛒</span>
            <div class="user-profile">
                <span class="user-avatar">👤</span>
                <span class="user-name"><?php echo $_SESSION['nama_pengguna']; ?></span>
                <a class="btn-logout-inline" href="../backend/logout.php" title="Logout">🚪</a>
            </div>
        </div>
    </header>

    <main class="dashboard-content">
        
        <?php if (isset($_GET['pesan'])): ?>
            <div style="background: <?php echo ($_GET['pesan'] === 'berhasil') ? '#d4edda' : '#f8d7da'; ?>; border: 1px solid <?php echo ($_GET['pesan'] === 'berhasil') ? '#c3e6cb' : '#f5c6cb'; ?>; color: <?php echo ($_GET['pesan'] === 'berhasil') ? '#155724' : '#721c24'; ?>; padding: 12px; border-radius: 4px; margin-bottom: 15px;">
                <?php 
                    if ($_GET['pesan'] === 'berhasil') {
                        echo '✅ Pesanan berhasil dibuat! Stok sudah berkurang dan masuk ke riwayat.';
                    } else {
                        echo '❌ Gagal membuat pesanan. ';
                        if (isset($_GET['error'])) {
                            echo 'Error: ' . htmlspecialchars($_GET['error']);
                        }
                    }
                ?>
            </div>
        <?php endif; ?>
                    </div> <!-- .page-container -->

        
        <div class="top-row">
            <section class="card-section riwayat-section">
                <h3>Riwayat Pembelian</h3>
                <div class="riwayat-grid">
                    <div class="riwayat-col">
                        <?php 
                        for ($i = 0; $i < $titik_tengah; $i++) { 
                            $status = $riwayat_data[$i]['status_pesanan'];
                        ?>
                            <div class="riwayat-item">
                                <strong>Pesanan #<?php echo $riwayat_data[$i]['id_pesanan']; ?></strong>
                                <strong><?php echo $riwayat_data[$i]['nama_produk']; ?></strong>
                                <strong><?php echo $status; ?></strong>
                            </div>
                        <?php } ?>
                    </div>

                <div class="riwayat-col border-left">
                        <?php 
                        for ($i = $titik_tengah; $i < $total_riwayat; $i++) { 
                            $status = $riwayat_data[$i]['status_pesanan'];
                        ?>
                            <div class="riwayat-item">
                                <strong>Pesanan #<?php echo $riwayat_data[$i]['id_pesanan']; ?></strong>
                                <strong><?php echo $riwayat_data[$i]['nama_produk']; ?></strong>
                                <strong><?php echo $status; ?></strong>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>

            <section class="card-section pesan-lagi-section">
                <h3>Pesan Lagi</h3>
                <div class="menu-card-horizontal">
                    <div class="img-placeholder">🌄</div>
                    <div class="menu-detail">
                        <?php if ($pesan_lagi) { ?>
                            <h4><?php echo $pesan_lagi['nama_produk']; ?></h4>
                            <span class="sold-count">Pembelian Terakhir Anda</span>
                            <span class="menu-price">Rp <?php echo number_format($pesan_lagi['harga_produk'], 0, ',', '.'); ?></span>
                        <?php } else { ?>
                            <h4>Belum Ada Riwayat</h4>
                            <span class="sold-count">-</span>
                            <span class="menu-price">Rp 0</span>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </div>

        <section class="katalog-menu-section">
            <h3 class="section-title">Menu</h3>
            
            <div class="menu-grid">
                <?php 
                // Looping data menu dari database gudang
                while ($menu = mysqli_fetch_array($queryKatalog)) { 
                ?>
                <div class="menu-card">
                    <form action="../backend/proses-pesan.php" method="POST">
                        <div class="img-placeholder">🌄</div>
                        <div class="menu-detail">
                            <input type="hidden" name="id_produk" value="<?php echo $menu['id_produk']; ?>" />
                            
                            <h4><?php echo $menu['nama_produk']; ?></h4>
                            <span class="sold-count">Terjual <?php echo $menu['terjual']; ?> porsi (Sisa: <?php echo $menu['stok_produk']; ?>)</span>
                            <span class="menu-price">Rp <?php echo number_format($menu['harga_produk'], 0, ',', '.'); ?></span>
                            
                            <div class="action-buy">
                                <input type="number" name="jumlah_beli" value="1" min="1" max="<?php echo $menu['stok_produk']; ?>" required class="input-qty" onchange="updateTotal(this, <?php echo $menu['harga_produk']; ?>)" />
                                <span class="total-price">Total: <strong>Rp <?php echo number_format($menu['harga_produk'], 0, ',', '.'); ?></strong></span>
                                <button type="submit" name="checkout" class="btn-buy">Beli</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
        </section>

    </main>

<script>
function updateTotal(input, hargaSatuan) {
    const jumlah = parseInt(input.value) || 1;
    const total = hargaSatuan * jumlah;
    const span = input.parentElement.querySelector('.total-price strong');
    if (span) {
        span.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }
}
</script>

</body>

</html>