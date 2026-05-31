<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login Pengguna</title>
    <link rel="stylesheet" href="design.css">
</head>

<body class="halaman-login">

    <div class="logo-container">
        <div class="logo-box">Logo</div>
    </div>

    <div class="auth-card">
        <div class="auth-tabs">
            <a href="login-page.php" class="tab-item active">Log In</a>
            <a href="register-page.php" class="tab-item">Register</a>
        </div>

        <form action="../backend/proses-login.php" method="POST" class="auth-form">
            <div class="input-group">
                <label for="email_pengguna">Email / Username</label>
                <div class="input-wrapper">
                    <span class="icon">✉️</span>
                    <input type="email" name="email_pengguna" placeholder="Email / Username" required />
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" placeholder="Password" required />
                </div>
            </div>

            <button type="submit" name="login" class="btn-auth">Log In</button>
        </form>
    </div>

</body>

</html>