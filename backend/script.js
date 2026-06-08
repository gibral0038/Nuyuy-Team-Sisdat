// Common JS for the project
// (No build step; loaded directly from PHP pages)

(function () {
  function setField(id, value) {
    const el = document.getElementById(id);
    if (!el) return;
    el.value = value;
  }

  // Called by inline onclick on supplier-page.php
  window.bbSupplierEdit = function (btnEl) {
    const id = btnEl?.getAttribute('data-edit');
    if (!id || !Array.isArray(window.bbSupplierProducts)) return;

    const item = window.bbSupplierProducts.find((x) => String(x.id_produk) === String(id));
    if (!item) return;

    setField('edit_id_produk', item.id_produk);
    setField('edit_nama_produk', item.nama_produk || '');
    setField('edit_deskripsi_produk', item.deskripsi_produk || '');
    setField('edit_harga_produk', item.harga_produk ?? 0);
    setField('edit_stok_produk_baru', item.stok_sekarang ?? 0);

    setField('stok_id_produk', item.id_produk);

    // Scroll to edit section for smooth UX
    const editBtn = document.getElementById('btnSaveEdit');
    if (editBtn) editBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
  };

  // Basic enhancements: smooth scrolling for buttons with JS
  document.addEventListener('click', (e) => {
    const target = e.target.closest?.('a[href], button[data-scroll]');
    if (!target) return;
  });
})();

