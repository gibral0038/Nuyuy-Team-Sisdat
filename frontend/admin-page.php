<?php
session_start();
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'admin') {
    header("Location: login-page.php");
    exit();
}
include("../backend/koneksi.php");

// Query mengambil semua data produk gabung dengan supplier
$queryProduk = mysqli_query($conn_gudang, "SELECT * FROM produk pd JOIN supplier sp ON pd.id_supplier = sp.id_supplier");
$total_data = mysqli_num_rows($queryProduk);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman 5 - admin main</title>
    <link rel="stylesheet" href="design.css?v=1.4">
</head>
<body class="halaman-admin">

    <header class="navbar-admin">
        <div class="logo-box-nav">logo</div>
        <div class="navbar-right-side">
            <span class="admin-icon">🏢</span>
            <div class="user-profile">
                <span class="user-avatar">👤</span>
                <span class="user-name"><?php echo $_SESSION['nama_pengguna'] ?? 'Admin'; ?></span>
                <a class="btn-logout-inline" href="../backend/logout.php" title="Logout">🚪</a>
            </div>
        </div>
    </header>

    <main class="admin-dashboard-content">
        
        <section class="admin-left-column">
            <h2>Stok</h2>
            <div class="card-list-box">
                
                <?php
                // Kita gunakan query ulang atau reset pointer untuk ringkasan stok di kiri
                if ($total_data > 0) {
                    // Loop pertama untuk visual progress bar stok di kiri
                    while ($row_stok = mysqli_fetch_array($queryProduk)) {
                        $stok = $row_stok['stok_produk'];
                ?>
                <div class="item-list-row">
                    <div class="img-mini-placeholder">🌄</div>
                    <div class="item-row-detail">
                        <div class="row-title-flex">
                            <span class="item-name"><?php echo $row_stok['nama_produk']; ?></span>
                            <span class="item-qty">jumlah</span>
                        </div>
                        <progress value="<?php echo $stok; ?>" max="100"></progress>
                    </div>
                </div>
                <?php 
                    }
                    // Kembalikan pointer database ke baris awal agar bisa di-looping lagi di tabel kanan
                    mysqli_data_seek($queryProduk, 0); 
                } else {
                    // Dummy item jika database kosong supaya visualnya terisi penuh pas mockup
                    for ($i=1; $i<=3; $i++) {
                ?>
                <div class="item-list-row">
                    <div class="img-mini-placeholder">🌄</div>
                    <div class="item-row-detail">
                        <div class="row-title-flex">
                            <span class="item-name">nama bahan</span>
                            <span class="item-qty">jumlah</span>
                        </div>
                        <progress value="<?php echo ($i == 1) ? 90 : (($i == 2) ? 20 : 65); ?>" max="100"></progress>
                    </div>
                </div>
                <?php
                    }
                }
                ?>

            </div>
        </section>

        <section class="admin-right-column">
            <h2>Daftar Menu Tersedia</h2>
            
            <div class="table-container-box">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Nama Supplier</th>
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if ($total_data > 0) {
                            while ($produk = mysqli_fetch_array($queryProduk)) {
                                echo "<tr>";
                                echo "<td>" . $produk['nama_supplier'] . "</td>";
                                echo "<td>" . $produk['id_produk'] . "</td>";
                                echo "<td>" . $produk['nama_produk'] . "</td>";
                                echo "<td>Rp " . number_format($produk['harga_produk'], 0, ',', '.') . "</td>";
                                echo "<td>" . $produk['stok_produk'] . "</td>";
                                echo "<td>
                                        <div class='action-buttons'>
                                            <button class='btn-edit'>⚙️</button>
                                            <button class='btn-delete'>🗑️</button>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            // Dummy data tabel biar persis seperti sketsa gambar mockup kamu
                            for ($i=1; $i<=3; $i++) {
                                echo "<tr>";
                                echo "<td>nama supplier</td>";
                                echo "<td>id produk</td>";
                                echo "<td>nama produk</td>";
                                echo "<td>harga</td>";
                                echo "<td>stok</td>";
                                echo "<td>
                                        <div class='action-buttons'>
                                            <button class='btn-edit'>⚙️</button>
                                            <button class='btn-delete'>🗑️</button>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                        ?>

                    </tbody>
                </table>
                
                <div class="table-footer-summary">
                    <p>Total data: <strong><?php echo $total_data; ?></strong></p>
                </div>
            </div>
        </section>

    </main>

</body>
</html>