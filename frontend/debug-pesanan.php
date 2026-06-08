<?php
session_start();
include("../backend/koneksi.php");

if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'customer') {
    die("Hanya customer yang boleh akses.");
}

$id_customer = (int)$_SESSION['id_pengguna'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Pesanan</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 15px; margin: 10px 0; border-radius: 4px; }
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h1>🔍 DEBUG SISTEM PESANAN</h1>

<div class="section">
    <h3>📊 Data Customer</h3>
    <p>ID: <?php echo $id_customer; ?></p>
    <p>Nama: <?php echo htmlspecialchars($_SESSION['nama_pengguna']); ?></p>
</div>

<div class="section">
    <h3>📦 Data Produk di Gudang</h3>
    <table>
        <tr>
            <th>ID Produk</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stok Sekarang</th>
            <th>Status</th>
        </tr>
        <?php
        $qProduk = mysqli_query($conn_gudang, "
            SELECT p.id_produk, p.nama_produk, p.harga_produk, g.stok_sekarang
            FROM produk p
            LEFT JOIN gudang g ON g.id_produk = p.id_produk
            ORDER BY p.id_produk DESC
        ");
        
        if ($qProduk) {
            while ($row = mysqli_fetch_assoc($qProduk)) {
                $stok = (int)($row['stok_sekarang'] ?? 0);
                $status = $stok > 0 ? '<span class="success">✅ Ada</span>' : '<span class="error">❌ Kosong/Null</span>';
                echo "<tr>
                    <td>{$row['id_produk']}</td>
                    <td>{$row['nama_produk']}</td>
                    <td>Rp " . number_format($row['harga_produk'], 0, ',', '.') . "</td>
                    <td>{$stok}</td>
                    <td>{$status}</td>
                </tr>";
            }
        }
        ?>
    </table>
</div>

<div class="section">
    <h3>🛒 Riwayat Pesanan Customer</h3>
    <table>
        <tr>
            <th>ID Pesanan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Detail Produk</th>
        </tr>
        <?php
        $qPesanan = mysqli_query($conn_penjualan, "
            SELECT pn.id_pesanan, pn.tanggal_pesanan, pn.status_pesanan,
                   dp.id_produk, dp.jumlah, dp.harga_total,
                   gd.nama_produk
            FROM pesanan pn
            LEFT JOIN detail_pesanan dp ON pn.id_pesanan = dp.id_pesanan
            LEFT JOIN db_gudang.produk gd ON dp.id_produk = gd.id_produk
            WHERE pn.id_pengguna = '$id_customer'
            ORDER BY pn.tanggal_pesanan DESC
        ");
        
        if ($qPesanan && mysqli_num_rows($qPesanan) > 0) {
            while ($row = mysqli_fetch_assoc($qPesanan)) {
                echo "<tr>
                    <td>{$row['id_pesanan']}</td>
                    <td>{$row['tanggal_pesanan']}</td>
                    <td>{$row['status_pesanan']}</td>
                    <td>{$row['nama_produk']} (x{$row['jumlah']}) = Rp " . number_format($row['harga_total'], 0, ',', '.') . "</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align:center;' class='error'>Belum ada pesanan</td></tr>";
        }
        ?>
    </table>
</div>

<div class="section">
    <h3>✅ Hasil Cek Error Database</h3>
    <?php
    $errors = [];
    
    // Cek koneksi
    if (!$conn_penjualan) $errors[] = "❌ Gagal koneksi ke db_penjualan";
    else $errors[] = "✅ db_penjualan OK";
    
    if (!$conn_gudang) $errors[] = "❌ Gagal koneksi ke db_gudang";
    else $errors[] = "✅ db_gudang OK";
    
    // Cek table produk
    $qCheck = mysqli_query($conn_gudang, "SELECT COUNT(*) as cnt FROM produk");
    if ($qCheck) {
        $row = mysqli_fetch_assoc($qCheck);
        $errors[] = "✅ Tabel produk ada, {$row['cnt']} produk";
    } else {
        $errors[] = "❌ Error tabel produk: " . mysqli_error($conn_gudang);
    }
    
    // Cek table gudang
    $qCheck = mysqli_query($conn_gudang, "SELECT COUNT(*) as cnt FROM gudang");
    if ($qCheck) {
        $row = mysqli_fetch_assoc($qCheck);
        $errors[] = "✅ Tabel gudang ada, {$row['cnt']} entry";
    } else {
        $errors[] = "❌ Error tabel gudang: " . mysqli_error($conn_gudang);
    }
    
    // Cek table pesanan
    $qCheck = mysqli_query($conn_penjualan, "SELECT COUNT(*) as cnt FROM pesanan");
    if ($qCheck) {
        $row = mysqli_fetch_assoc($qCheck);
        $errors[] = "✅ Tabel pesanan ada, {$row['cnt']} pesanan total";
    } else {
        $errors[] = "❌ Error tabel pesanan: " . mysqli_error($conn_penjualan);
    }
    
    foreach ($errors as $err) {
        echo "<p>$err</p>";
    }
    ?>
</div>

<p style="margin-top: 20px;">
    <a href="form-pesan.php" style="padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">← Kembali ke Form Pesan</a>
</p>

</body>
</html>
