# TODO STEP 3 — Supplier CRUD produk & stok (berbasis gudang.stok_sekarang)

## Tujuan
Supplier dapat:
- tambah produk jenis baru
- tambah stok
- edit produk
- hapus produk
- hanya mengakses produk miliknya sendiri

## Alur kerja (server)
1. Ambil `supplier_aktif_id` dari `$_SESSION['id_pengguna']`.
2. Validasi akses: produk harus punya `produk.id_supplier = supplier_aktif_id`.
3. Operasi data:
   - Produk tersimpan di `db_gudang.produk`.
   - Stok utama disimpan di `db_gudang.gudang.stok_sekarang` (bukan `produk.stok_produk`).
4. Untuk `edit` stok:
   - stok **replace** (set sesuai input).
   - untuk `tambah stok` gunakan increment.

## Endpoint yang harus dibuat/diperbarui
- `backend/proses-tambah-produk.php`
- `backend/proses-tambah-stok.php`
- `backend/proses-edit-produk.php` (update + stok replace ke `gudang`)
- `backend/proses-hapus-produk.php`

## Endpoint minimum data yang dibutuhkan UI
- `id_produk` (untuk edit/hapus)
- `nama_produk`, `deskripsi_produk`, `harga_produk`
- stok:
  - replace: `stok_produk_baru`
  - increment: `tambah_stok`

## Next steps after Step 3
- Update `frontend/supplier-page.php` dari mode dummy menjadi CRUD sebenarnya.
- Update query stok di supplier page menggunakan `gudang.stok_sekarang`.


