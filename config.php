<?php
// config.php
$host = 'localhost';      // Host database, biasanya localhost
$user = 'root';           // Username database Anda
$password = '';           // Password database Anda
$database = 'db_pesanan'; // Nama database yang sudah dibuat

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke utf8mb4 agar mendukung karakter lengkap
$conn->set_charset("utf8mb4");
?>
