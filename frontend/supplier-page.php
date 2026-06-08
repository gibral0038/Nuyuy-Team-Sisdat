<?php
session_start();
if (!isset($_SESSION['email_pengguna']) || $_SESSION['role_pengguna'] !== 'supplier') {
    header("Location: login-page.php");
    exit();
}

$id_supplier_aktif = (int)$_SESSION['id_pengguna'];
include("../backend/koneksi.php");

$pesan = $_GET['pesan'] ?? '';
$err = $_GET['err'] ?? '';

$queryProduk = mysqli_query(
    $conn_gudang,
    "SELECT pd.id_produk, pd.nama_produk, pd.deskripsi_produk, pd.harga_produk,
            g.stok_sekarang
     FROM produk pd
     JOIN supplier sp ON pd.id_supplier = sp.id_supplier
     LEFT JOIN gudang g ON g.id_produk = pd.id_produk
     WHERE pd.id_supplier = '$id_supplier_aktif'
     ORDER BY pd.id_produk DESC"
);

$queryBest = mysqli_query(
    $conn_gudang,
    "SELECT pd.id_produk, pd.nama_produk, COALESCE(bs.jumlah_terjual,0) AS jumlah_terjual
     FROM produk pd
     LEFT JOIN best_seller bs ON bs.id_produk = pd.id_produk
     WHERE pd.id_supplier = '$id_supplier_aktif'
     ORDER BY jumlah_terjual DESC
     LIMIT 4"
);

