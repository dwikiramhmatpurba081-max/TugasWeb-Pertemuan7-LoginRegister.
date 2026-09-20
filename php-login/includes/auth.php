<?php
/**
 * auth.php
 * Menangani session, fitur "Remember Me" via cookie, dan proteksi halaman.
 */

require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Mengecek apakah user sedang login (via session atau cookie remember-me).
 * Jika cookie valid tapi session belum ada, session akan dipulihkan otomatis.
 */
function isLoggedIn(): bool
{
    if (isset($_SESSION['user_email'])) {
        return true;
    }

    // Coba pulihkan sesi dari cookie "Remember Me"
    if (isset($_COOKIE['remember_email'], $_COOKIE['remember_token'])) {
        $email = $_COOKIE['remember_email'];
        $token = $_COOKIE['remember_token'];
        $user = findUserByEmail($email);

        if ($user && isset($user['remember_token']) && hash_equals($user['remember_token'], $token)) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            return true;
        }
    }

    return false;
}

/**
 * Memaksa halaman hanya bisa diakses jika sudah login.
 * Jika belum login, redirect ke halaman login.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
