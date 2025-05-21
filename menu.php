<?php
// Data menu dengan status ready/not ready
$menu = [
    "Miso" => [
        ["nama" => "Babat", "harga" => 8000, "status" => "ready"],
        ["nama" => "Cincang", "harga" => 8000, "status" => "ready"],
        ["nama" => "Ayam Bacok", "harga" => 10000, "status" => "ready"],
        ["nama" => "Ayam Suir", "harga" => 8000, "status" => "ready"],
        ["nama" => "Bakso", "harga" => 8000, "status" => "ready"],
        ["nama" => "Mie Ayam", "harga" => 8000, "status" => "ready"],
        ["nama" => "Mie Ayam Bakso", "harga" => 15000, "status" => "ready"],
    ],
    "Soto" => [
        ["nama" => "Babat", "harga" => 10000, "status" => "ready"],
        ["nama" => "Ayam", "harga" => 10000, "status" => "ready"],
        ["nama" => "Cincang", "harga" => 10000, "status" => "ready"],
        ["nama" => "Nasi Soto", "harga" => 15000, "status" => "ready"],
    ],
    "Geprek" => [
        ["nama" => "Sambal Doer", "harga" => 13000, "status" => "ready"],
    ],
    "Mie" => [
        ["nama" => "Mie Goreng", "harga" => 8000, "status" => "ready"],
        ["nama" => "Indomie Goreng", "harga" => 8000, "status" => "ready"],
        ["nama" => "Tumis", "harga" => 8000, "status" => "ready"],
    ],
    "Minuman" => [
        ["nama" => "Mandi", "harga" => 5000, "status" => "ready"],
        ["nama" => "Tebot", "harga" => 5000, "status" => "ready"],
        ["nama" => "Fruit", "harga" => 5000, "status" => "ready"],
        ["nama" => "Badak", "harga" => 10000, "status" => "ready"],
        ["nama" => "Kurnia", "harga" => 5000, "status" => "ready"],
        ["nama" => "Nutrisari", "harga" => 5000, "status" => "ready"],
        ["nama" => "Hilo", "harga" => 7000, "status" => "ready"],
        ["nama" => "Cappucino", "harga" => 7000, "status" => "ready"],
    ],
    "Cemilan" => [
        ["nama" => "Sate Kerang", "harga" => 3000, "status" => "ready"],
        ["nama" => "Bakuan", "harga" => 1000, "status" => "ready"],
        ["nama" => "Kacang", "harga" => 2000, "status" => "ready"],
        ["nama" => "Telur Rebus", "harga" => 3000, "status" => "ready"],
    ],
];

// Warna latar untuk setiap kategori (bisa disesuaikan)
$warna_kategori = [
    "Miso" => "#ffb3b3",       // soft red
    "Soto" => "#b3d9ff",       // soft blue
    "Geprek" => "#ffd9b3",     // soft orange
    "Mie" => "#b3ffda",        // soft teal
    "Minuman" => "#fff3b3",    // soft yellow
    "Cemilan" => "#d9b3ff",    // soft purple
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistem Pesanan Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Background halaman */
        body {
            background: linear-gradient(135deg, #4a90e2, #50e3c2);
            min-height: 100vh;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #212529;
        }
        /* Container utama */
        .container {
            max-width: 1100px;
            background-color: #ffffffdd;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        }
        /* Header */
        h1 {
            font-weight: 900;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 40px;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
        /* Kartu menu */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }
        .card-body {
            border-radius: 15px;
            padding: 25px;
            color: #212529;
        }
        .card-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #222;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-text {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: #333;
        }
        /* Badge status */
        .badge-ready {
            background-color: #28a745;
            color: white;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 700;
        }
        .badge-notready {
            background-color: #dc3545;
            color: white;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 700;
        }
        /* Input jumlah pesanan */
        input[type=number] {
            border-radius: 10px;
            border: 2px solid #ced4da;
            padding: 8px 12px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type=number]:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,0.5);
            outline: none;
        }
        /* Label nomor meja */
        label.form-label {
            font-weight: 700;
            font-size: 1.1rem;
            color: #111;
        }
        /* Tombol submit */
        button.btn-success {
            width: 100%;
            font-weight: 700;
            font-size: 1.2rem;
            padding: 14px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(40,167,69,0.5);
            transition: background-color 0.3s ease;
        }
        button.btn-success:hover {
            background-color: #218838cc;
        }
        /* Warna latar untuk card body sesuai kategori */
        <?php foreach ($warna_kategori as $kategori => $warna): ?>
        .card-body-<?= strtolower($kategori) ?> {
            background-color: <?= $warna ?>;
        }
        <?php endforeach; ?>
        /* Responsive */
        @media (max-width: 576px) {
            .card {
                margin-bottom: 25px;
            }
        }
    </style>
</head>
<body>
<div class="container shadow-lg">
    <h1>Sistem Pesanan Menu</h1>
    <form id="orderForm" action="process_order.php" method="post" novalidate>
        <div class="mb-4">
            <label for="nomor_meja" class="form-label">Nomor Meja</label>
            <input type="text" class="form-control" id="nomor_meja" name="nomor_meja" placeholder="Masukkan nomor meja" required />
        </div>

        <?php foreach ($menu as $kategori => $items): ?>
            <h4 class="mt-5 mb-4 text-dark border-bottom border-3 border-primary pb-2"><?= htmlspecialchars($kategori) ?></h4>
            <div class="row g-4">
                <?php foreach ($items as $item): ?>
                    <?php 
                        $kelas_warna = "card-body-" . strtolower($kategori);
                        $status = $item['status'] ?? 'not ready'; // default not ready jika tidak ada status
                        $is_ready = ($status === 'ready');
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100">
                            <div class="card-body <?= htmlspecialchars($kelas_warna) ?>">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($item['nama']) ?>
                                    <?php if ($is_ready): ?>
                                        <span class="badge-ready">Ready</span>
                                    <?php else: ?>
                                        <span class="badge-notready">Not Ready</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="card-text">
                                    Harga: 
                                    <?= $item['harga'] !== null ? "Rp" . number_format($item['harga'], 0, ',', '.') : "<em>Harga belum tersedia</em>" ?>
                                </p>
                                <input 
                                    type="number" 
                                    min="0" 
                                    class="form-control" 
                                    name="pesanan[<?= htmlspecialchars($kategori) ?>][<?= htmlspecialchars($item['nama']) ?>]" 
                                    value="0" 
                                    <?= $is_ready ? '' : 'disabled' ?> 
                                />
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-success mt-5">Kirim Pesanan</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('orderForm').addEventListener('submit', function(event) {
        alert('Pesanan sedang dibuat, mohon menunggu...');
        // Form akan tetap submit setelah alert
    });
</script>
</body>
</html>
