<?php
// 1. Hubungkan ke database lewat koneksi.php
include("koneksi.php");

// Mulai session agar status login tersimpan di web browser
session_start();

if (isset($_POST['login'])) {
    // 2. Tangkap ID yang diinput oleh user
    $id_pengguna = $_POST['id_pengguna'];
    $password = $_POST['password'];

    // 3. Lakukan Query ke db_penjualan pada tabel pengguna
    $sql = "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna' AND password = '$password'";
    $query = mysqli_query($conn_penjualan, $sql);

    // 4. Hitung apakah data ditemukan atau tidak
    if (mysqli_num_rows($query) > 0) {
        // JIKA ADA (Jumlah baris lebih dari 0)
        // Ambil data pengguna tersebut
        $data_user = mysqli_fetch_assoc($query);

        // Simpan data penting ke dalam SESSION agar bisa dipakai di halaman lain
        $_SESSION['id_pengguna'] = $data_user['id_pengguna'];
        $_SESSION['nama_pengguna'] = $data_user['nama_pengguna'];
        $_SESSION['role_pengguna'] = $data_user['role_pengguna'];

        // Lempar ke halaman utama (index.php)
        header("Location: index.php");
        exit();
    } else {
        // JIKA TIDAK ADA (ID tidak terdaftar di database)
        // Alihkan ke halaman register sambil membawa pesan peringatan
        header("Location: register.php?pesan=id_tidak_ditemukan");
        exit();
    }
} else {
    die("Akses dilarang...");
}
?>