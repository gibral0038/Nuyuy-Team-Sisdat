// Common JS for the project
// (No build step; loaded directly from PHP pages)

console.log('[script.js] File loaded successfully');

(function () {
  function setField(id, value) {
    const el = document.getElementById(id);
    if (!el) return;
    el.value = value;
  }

  // Called by inline onclick on supplier-page.php (⚙️ = tampilkan form tambah stok)
  window.bbSupplierTambahStok = function (btnEl) {
    console.log('[bbSupplierTambahStok] Fungsi dipanggil');
    console.log('[bbSupplierTambahStok] Button element:', btnEl);
    console.log('[bbSupplierTambahStok] Data produk tersedia:', window.bbSupplierProducts);
    
    const id = btnEl?.getAttribute('data-edit');
    console.log('[bbSupplierTambahStok] ID dari tombol:', id);
    
    if (!id) {
      console.error('[bbSupplierTambahStok] ERROR: ID tidak ditemukan');
      return;
    }
    
    if (!Array.isArray(window.bbSupplierProducts)) {
      console.error('[bbSupplierTambahStok] ERROR: bbSupplierProducts bukan array');
      return;
    }

    const item = window.bbSupplierProducts.find((x) => String(x.id_produk) === String(id));
    console.log('[bbSupplierTambahStok] Produk ditemukan:', item);
    
    if (!item) {
      console.error('[bbSupplierTambahStok] ERROR: Produk tidak ditemukan di array');
      return;
    }

    setField('stok_id_produk', item.id_produk);
    console.log('[bbSupplierTambahStok] Hidden input di-set dengan ID:', item.id_produk);

    const container = document.getElementById('bbSupplierTambahStokContainer');
    console.log('[bbSupplierTambahStok] Container ditemukan:', !!container);
    
    if (container) {
      container.style.display = 'block';
      console.log('[bbSupplierTambahStok] Container di-show');
      container.scrollIntoView({ behavior: 'smooth', flex: 'center' });
      console.log('[bbSupplierTambahStok] Scroll ke container');
    } else {
      console.error('[bbSupplierTambahStok] ERROR: Container tidak ditemukan');
    }
  };


  // Basic enhancements: smooth scrolling for buttons with JS
  document.addEventListener('click', (e) => {
    const target = e.target.closest?.('a[href], button[data-scroll]');
    if (!target) return;
  });
})();

