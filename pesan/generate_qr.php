<?php
// Ganti URL ini dengan URL hosting Anda nanti
$url_index = 'https://yourdomain.com/index.php';

$data = urlencode($url_index);
$size = 300;
$qr_url = "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl={$data}&choe=UTF-8";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Generate QR Code untuk Hosting</title>
</head>
<body>
    <h2>QR Code menuju halaman index di hosting</h2>
    <img src="images/menu.jpg" alt="QR Code menuju index" width="150" height="150" />
    <p>Scan QR code ini untuk membuka halaman index di hosting Anda.</p>
</body>
</html>
