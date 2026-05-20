<!DOCTYPE html>
<html>
<head>
    <title>Form Login Pengguna</title>
</head>
<body>
    <h3>Form Login Pengguna</h3>
    <!-- Data dikirim ke file proses-login.php -->
    <form action="proses-login.php" method="POST">
        <p>
            <label for="id_pengguna">ID Pengguna: </label>
            <input type="number" name="id_pengguna" placeholder="Masukkan ID Pengguna" required />
        </p>
        <p>
            <label for="password">Password: </label>
            <input type="password" name="password" placeholder="Masukkan Password" required />
        </p>
            <input type="submit" value="Login" name="login" />
        </p>
    </form>
</body>
</html>