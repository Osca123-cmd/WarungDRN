<?php
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($username === '' || $password === '' || $password_confirm === '') {
        $message = 'Semua field harus diisi.';
    } elseif ($password !== $password_confirm) {
        $message = 'Password dan konfirmasi password tidak sama.';
    } else {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = 'Username sudah digunakan.';
        } else {
            // Hash password dan simpan
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $username, $password_hash);
            if ($stmt_insert->execute()) {
                header('Location: login.php?registered=1');
                exit;
            } else {
                $message = 'Gagal mendaftar, coba lagi.';
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Registrasi Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 20px; }
        .container { max-width: 400px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 20px; width: 100%; padding: 10px; background-color: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #1f6391; }
        .message { margin-top: 15px; color: red; text-align: center; }
        .link { margin-top: 15px; text-align: center; }
        .link a { color: #2980b9; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrasi Admin</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <label for="password_confirm">Konfirmasi Password:</label>
            <input type="password" id="password_confirm" name="password_confirm" required />

            <button type="submit">Daftar</button>
        </form>
        <div class="link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>
