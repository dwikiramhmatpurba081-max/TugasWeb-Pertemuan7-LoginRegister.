<?php
require_once __DIR__ . '/includes/auth.php';

// Hapus remember_token dari data user (jika ada) agar cookie lama tidak bisa dipakai lagi
if (isset($_SESSION['user_email'])) {
    $users = loadUsers();
    $index = findUserIndexByEmail($_SESSION['user_email']);
    if ($index !== -1) {
        $users[$index]['remember_token'] = null;
        saveUsers($users);
    }
}

// Hapus session
$_SESSION = [];
session_destroy();

// Hapus cookie "Remember Me"
setcookie('remember_email', '', time() - 3600, '/');
setcookie('remember_token', '', time() - 3600, '/');

header('Location: login.php');
exit;
