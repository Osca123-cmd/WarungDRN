<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>QR Code Elegan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 360px;
            width: 100%;
        }

        h1 {
            font-weight: 600;
            margin-bottom: 24px;
            color: #222;
        }

        p {
            font-size: 16px;
            margin-bottom: 32px;
            color: #555;
        }

        .qr-code {
            width: 250px;
            height: 250px;
            margin: 0 auto 24px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
        }

        .qr-code:hover {
            transform: scale(1.05);
        }

        .footer {
            font-size: 14px;
            color: #888;
            margin-top: 16px;
        }

        a.button {
            display: inline-block;
            padding: 12px 28px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Scan QR Code Ini</h1>
        <img src="images/menu.jpg" alt="QR Code" class="qr-code" />
        <p>Scan QR code ini untuk mengakses halaman index dengan mudah dan cepat.</p>
        <a href="menu.php" target="_blank" class="button">Buka Halaman Menu</a>
    </div>
</body>
</html>
