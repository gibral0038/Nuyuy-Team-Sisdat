<!DOCTYPE html>
<html>

<head>
    <title>Form Pemesanan Menu</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="halaman-pemesanan">
    <h3>Form Pemesanan Menu</h3>
    <!-- Data dikirim ke file proses-pesan.php -->
    <form action="../backend/proses-pesan.php" method="POST">
        <p>
            <label for="id_produk">ID Produk (Menu): </label>
            <input type="number" name="id_produk" placeholder="Masukkan ID Produk" required />
        </p>
        <p>
            <label for="jumlah_beli">Jumlah: </label>
            <input type="number" name="jumlah_beli" placeholder="Jumlah porsi" required />
        </p>
        <p>
            <label for="total_bayar">Total Harga (Simulasi): </label>
            <input type="number" name="total_bayar" placeholder="Total Bayar" required />
        </p>
        <p>
            <input type="submit" value="Selesaikan Pembayaran" name="checkout" />
        </p>
    </form>
</body>

</html>