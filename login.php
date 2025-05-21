<?php
session_start();
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Username dan password harus diisi.';
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $password_hash);
            $stmt->fetch();
            if (password_verify($password, $password_hash)) {
                // Login berhasil, simpan session dan redirect ke admin.php
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_username'] = $username;
                header('Location: admin.php');
                exit;
            } else {
                $message = 'Password salah.';
            }
        } else {
            $message = 'Username tidak ditemukan.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Login Admin</title>
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
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Admin</h2>
        <?php if (isset($_GET['registered'])): ?>
            <div class="message success">Registrasi berhasil, silakan login.</div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <button type="submit">Login</button>
        </form>
        <div class="link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>
</html>
