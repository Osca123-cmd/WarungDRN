<?php
require 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID pesanan tidak valid.");
}

$pesanan_id = (int)$_GET['id'];

// Ambil info pesanan
$stmt = $conn->prepare("SELECT nomor_meja FROM pesanan WHERE id = ?");
$stmt->bind_param("i", $pesanan_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Pesanan tidak ditemukan.");
}
$pesanan_info = $result->fetch_assoc();
$stmt->close();

// Ambil data kategori
$kategori_result = $conn->query("SELECT * FROM kategori ORDER BY nama");
$kategori_list = [];
while ($row = $kategori_result->fetch_assoc()) {
    $kategori_list[$row['id']] = $row['nama'];
}

// Ambil data menu per kategori
$menu_result = $conn->query("SELECT * FROM menu ORDER BY kategori_id, nama");
$menu_list = [];
while ($row = $menu_result->fetch_assoc()) {
    $menu_list[$row['kategori_id']][] = $row;
}

// Ambil detail pesanan saat ini
$stmt = $conn->prepare("SELECT menu_id, jumlah FROM detail_pesanan WHERE pesanan_id = ?");
$stmt->bind_param("i", $pesanan_id);
$stmt->execute();
$result = $stmt->get_result();
$current_details = [];
while ($row = $result->fetch_assoc()) {
    $current_details[$row['menu_id']] = $row['jumlah'];
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_meja = trim($_POST['nomor_meja'] ?? '');
    $pesanan_baru = $_POST['pesanan'] ?? [];

    if ($nomor_meja === '') {
        $error = "Nomor meja harus diisi.";
    } else {
        $conn->begin_transaction();
        try {
            // Update nomor meja
            $stmt = $conn->prepare("UPDATE pesanan SET nomor_meja = ? WHERE id = ?");
            $stmt->bind_param("si", $nomor_meja, $pesanan_id);
            $stmt->execute();
            $stmt->close();

            // Hapus detail lama
            $stmt = $conn->prepare("DELETE FROM detail_pesanan WHERE pesanan_id = ?");
            $stmt->bind_param("i", $pesanan_id);
            $stmt->execute();
            $stmt->close();

            // Insert detail baru
            $stmt = $conn->prepare("INSERT INTO detail_pesanan (pesanan_id, menu_id, jumlah) VALUES (?, ?, ?)");
            foreach ($pesanan_baru as $menu_id => $jumlah) {
                $jumlah = (int)$jumlah;
                $menu_id = (int)$menu_id;
                if ($jumlah > 0) {
                    $stmt->bind_param("iii", $pesanan_id, $menu_id, $jumlah);
                    $stmt->execute();
                }
            }
            $stmt->close();

            $conn->commit();
            header("Location: admin.php?msg=Pesanan berhasil diperbarui");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Terjadi kesalahan saat memperbarui pesanan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Pesanan #<?= htmlspecialchars($pesanan_id) ?></title>
    <style>
        /* Styling konsisten dengan index.php dan tambahan warna latar */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e6f0ff; /* Warna latar belakang body yang lembut biru muda */
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff; /* Warna latar belakang container putih bersih */
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
        }
        label {
            font-weight: 600;
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            color: #34495e;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #2980b9;
            outline: none;
            box-shadow: 0 0 8px rgba(41, 128, 185, 0.3);
        }
        .kategori-section {
            margin-top: 30px;
            padding: 15px 20px;
            background-color: #f0f7ff; /* Warna latar kategori yang lembut */
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .kategori-title {
            font-size: 1.2em;
            font-weight: 700;
            margin-bottom: 15px;
            color: #2980b9;
            border-bottom: 2px solid #2980b9;
            padding-bottom: 5px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .menu-item label {
            flex: 1;
            font-weight: 500;
            color: #2c3e50;
        }
        .menu-item input[type="number"] {
            width: 80px;
            margin-left: 15px;
            font-size: 1em;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: border-color 0.3s ease;
        }
        .menu-item input[type="number"]:focus {
            border-color: #2980b9;
            outline: none;
            box-shadow: 0 0 8px rgba(41, 128, 185, 0.3);
        }
        .btn-submit {
            margin-top: 30px;
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 14px 30px;
            font-size: 1.1em;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #1c5980;
        }
        .error {
            margin-top: 15px;
            color: #e74c3c;
            font-weight: 700;
            text-align: center;
        }
        .back-link {
            display: block;
            margin-top: 25px;
            text-align: center;
            color: #2980b9;
            font-weight: 600;
            text-decoration: none;
            font-size: 1em;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .menu-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .menu-item input[type="number"] {
                margin-left: 0;
                margin-top: 6px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Pesanan #<?= htmlspecialchars($pesanan_id) ?></h1>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nomor_meja">Nomor Meja:</label>
            <input type="text" id="nomor_meja" name="nomor_meja" value="<?= htmlspecialchars($_POST['nomor_meja'] ?? $pesanan_info['nomor_meja']) ?>" required />

            <?php foreach ($kategori_list as $kategori_id => $kategori_nama): ?>
                <div class="kategori-section">
                    <div class="kategori-title"><?= htmlspecialchars($kategori_nama) ?></div>
                    <?php if (isset($menu_list[$kategori_id])): ?>
                        <?php foreach ($menu_list[$kategori_id] as $menu_item): 
                            $menu_id = $menu_item['id'];
                            $menu_nama = $menu_item['nama'];
                            $jumlah = $_POST['pesanan'][$menu_id] ?? ($current_details[$menu_id] ?? 0);
                        ?>
                            <div class="menu-item">
                                <label for="menu_<?= $menu_id ?>"><?= htmlspecialchars($menu_nama) ?> (Rp <?= number_format($menu_item['harga'], 0, ',', '.') ?>)</label>
                                <input type="number" id="menu_<?= $menu_id ?>" name="pesanan[<?= $menu_id ?>]" min="0" value="<?= (int)$jumlah ?>" />
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Tidak ada menu di kategori ini.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>

        <a href="admin.php" class="back-link">&larr; Kembali ke Data Pesanan</a>
    </div>
</body>
</html>
