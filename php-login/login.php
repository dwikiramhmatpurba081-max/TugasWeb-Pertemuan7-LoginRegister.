<?php
require_once __DIR__ . '/includes/auth.php';

// Kalau sudah login, langsung lempar ke dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$old = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email        = sanitize($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $rememberMe   = isset($_POST['remember']);

    $old['email'] = $email;

    if ($email === '' || $password === '') {
        $errors[] = 'Email dan password wajib diisi.';
    }

    if (empty($errors)) {
        $user = findUserByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors[] = 'Email atau password salah.';
        } else {
            // Set session
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name']  = $user['name'];

            // Fitur "Remember Me" dengan cookie
            if ($rememberMe) {
                $token = generateToken();
                $users = loadUsers();
                $index = findUserIndexByEmail($email);
                $users[$index]['remember_token'] = $token;
                saveUsers($users);

                $expire = time() + (30 * 24 * 60 * 60); // 30 hari
                setcookie('remember_email', $user['email'], $expire, '/');
                setcookie('remember_token', $token, $expire, '/');
            }

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="card">
        <h1>Masuk ke Akun</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="checkbox-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin:0;font-weight:400;">Ingat saya</label>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="footer-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
