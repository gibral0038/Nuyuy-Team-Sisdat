<!DOCTYPE html>
<html>
<head>
    <title>Form Login Pengguna</title>
</head>
<body>
    <h3>Form Register Pengguna</h3>
    <!-- Data dikirim ke file proses-register.php -->
    <form action="proses-register.php" method="POST">
        <p>
            <label for="id_pengguna">ID Pengguna: </label>
            <input type="number" name="id_pengguna" placeholder="Masukkan ID Pengguna" required />
        </p>
        <p>
            <label for="nama_pengguna">Nama Pengguna: </label>
            <input type="text" name="nama_pengguna" placeholder="Masukkan Nama" required />
        </p>
        <p>
            <label for="email">Email: </label>
            <input type="email" name="email" placeholder="Masukkan Email" required />
        </p>
        <p>
            <label for="password">Password: </label>
            <input type="password" name="password" placeholder="Masukkan Password" required />
        </p>
            <label>Pilih Role Akun:</label><br>
            <input type="radio" id="role_customer" name="role_pengguna" value="customer" checked>
            <label for="role_customer">Customer</label> <br>

            <input type="radio" id="role_admin" name="role_pengguna" value="admin">
            <label for="role_admin">Admin Gudang</label> <br>

            <input type="radio" id="role_supplier" name="role_pengguna" value="supplier">
            <label for="role_supplier">Supplier</label>
        <p>
            <input type="submit" value="Register" name="register" />
        </p>
    </form>
</body>
</html>