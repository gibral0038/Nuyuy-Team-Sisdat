<?php 
session_start();
include("koneksi.php");

$id_customer = (int)$_SESSION['id_pengguna'];

$sql = "SELECT pn.id_pesanan, p.nama_produk, dp.jumlah, pn.status_pesanan AS status 
        FROM pesanan pn 
        JOIN detail_pesanan dp ON pn.id_pesanan = dp.id_pesanan
        JOIN db_gudang.produk p ON dp.id_produk = p.id_produk
        WHERE pn.id_pengguna = '$id_customer'
        ORDER BY pn.id_pesanan DESC";

$query = mysqli_query($conn_penjualan, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>
    <link rel="stylesheet" href="../frontend/design.css">
    <style>
        .halaman-pemesanan {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .dashboard-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin: 30px auto;
            padding: 0 20px;
            width: 100%;
            max-width: 1200px;
            box-sizing: border-box;
        }
        .full-table-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }
        .scrollable-table-wrap {
            flex: 1;
            overflow-y: auto;
            border-radius: 15px;
            background: white;
        }
    </style>
</head>

<body class="halaman-pemesanan">

    <header class="navbar-customer">
        <div class="logo-box-nav">logo</div>
        <div class="profile-container">
            <div class="user-profile">
                <span class="user-avatar">👤</span>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['id_pengguna'] ?? 'Customer'); ?></span>
                <a class="btn-logout-inline" href="../backend/logout.php" title="Logout">🚪</a>
            </div>
        </div>
    </header>

    <main class="dashboard-content">

        <?php if(isset($_GET['status'])): ?>
            <div style="background:<?php echo $_GET['status'] == 'sukses' ? '#d4edda' : '#f8d7da'; ?>; border-radius:12px; padding:12px 20px; margin-bottom:20px; font-weight:bold; color:<?php echo $_GET['status'] == 'sukses' ? '#155724' : '#721c24'; ?>;">
                <?php echo $_GET['status'] == 'sukses' ? '✅ Pembelian sukses!' : '❌ Pembelian gagal!'; ?>
            </div>
        <?php endif; ?>

        <section class="card-section full-table-section">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0;">Riwayat Pemesanan Anda</h3>
                <a href="../frontend/form-pesan.php" style="background:#e05300; color:white; padding:10px 18px; border-radius:12px; font-weight:bold; text-decoration:none; font-size:14px; box-shadow:0 3px 0 #b04100;">
                    🛒 Tambah Pesanan Baru
                </a>
            </div>

            <div class="scrollable-table-wrap">
                <table class="modern-table" style="margin-top:0; width:100%;">
                    <thead>
                        <tr>
                            <th style="position:sticky; top:0; background:#f0d5bb; z-index:1;">No</th>
                            <th style="position:sticky; top:0; background:#f0d5bb; z-index:1;">ID Pesanan</th>
                            <th style="position:sticky; top:0; background:#f0d5bb; z-index:1;">Produk</th>
                            <th style="position:sticky; top:0; background:#f0d5bb; z-index:1;">Jumlah</th>
                            <th style="position:sticky; top:0; background:#f0d5bb; z-index:1;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($query && mysqli_num_rows($query) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while($pesanan = mysqli_fetch_array($query)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo (int)$pesanan['id_pesanan']; ?></td>
                                    <td><?php echo htmlspecialchars($pesanan['nama_produk']); ?></td>
                                    <td><?php echo (int)$pesanan['jumlah']; ?></td>
                                    <td>
                                        <span style="background:<?php echo $pesanan['status'] == 'selesai' ? '#d4edda' : ($pesanan['status'] == 'diproses' ? '#fff3cd' : '#f8d7da'); ?>; color:<?php echo $pesanan['status'] == 'selesai' ? '#155724' : ($pesanan['status'] == 'diproses' ? '#856404' : '#721c24'); ?>; padding:4px 10px; border-radius:20px; font-size:13px; font-weight:bold;">
                                            <?php echo htmlspecialchars($pesanan['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#666; padding:20px;">Belum ada pesanan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <p style="margin:12px 0 0 5px; font-size:13px; color:#666;">
                Total data: <?php echo $query ? mysqli_num_rows($query) : 0; ?>
            </p>
        </section>

    </main>

</body>
</html>