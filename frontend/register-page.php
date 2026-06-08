<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Register Pengguna</title>
    <link rel="stylesheet" href="design.css">
</head>

<body class="halaman-register">

    <div class="logo-container">
        <div class="logo-box">logo</div>
    </div>

    <div class="auth-card">
        <div class="auth-tabs">
            <a href="login-page.php" class="tab-item">Log In</a>
            <a href="register-page.php" class="tab-item active">Register</a>
        </div>

        <!-- Data dikirim ke file proses-register.php -->
        <form action="../backend/proses-register.php" method="POST">

            <input type="hidden" name="id_pengguna" value="<?php echo rand(1000, 9999); ?>" />
            <input type="hidden" name="nama_pengguna" value="User Baru" />

            <div class="input-group">
                <label for="email_pengguna">Email / Username</label>
                <div class="input-wrapper">
                    <span class="icon">✉️</span>
                    <input type="email" name="email_pengguna" placeholder="Email / Username" required />
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="icon">🔒</span>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                </div>

                <div class="input-group">
                    <label for="role_pengguna">Role</label>
                    <div class="input-wrapper">
                        <select name="role_pengguna" id="role_pengguna" required>
                            <option value="supplier">Supplier</option>
                            <option value="customer" selected>Customer</option>
                        </select>

                    </div>
                </div>
            </div>

            <div class="input-group">
                <div class="input-wrapper">
                    <span class="icon">🔒</span>
                    <input type="password" name="konfirmasi_password" placeholder="Konfirmasi Password" required />
                </div>
            </div>

            <input type="submit" name="register" class="btn-auth" value="Register" style="text-align: center;" />
        </form>
    </div>

</body>

</html>