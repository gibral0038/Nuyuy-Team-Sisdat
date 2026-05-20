<?php
// 1. Wajib jalankan session_start() agar PHP tahu session mana yang mau dihancurkan
session_start();

// 2. Kosongkan semua variabel session yang ada (id_pengguna, nama_pengguna, role)
session_unset();

// 3. Hancurkan sesi login secara total dari memori server
session_destroy();

// 4. Alihkan pengguna kembali ke halaman login utama kelompokmu
header("Location: login-page.php");
exit();
?>