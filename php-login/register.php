<?php
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$success = '';
$old = ['name' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name            = sanitize($_POST['name'] ?? '');
    $email           = sanitize($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $old['name']  = $name;
    $old['email'] = $email;

    // 1. Validasi field wajib
    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'Semua field wajib diisi.';
    }

    // 2. Validasi format email dengan filter_var()
    if ($email !== '' && !isValidEmail($email)) {
        $errors[] = 'Format email tidak valid.';
    }

    // 3. Validasi password
    if ($password !== '' && !isValidPassword($password)) {
        $errors[] = 'Password minimal 6 karakter.';
    }

    if ($password !== '' && $confirmPassword !== '' && $password !== $confirmPassword) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    // 4. Cek duplikasi email
    if (empty($errors) && findUserByEmail($email) !== null) {
        $errors[] = 'Email sudah terdaftar. Silakan gunakan email lain atau login.';
    }

    // 5. Simpan user baru jika semua validasi lolos
    if (empty($errors)) {
        $users = loadUsers();

        $users[] = [
            'id'             => count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1,
            'name'           => $name,
            'email'          => $email,
            'password_hash'  => password_hash($password, PASSWORD_DEFAULT),
            'remember_token' => null,
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        if (saveUsers($users)) {
            $success = 'Registrasi berhasil! Silakan login menggunakan akun kamu.';
            $old = ['name' => '', 'email' => ''];
        } else {
            $errors[] = 'Gagal menyimpan data. Coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="card">
        <h1>Buat Akun Baru</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php" novalidate>
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Daftar</button>
        </form>

        <p class="footer-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
