# TugasWeb-Pertemuan7-LoginRegister

Sistem Login/Register sederhana menggunakan PHP Native + file JSON sebagai penyimpanan data.
Mata Kuliah Pemrograman Web (3KOM40115) — Tugas Rutin 7.

## Struktur Folder

```
├── index.php            # Redirect otomatis ke dashboard/login
├── register.php         # Form & proses registrasi
├── login.php            # Form & proses login (+ Remember Me)
├── dashboard.php         # Halaman yang diproteksi (wajib login)
├── edit_profile.php     # Bonus: ubah nama/password
├── logout.php           # Proses logout
├── includes/
│   ├── functions.php    # Fungsi baca/simpan JSON, validasi, sanitasi
│   └── auth.php         # Session handling & proteksi halaman
├── data/
│   └── users.json       # Penyimpanan data user
└── assets/
    └── style.css        # Styling tampilan
```

## Cara Menjalankan

1. Pastikan PHP sudah terinstall (PHP 7.4 ke atas).
2. Jalankan server bawaan PHP dari folder proyek ini:
   ```
   php -S localhost:8000
   ```
3. Buka `http://localhost:8000` di browser.
4. Daftar akun baru lewat halaman Register, lalu login.

## Fitur yang Diimplementasikan

- Form registrasi dengan validasi nama, email, dan password
- Validasi format email dengan `filter_var()`
- Password di-hash dengan `password_hash()` (bcrypt)
- Data user disimpan di `data/users.json`
- Pengecekan duplikasi email saat registrasi
- Login dengan session (`$_SESSION`)
- Dashboard yang diproteksi — redirect ke login jika belum login
- Logout dengan `session_destroy()`
- Sanitasi seluruh input dengan `htmlspecialchars()`
- Pesan error & sukses yang jelas di setiap form
- **Bonus:** fitur "Remember Me" dengan cookie, halaman edit profil, tampilan CSS rapi

## Catatan

- File `data/users.json` harus punya izin tulis (writable) oleh server PHP.
- Ganti isi `data/users.json` dengan `[]` untuk mereset data user.
