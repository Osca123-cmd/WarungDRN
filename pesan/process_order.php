<?php
require 'config.php'; // Pastikan koneksi database sudah benar

$nomor_meja = isset($_POST['nomor_meja']) ? trim($_POST['nomor_meja']) : '';
$pesanan = isset($_POST['pesanan']) ? $_POST['pesanan'] : [];

if ($nomor_meja === '') {
    die("Nomor meja harus diisi.");
}

if (empty($pesanan)) {
    die("Pesanan tidak boleh kosong.");
}

function getMenuId($conn, $kategori, $nama) {
    $sql = "SELECT m.id, m.harga FROM menu m
            JOIN kategori k ON m.kategori_id = k.id
            WHERE k.nama = ? AND m.nama = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $kategori, $nama);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row;
    }
    return null;
}

$conn->begin_transaction();

try {
    // Insert ke tabel pesanan
    $stmtPesanan = $conn->prepare("INSERT INTO pesanan (nomor_meja) VALUES (?)");
    $stmtPesanan->bind_param("s", $nomor_meja);
    $stmtPesanan->execute();
    $pesanan_id = $stmtPesanan->insert_id;
    $stmtPesanan->close();

    // Insert detail pesanan
    $stmtDetail = $conn->prepare("INSERT INTO detail_pesanan (pesanan_id, menu_id, jumlah) VALUES (?, ?, ?)");

    $ada_pesanan = false;
    $detail_pesanan = []; // Untuk menyimpan data pesanan yang berhasil dimasukkan

    foreach ($pesanan as $kategori => $items) {
        foreach ($items as $nama => $jumlah) {
            $jumlah = (int)$jumlah;
            if ($jumlah > 0) {
                $menuData = getMenuId($conn, $kategori, $nama);
                if ($menuData === null) {
                    throw new Exception("Menu '$nama' pada kategori '$kategori' tidak ditemukan.");
                }
                $menu_id = $menuData['id'];
                $harga = $menuData['harga'];

                $stmtDetail->bind_param("iii", $pesanan_id, $menu_id, $jumlah);
                $stmtDetail->execute();

                // Simpan data untuk ditampilkan nanti
                $detail_pesanan[] = [
                    'kategori' => $kategori,
                    'nama' => $nama,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $harga * $jumlah,
                ];

                $ada_pesanan = true;
            }
        }
    }

    if (!$ada_pesanan) {
        throw new Exception("Tidak ada item pesanan yang valid.");
    }

    $stmtDetail->close();

    $conn->commit();

    // Tampilkan hasil pesanan dengan tampilan menarik
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <title>Ringkasan Pesanan</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 20px;
                color: #333;
            }
            .container {
                max-width: 700px;
                margin: 0 auto;
                background: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            h2 {
                color: #27ae60;
                margin-bottom: 10px;
                text-align: center;
            }
            .info {
                font-size: 1.1em;
                margin-bottom: 20px;
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                padding: 12px 15px;
                border-bottom: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #27ae60;
                color: white;
                text-transform: uppercase;
                font-size: 0.9em;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
            tfoot td {
                font-weight: bold;
                font-size: 1.1em;
                border-top: 2px solid #27ae60;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #27ae60;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                transition: background-color 0.3s ease;
                text-align: center;
            }
            .btn:hover {
                background-color: #219150;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Pesanan Berhasil Disimpan</h2>
            <p class="info">Nomor Meja: <strong><?= htmlspecialchars($nomor_meja) ?></strong></p>

            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Harga (Rp)</th>
                        <th>Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($detail_pesanan as $item):
                        $total += $item['subtotal'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['kategori']) ?></td>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= $item['jumlah'] ?></td>
                        <td><?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;">Total Harga:</td>
                        <td><?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>

            <div style="text-align:center;">
                <a href="menu.php" class="btn">Kembali ke Form Pemesanan</a>
            </div>
        </div>
    </body>
    </html>
    <?php

} catch (Exception $e) {
    $conn->rollback();
    echo "<p style='color:red; font-weight:bold;'>Terjadi kesalahan saat menyimpan pesanan: " . htmlspecialchars($e->getMessage()) . "</p>";
}

$conn->close();
?>
