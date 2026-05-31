<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasboard Customer - Pemesanan</title>
    <link rel="stylesheet" href="design.css">
</head>

<body class="halaman-pemesanan">

    <header class="navbar-customer">
        <div class="logo-box-nav">logo</div>
        
        <div class="search-container">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Cari menu...">
        </div>
        
        <div class="profile-container">
            <span class="cart-icon">🛒</span>
            <div class="user-profile">
                <span class="user-avatar">👤</span>
                <span class="user-name">Username</span>
            </div>
        </div>
    </header>

    <main class="dashboard-content">
        
        <div class="top-row">
            <section class="card-section riwayat-section">
                <h3>Riwayat Pembelian</h3>
                <div class="riwayat-grid">
                    <div class="riwayat-col">
                        <div class="riwayat-item"><span>nama pesanan</span> <strong>total Penjualan</strong></div>
                        <div class="riwayat-item"><span>nama pesanan</span> <strong>total Penjualan</strong></div>
                        <div class="riwayat-item"><span>nama pesanan</span> <strong>total Penjualan</strong></div>
                    </div>
                    <div class="riwayat-col border-left">
                        <div class="riwayat-item"><span>nama pesanan</span> <strong>total Penjualan</strong></div>
                        <div class="riwayat-item"><span>nama pesanan</span> <strong>total Penjualan</strong></div>
                        <div class="riwayat-item"><span>nama pesanan</span> <strong>total Penjualan</strong></div>
                    </div>
                </div>
            </section>

            <section class="card-section pesan-lagi-section">
                <h3>Pesan Lagi</h3>
                <div class="menu-card-horizontal">
                    <div class="img-placeholder">🌄</div>
                    <div class="menu-detail">
                        <h4>nama pesanan</h4>
                        <span class="sold-count">total Penjualan</span>
                        <span class="menu-price">harga pesanan</span>
                    </div>
                </div>
            </section>
        </div>

        <section class="katalog-menu-section">
            <h3 class="section-title">Menu</h3>
            
            <div class="menu-grid">
                
                <div class="menu-card">
                    <form action="../backend/proses-pesan.php" method="POST">
                        <div class="img-placeholder">🌄</div>
                        <div class="menu-detail">
                            <input type="hidden" name="id_produk" value="101" /> 
                            
                            <h4>Nasi Goreng Spesial</h4>
                            <span class="sold-count">Terjual 120 porsi</span>
                            <span class="menu-price">Rp 15.000</span>
                            
                            <div class="action-buy">
                                <input type="number" name="jumlah_beli" value="1" min="1" required class="input-qty" />
                                <button type="submit" name="checkout" class="btn-buy">Beli</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="menu-card">
                    <form action="../backend/proses-pesan.php" method="POST">
                        <div class="img-placeholder">🌄</div>
                        <div class="menu-detail">
                            <input type="hidden" name="id_produk" value="102" />
                            <h4>Mie Ayam Bakso</h4>
                            <span class="sold-count">Terjual 85 porsi</span>
                            <span class="menu-price">Rp 13.000</span>
                            <div class="action-buy">
                                <input type="number" name="jumlah_beli" value="1" min="1" required class="input-qty" />
                                <button type="submit" name="checkout" class="btn-buy">Beli</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="menu-card">
                    <form action="../backend/proses-pesan.php" method="POST">
                        <div class="img-placeholder">🌄</div>
                        <div class="menu-detail">
                            <input type="hidden" name="id_produk" value="103" />
                            <h4>Es Teh Manis Jumbo</h4>
                            <span class="sold-count">Terjual 340 porsi</span>
                            <span class="menu-price">Rp 4.000</span>
                            <div class="action-buy">
                                <input type="number" name="jumlah_beli" value="1" min="1" required class="input-qty" />
                                <button type="submit" name="checkout" class="btn-buy">Beli</button>
                            </div>
                        </div>
                    </form>
                </div>

                </div>
        </section>

    </main>

</body>

</html>