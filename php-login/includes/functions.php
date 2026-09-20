<?php
/**
 * functions.php
 * Kumpulan fungsi helper: penyimpanan data (JSON), validasi, dan sanitasi input.
 */

define('USERS_FILE', __DIR__ . '/../data/users.json');

/**
 * Membaca seluruh data user dari file JSON.
 * Mengembalikan array kosong jika file belum ada atau rusak.
 */
function loadUsers(): array
{
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, json_encode([]));
    }

    $content = file_get_contents(USERS_FILE);
    $data = json_decode($content, true);

    return is_array($data) ? $data : [];
}

/**
 * Menyimpan seluruh data user ke file JSON.
 */
function saveUsers(array $users): bool
{
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    return file_put_contents(USERS_FILE, $json) !== false;
}

/**
 * Mencari satu user berdasarkan email. Mengembalikan null jika tidak ditemukan.
 */
function findUserByEmail(string $email): ?array
{
    $users = loadUsers();
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

/**
 * Mencari index array user berdasarkan email. Mengembalikan -1 jika tidak ditemukan.
 */
function findUserIndexByEmail(string $email): int
{
    $users = loadUsers();
    foreach ($users as $index => $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $index;
        }
    }
    return -1;
}

/**
 * Sanitasi input teks: trim spasi lalu escape karakter HTML berbahaya.
 */
function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi format email menggunakan filter_var().
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validasi panjang minimum password.
 */
function isValidPassword(string $password): bool
{
    return strlen($password) >= 6;
}

/**
 * Menghasilkan token acak untuk fitur "Remember Me".
 */
function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}
