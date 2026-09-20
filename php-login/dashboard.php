<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin(); // Redirect ke login.php jika belum login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="card dashboard-box">
        <h1>Dashboard</h1>

        <div class="user-info">
            Selamat datang, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>!<br>
            Email: <?= htmlspecialchars($_SESSION['user_email']) ?>
        </div>

        <div class="actions">
            <a href="edit_profile.php" style="width:100%;">
                <button type="button" class="btn">Edit Profil</button>
            </a>
        </div>
        <br>
        <form method="POST" action="logout.php">
            <button type="submit" class="btn btn-secondary">Logout</button>
        </form>
    </div>
</body>
</html>
