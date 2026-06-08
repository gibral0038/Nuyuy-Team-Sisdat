<?php
include("../backend/koneksi.php");

session_start();
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'customer') {
    header("Location: login-page.php");
    exit();
}

$id_customer = (int)$_SESSION['id_pengguna'];

// status_pesanan bisa tampil untuk pending/diproses/selesai
$sql = "SELECT pn.id_pesanan, pn.tanggal_pesanan, pn.status_pesanan,
               dp.id_produk, dp.jumlah
        FROM pesanan pn
        JOIN detail_pesanan dp ON pn.id_pesanan = dp.id_pesanan
        WHERE pn.id_pengguna = '$id_customer'
        ORDER BY pn.tanggal_pesanan DESC, pn.id_pesanan DESC";

$query = mysqli_query($conn_penjualan, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>
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
    <section class="card-section">
        <h3>Riwayat Pesanan (Status)</h3>

        <div style="overflow:auto;">
            <table border="1" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>ID Produk</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?php echo (int)$row['id_pesanan']; ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal_pesanan'] ?? ''); ?></td>
                                <td><?php echo (int)$row['id_produk']; ?></td>
                                <td><?php echo (int)$row['jumlah']; ?></td>
                                <td><b><?php echo htmlspecialchars($row['status_pesanan'] ?? ''); ?></b></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; color:#666; font-weight:bold;">Belum ada pesanan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

</body>
</html>