$arr = [];
$qArr = mysqli_query(
    $conn_gudang,
    "SELECT pd.id_produk, pd.nama_produk, pd.deskripsi_produk, pd.harga_produk,
            COALESCE(g.stok_sekarang,0) AS stok_sekarang
     FROM produk pd
     LEFT JOIN gudang g ON g.id_produk = pd.id_produk
     WHERE pd.id_supplier = '$id_supplier_aktif'"
);
while ($p = mysqli_fetch_array($qArr)) {
    $arr[] = [
        'id_produk'        => (int)$p['id_produk'],
        'nama_produk'      => $p['nama_produk'],
        'deskripsi_produk' => $p['deskripsi_produk'],
        'harga_produk'     => (int)$p['harga_produk'],
        'stok_sekarang'    => (int)$p['stok_sekarang'],
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Supplier - Manajemen Produk</title>
    <link rel="stylesheet" href="design.css?v=1.4">
</head>
<body class="halaman-supplier">

<div class="page-container">

<header class="navbar-supplier">
    <div class="logo-box-nav">logo</div>
    <div class="navbar-right-side">
        <span class="truck-icon">🚚</span>
        <div class="user-profile">
            <span class="user-avatar">👤</span>
            <span class="user-name"><?php echo htmlspecialchars($_SESSION['nama_pengguna'] ?? 'Username'); ?></span>
            <a class="btn-logout-inline" href="../backend/logout.php" title="Logout">🚪</a>
        </div>
    </div>
</header>

<main class="supplier-dashboard-content">

    <section class="supplier-column">
        <h2>PRODUK & STOK</h2>

        <div class="card-list-box">
            <?php if ($pesan): ?>
                <div class="info-box" style="margin-top:0; border-left-color:#e05300;">
                    <?php
                        if ($pesan === 'tambah_produk_sukses') echo 'Produk berhasil ditambahkan.';
                        elseif ($pesan === 'tambah_produk_gagal') echo 'Gagal menambahkan produk.';
                        elseif ($pesan === 'tambah_stok_sukses') echo 'Stok berhasil ditambahkan.';
                        elseif ($pesan === 'tambah_stok_gagal') echo 'Gagal menambah stok.';
                        else echo htmlspecialchars($pesan);
                    ?>
                    <?php if ($err): ?>
                        <div style="margin-top:6px; color:#b00020; font-size:12px;">Error: <?php echo htmlspecialchars($err); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="table-header-row" style="padding-left:0;">
                <span>Nama Produk</span>
                <span>Stok</span>
                <span>Aksi</span>
            </div>

            <div style="display:flex; flex-direction:column; gap:12px;">
                <?php
                $hasData = false;
                while ($row = mysqli_fetch_array($queryProduk)) {
                    $hasData = true;
                    $stok = (int)($row['stok_sekarang'] ?? 0);
                    $idProduk = (int)$row['id_produk'];
                ?>
                    <div class="item-list-row align-center" style="gap:12px;">
                        <div class="img-mini-placeholder">📦</div>
                        <div class="item-row-detail" style="gap:2px;">
                            <div class="row-title-flex">
                                <span class="item-name"><?php echo htmlspecialchars($row['nama_produk']); ?></span>
                                <span class="item-qty">ID <?php echo $idProduk; ?></span>
                            </div>
                            <div style="font-size:13px; color:#333; font-weight:bold;">Stok Saat Ini: <?php echo $stok; ?></div>
                        </div>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <button class="btn-edit" type="button" data-edit="<?php echo $idProduk; ?>" onclick="window.bbSupplierTambahStok(this)">⚙️</button>
                            <form action="../backend/proses-hapus-produk.php" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                <input type="hidden" name="id_produk" value="<?php echo $idProduk; ?>" />
                                <button class="btn-delete" type="submit" name="hapus_produk">🗑️</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>

                <?php if (!$hasData): ?>
                    <div class="item-list-row" style="justify-content:center;">
                        <span style="color:#666; font-weight:bold;">Belum ada produk.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="supplier-column">
        <h2>TAMBAH PRODUK</h2>

        <div class="card-list-box">
            <h3 style="margin:0 0 10px 0; font-size:18px;">Tambah Produk</h3>
            <form action="../backend/proses-tambah-produk.php" method="POST" class="crud-form">
                <input type="hidden" name="tambah_produk" value="1" />

                <label style="font-weight:bold; font-size:13px;">Nama Produk</label>
                <input name="nama_produk" required class="crud-input" />

                <label style="font-weight:bold; font-size:13px;">Deskripsi</label>
                <input name="deskripsi_produk" required class="crud-input" />

                <label style="font-weight:bold; font-size:13px;">Harga</label>
                <input type="number" name="harga_produk" min="0" required class="crud-input" />

                <label style="font-weight:bold; font-size:13px;">Stok Awal</label>
                <input type="number" name="stok_awal" min="0" required class="crud-input" />

                <button type="submit" class="btn-buy" style="margin-top:12px;">Tambah</button>
            </form>
        </div>

        <div class="card-list-box" style="margin-top:25px;">
            <div id="bbSupplierTambahStokContainer" style="display:none;">
                <h3 style="margin:0 0 10px 0; font-size:18px;">Tambah Stok</h3>
                <p id="stok_nama_produk_label" style="font-weight:bold; font-size:14px; margin:0 0 8px 0;"></p>
                <form action="../backend/proses-tambah-stok-popup.php" method="POST" class="crud-form">
                    <input type="hidden" name="tambah_stok_popup" value="1" />
                    <input type="hidden" id="stok_id_produk" name="id_produk" value="" />

                    <label style="font-weight:bold; font-size:13px;">Jumlah Tambah</label>
                    <input type="number" name="jumlah_tambah" min="1" required class="crud-input" />

                    <button type="submit" class="btn-auth" style="margin-top:12px;">Tambah Stok</button>
                </form>

                <div style="font-size:12px; color:#666; margin-top:10px;">Masukkan jumlah stok lalu simpan.</div>
            </div>
        </div>
    </section>

    <section class="supplier-column flex-column-gap">
        <div class="sub-target-box">
            <h2>Top Terjual (Best Seller)</h2>
            <?php if (mysqli_num_rows($queryBest) > 0): ?>
                <?php while ($b = mysqli_fetch_array($queryBest)): ?>
                    <div class="item-list-row align-center small-padding" style="margin-bottom:10px;">
                        <div class="img-mini-placeholder icon-small">🔥</div>
                        <span class="report-menu-name"><?php echo htmlspecialchars($b['nama_produk']); ?></span>
                        <span class="report-total"><?php echo (int)$b['jumlah_terjual']; ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="color:#666; font-weight:bold;">Belum ada data penjualan.</div>
            <?php endif; ?>
        </div>

        <div class="sub-laporan-box">
            <h2>Laporan</h2>
            <div class="table-header-row border-bottom">
                <span>Menu</span>
                <span>Penjualan</span>
            </div>
            <?php
                $qLap = mysqli_query(
                    $conn_gudang,
                    "SELECT pd.nama_produk, COALESCE(bs.jumlah_terjual,0) AS jumlah_terjual
                     FROM produk pd
                     LEFT JOIN best_seller bs ON bs.id_produk = pd.id_produk
                     WHERE pd.id_supplier = '$id_supplier_aktif'
                     ORDER BY jumlah_terjual DESC
                     LIMIT 4"
                );
                if ($qLap) {
                    while ($l = mysqli_fetch_array($qLap)) {
            ?>
                        <div class="item-list-row align-center small-padding">
                            <div class="img-mini-placeholder icon-small">📊</div>
                            <span class="report-menu-name"><?php echo htmlspecialchars($l['nama_produk']); ?></span>
                            <span class="report-total"><?php echo (int)$l['jumlah_terjual']; ?></span>
                        </div>
            <?php } }
            ?>
        </div>
    </section>

</main>

<script>
    window.bbSupplierProducts = <?php echo json_encode($arr); ?>;

    window.bbSupplierTambahStok = function (btnEl) {
        const id = btnEl?.getAttribute('data-edit');
        if (!id) { alert('ID tidak ditemukan'); return; }

        const item = window.bbSupplierProducts.find(
            (x) => String(x.id_produk) === String(id)
        );
        if (!item) { alert('Produk tidak ditemukan: ' + id); return; }

        // Isi hidden input id produk
        document.getElementById('stok_id_produk').value = item.id_produk;

        // Tampilkan nama produk di label
        const label = document.getElementById('stok_nama_produk_label');
        if (label) label.textContent = item.nama_produk;

        // Tampilkan container tambah stok
        const container = document.getElementById('bbSupplierTambahStokContainer');
        if (!container) { alert('Container tidak ditemukan!'); return; }

        container.style.display = 'block';
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };
</script>

    </div> <!-- .page-container -->

</body>
</html>