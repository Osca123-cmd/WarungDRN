<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Selamat Datang</title>
    <style>
        /* Reset and base */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            /* Animated gradient background for body */
            background: linear-gradient(270deg, #1e3c72, #2a5298, #1e3c72, #2a5298);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: #f0f0f0;
            min-height: 100vh;
        }
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Animated gradient background keyframes */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(270deg, #0f2027, #203a43, #2c5364, #0f2027);
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
            display: flex;
            justify-content: space-between; /* Logo kiri, menu kanan */
            align-items: center;
            padding: 1rem 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }

        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: #e0e0e0;
        }

        .nav-links a {
            color: #e0e0e0;
            font-weight: 600;
            margin-left: 1.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-decoration: none;
        }

        .nav-links a:hover,
        .nav-links a:focus {
            background-color: #3498db;
            color: white;
            outline: none;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #2980b9, #6dd5fa);
            color: white;
            text-align: center;
            padding: 6rem 1rem 4rem;
            box-shadow: inset 0 -4rem 6rem -4rem rgba(0,0,0,0.3);
        }
        .hero h1 {
            font-size: 3.5rem;
            margin: 0;
            font-weight: 700;
            letter-spacing: 1.2px;
        }
        .hero p {
            margin-top: 1rem;
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.85;
        }

        /* Cards Container */
        .cards {
            max-width: 960px;
            margin: 2rem auto 4rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            padding: 0 1rem;
        }

        /* Animated gradient background for cards */
        .card {
            background: linear-gradient(270deg, #6a11cb, #2575fc, #6a11cb, #2575fc);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 1.5rem 1.25rem;
            text-align: center;
            color: white;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: default;
        }
        .card:hover,
        .card:focus-within {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
            outline: none;
        }
        .card h3 {
            margin-bottom: 0.75rem;
            font-size: 1.3rem;
        }
        .card p {
            font-weight: 400;
            font-size: 0.95rem;
            line-height: 1.4;
            opacity: 0.9;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .cards {
                margin: 1rem auto 3rem;
            }
            .nav-links a {
                padding: 0.4rem 0.8rem;
                margin-left: 1rem;
                font-size: 0.9rem;
            }
            .navbar {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="logo" aria-label="Logo Sistem">Warung DRN</div>
    <div class="nav-links">
        <a href="menu.php" tabindex="0">Menu</a>
        <a href="login.php" tabindex="0">Admin</a>
        <a href="process_order.php" tabindex="0">Process Order</a>
    </div>
</nav>

<header class="hero" role="banner">
    <h1>Selamat Datang di Warung DRN</h1>
    <p>Mengelola menu, dan proses pesanan dengan mudah dan efisien.</p>
</header>

<main>
    <section class="cards" aria-label="Fitur utama sistem">
        <article class="card" tabindex="0">
            <h3>Menu Lengkap</h3>
            <p>Temukan berbagai pilihan menu yang kami sediakan dengan kualitas terbaik.</p>
        </article>
        <article class="card" tabindex="0">
            <h3>Manajemen Waktu</h3>
            <p>Dengan sistem ini diharapkan dapat memudahkan dalam proses pesanan</p>
        </article>
        <article class="card" tabindex="0">
            <h3>Proses Pesanan</h3>
            <p>Proses pesanan pelanggan dengan cepat dan efisien.</p>
        </article>
    </section>
</main>

</body>
</html>
