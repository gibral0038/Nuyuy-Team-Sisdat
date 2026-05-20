<?php
// 1. Hubungkan ke database lewat koneksi.php
include("koneksi.php");

// Mulai session agar status login tersimpan di web browser
session_start();

if (isset($_POST['register'])) {
    // 2. Tangkap data yang diinput oleh user
    $id_pengguna = $_POST['id_pengguna'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_pengguna = $_POST['role_pengguna'];

    // 3. Lakukan Query ke db_penjualan pada tabel pengguna
    $sql = "INSERT INTO pengguna (id_pengguna, nama_pengguna, email, password, role_pengguna) VALUES ('$id_pengguna', '$nama_pengguna', '$email', '$password', '$role_pengguna')";
    $query = mysqli_query($conn_penjualan, $sql);

    // 4. Hitung apakah data ditemukan atau tidak
    if ($query) {
        // JIKA BERHASIL (Query berhasil dijalankan)
        // Alihkan ke halaman login sambil membawa pesan sukses
        header("Location: login-page.php?pesan=register_berhasil");
    } else {
        // JIKA TIDAK BERHASIL (Query gagal dijalankan)
        // Alihkan ke halaman register sambil membawa pesan peringatan
        header("Location: register-page.php?pesan=register_gagal");
        exit();
    }
} else {
    die("Akses dilarang...");
}
?>