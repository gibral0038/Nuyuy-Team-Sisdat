// Common JS for the project
// (No build step; loaded directly from PHP pages)

(function () {
  function setField(id, value) {
    const el = document.getElementById(id);
    if (!el) return;
    el.value = value;
  }

  // Called by inline onclick on supplier-page.php (⚙️ = tampilkan form tambah stok)
  window.bbSupplierTambahStok = function (btnEl) {
    const id = btnEl?.getAttribute('data-edit');
    if (!id || !Array.isArray(window.bbSupplierProducts)) return;

    const item = window.bbSupplierProducts.find((x) => String(x.id_produk) === String(id));
    if (!item) return;

    setField('stok_id_produk', item.id_produk);

    const container = document.getElementById('bbSupplierTambahStokContainer');
    if (container) {
      container.style.display = 'block';
      container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  };


  // Basic enhancements: smooth scrolling for buttons with JS
  document.addEventListener('click', (e) => {
    const target = e.target.closest?.('a[href], button[data-scroll]');
    if (!target) return;
  });
})();

