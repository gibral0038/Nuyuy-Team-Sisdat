# TODO - Update Sistem Supplier & Customer (Nuyuy-Team-Sisdat)

## Step 1 — Audit & konsolidasi skema stok/penjualan
- [x] Samakan penanganan `pesanan`, `detail_pesanan`, `pembayaran` di PHP dengan schema pada `backend/penjualan.sql`.
- [x] Jadikan `db_gudang.gudang.stok_sekarang` sebagai sumber stok utama. `produk.stok_produk` akan diabaikan atau disinkronisasi.


## Step 2 — Tombol logout untuk semua role & halaman
- [x] Tambahkan tombol Logout pada `frontend/index.php`, `frontend/admin-page.php`, `frontend/supplier-page.php`, `frontend/form-pesan.php`.
- [x] Pastikan tombol logout konsisten ke `backend/logout.php`.

## Step 3 — Supplier CRUD produk (tambah/edit/tambah stok/hapus)
- [x] Buat endpoint tambah produk + tambah stok.
- [x] Tingkatkan `backend/proses-edit-produk.php` agar benar-benar mengedit detail produk (nama/deskripsi/harga) dan kontrol stok.
- [x] Buat endpoint hapus produk.

- [x] Validasi akses: supplier hanya boleh mengakses `produk` miliknya (`produk.id_supplier = session.id_pengguna` melalui tabel `supplier`).


## Step 4
- [x] Rule hapus produk supplier => riwayat customer terkait ikut terhapus (cleanup di `backend/proses-hapus-produk.php`).
- [x] Update query customer agar hanya menampilkan produk yang masih ada & stok tersedia (pakai `gudang.stok_sekarang`).


## Step 5 — Supplier dashboard stok & analitik best seller
- [ ] Update `frontend/supplier-page.php` untuk memfilter produk milik supplier aktif.
- [ ] Tampilkan stok supplier + top-selling berdasarkan data transaksi.

## Step 6 — Customer: pesanan dalam proses & riwayat
- [x] Update `backend/proses-pesan.php` agar mengikuti schema `backend/penjualan.sql` dan kurangi `gudang.stok_sekarang` saat checkout.
- [x] Update `frontend/form-pesan.php` (riwayat & katalog menu) agar mengikuti schema `backend/penjualan.sql` dan stok utama `gudang.stok_sekarang`.
- [ ] Update `best_seller` dan/atau `laporan_penjualan`.
- [ ] Pastikan tampilan & filter riwayat mendukung status flow `pending/diproses/selesai`.


## Step 7 — Update `script.js`
- [ ] Implement validasi & perhitungan total di sisi client.
- [ ] UI feedback (toast/alert) untuk sukses/gagal.

## Step 8 — UI/UX smooth & professional
- [ ] Rapikan CSS seperlunya untuk modal/section CRUD supplier & tombol logout.

## Step 9 — Testing
- [ ] Login sebagai supplier: tambah/edit/hapus produk → customer menu berubah.
- [ ] Checkout customer: stok gudang berkurang & riwayat tampil benar.
- [ ] Cek top-selling & laporan penjualan supplier.

