<?php
session_start();

// Contoh data user untuk testing (username => password)
$users = [
    'user1' => 'password123',
    'user2' => 'mypassword',
];

// Ambil data dari form login
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Cek apakah username ada dan password cocok
if (isset($users[$username]) && $users[$username] === $password) {
    // Login berhasil, simpan session
    $_SESSION['username'] = $username;
    // Redirect ke index.php
    header('Location: index.php');
    exit();
} else {
    // Login gagal, redirect kembali ke halaman login dengan pesan error
    $_SESSION['error'] = 'Username atau password salah.';
    header('Location: user_login.html');
    exit();
}
?>
