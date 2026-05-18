<?php include("koneksi.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Pesanan Pelanggan</title>
</head>
<body>
    <h3>Riwayat Pemesanan Anda</h3>

    <nav>
        <a href="form-pesan.php">[+] Tambah Pesanan Baru</a>
    </nav>

    <br>

    <table border="1">
    <thead>
        <tr>
            <th>ID Pesanan</th>
            <th>ID Produk</th>
            <th>Jumlah</th>
            <th>Status Pesanan</th>
        </tr>
    </thead>
    <tbody>

        <?php
        // Mengambil data dari tabel detail_pesanan & pesanan di DB Penjualan
        $sql = "SELECT pesanan.id, detail_pesanan.id_produk, detail_pesanan.jumlah, pesanan.status 
                FROM pesanan 
                JOIN detail_pesanan ON pesanan.id = detail_pesanan.id_pesanan";
        
        $query = mysqli_query($conn_penjualan, $sql);

        // Tampilkan semua data menggunakan looping while (Standar Petanikode)
        while($pesanan = mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>".$pesanan['id']."</td>";
            echo "<td>".$pesanan['id_produk']."</td>";
            echo "<td>".$pesanan['jumlah']."</td>";
            echo "<td><b>".$pesanan['status']."</b></td>";
            echo "</tr>";
        }
        ?>

    </tbody>
    </table>

    <p>Total data: <?php echo mysqli_num_rows($query) ?></p>

    <?php if(isset($_GET['status'])): ?>
    <p>
        <?php
            if($_GET['status'] == 'sukses'){
                echo "<h3>Pembelian sukses dan stok gudang otomatis terpotong!</h3>";
            } else {
                echo "<h3>Pembelian gagal! Transaksi dibatalkan.</h3>";
            }
        ?>
    </p>
    <?php endif; ?>

</body>
</html>