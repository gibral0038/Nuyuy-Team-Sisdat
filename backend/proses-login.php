<?php
// 1. Hubungkan ke database lewat koneksi.php
include("koneksi.php");

// Mulai session agar status login tersimpan di web browser
session_start();

if (isset($_POST['login'])) {
    // 2. Tangkap Email dan Password yang diinput oleh user
    $email_pengguna = $_POST['email_pengguna'];
    $password = $_POST['password'];

    // 3. Lakukan Query ke db_penjualan pada tabel pengguna
    $sql = "SELECT * FROM pengguna WHERE email_pengguna = '$email_pengguna' AND password_pengguna = '$password'";
    $query = mysqli_query($conn_penjualan, $sql);

    // 4. Hitung apakah data ditemukan atau tidak
    if (mysqli_num_rows($query) > 0) {
        // JIKA ADA (Jumlah baris lebih dari 0)
        // Ambil data pengguna tersebut
        $data_user = mysqli_fetch_assoc($query);

        // Simpan data penting ke dalam SESSION agar bisa dipakai di halaman lain
        $_SESSION['id_pengguna'] = $data_user['id_pengguna'];
        $_SESSION['nama_pengguna'] = $data_user['nama_pengguna'];
        $_SESSION['email_pengguna'] = $data_user['email_pengguna'];
        $_SESSION['role_pengguna'] = $data_user['role_pengguna'];

        $role_check = strtolower($data_user['role_pengguna']);

        // Lempar ke halaman utama (index.php)
        if ($role_check == 'admin') {
            header("Location: ../frontend/admin-page.php");
            exit();
        } else if ($role_check == 'supplier') {
            header("Location: ../frontend/supplier-page.php");
            exit();
        } else {
            header("Location: ../frontend/index.php");
            exit();
        }
        exit();
    } else {
        // JIKA TIDAK ADA (Email tidak terdaftar di database)
        // Alihkan ke halaman register sambil membawa pesan peringatan
        header("Location: ../frontend/register-page.php?pesan=email_tidak_ditemukan");
        exit();
    }
} else {
    die("Akses dilarang...");
}
?>