<?php
    session_start();
    if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'supplier') {
        header("Location: login-page.php");
        exit();
    }
    $id_supplier_aktif = $_SESSION['id_pengguna'];
    include ("../backend/koneksi.hpp");
    $queryProduk = mysqli_query($conn_gudang, "SELECT * FROM produk pd JOIN supplier sp ON pd.id_supplier = sp.id_supplier");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Supplier - Daftar Menu</title>
</head>
<body>
    <h3>Daftar Menu Tersedia</h3>

    <br>

    <table border="1">
    <thead>
        <tr>
            <th>ID Produk</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok Tersedia</th>
        </tr>
    </thead>
    <tbody>

        <?php
        // Tampilkan semua data menggunakan looping while (Standar Petanikode)
        while($produk = mysqli_fetch_array($queryProduk)){
            echo "<tr>";
            echo "<td>".$produk['id_produk']."</td>";
            echo "<td>".$produk['nama_produk']."</td>";
            echo "<td>".$produk['harga_produk']."</td>";
            echo "<td>".$produk['stok_produk']."</td>";
            echo "</tr>";
        }
        ?>

    </tbody>
    </table>

    <p>Total data: <?php echo mysqli_num_rows($queryProduk) ?></p>