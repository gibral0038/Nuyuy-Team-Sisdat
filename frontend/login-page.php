<!DOCTYPE html>
<html>
<head>
    <title>Form Login Pengguna</title>
</head>
<body>
    <h3>Form Login Pengguna</h3>
    <!-- Data dikirim ke file proses-login.php -->
    <form action="../backend/proses-login.php" method="POST">
        <p>
            <label for="email_pengguna">Email: </label>
            <input type="email" name="email_pengguna" placeholder="Masukkan Email" required />
        </p>
        <p>
            <label for="password">Password: </label>
            <input type="password" name="password" placeholder="Masukkan Password" required />
        </p>
            <input type="submit" value="Login" name="login" />
        </p>
        <p>
            <a href="register-page.php" style="border-radius: 0; padding: 5px 10px; background-color: #ededed; color: black; border: 1px solid #3f3f3f; text-decoration: none;">
            Register
            </a>
        </p>
    </form>
</body>
</html>