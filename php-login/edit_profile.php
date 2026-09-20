<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = sanitize($_POST['name'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';

    if ($name === '') {
        $errors[] = 'Nama tidak boleh kosong.';
    }

    if ($newPassword !== '' && !isValidPassword($newPassword)) {
        $errors[] = 'Password baru minimal 6 karakter.';
    }

    if (empty($errors)) {
        $users = loadUsers();
        $index = findUserIndexByEmail($_SESSION['user_email']);

        if ($index !== -1) {
            $users[$index]['name'] = $name;

            if ($newPassword !== '') {
                $users[$index]['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            saveUsers($users);
            $_SESSION['user_name'] = $name;
            $success = 'Profil berhasil diperbarui.';
        }
    }
}

$currentUser = findUserByEmail($_SESSION['user_email']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="card">
        <h1>Edit Profil</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="edit_profile.php" novalidate>
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($currentUser['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" id="new_password" name="new_password">
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>

        <p class="footer-link"><a href="dashboard.php">&larr; Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
