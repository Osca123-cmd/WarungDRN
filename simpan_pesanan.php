<?php
require 'config.php';

$nomor_meja = isset($_POST['nomor_meja']) ? trim($_POST['nomor_meja']) : '';
$pesanan = isset($_POST['pesanan']) ? $_POST['pesanan'] : [];

if ($nomor_meja === '') {
    die("Nomor meja harus diisi.");
}

if (empty($pesanan)) {
    die("Pesanan tidak boleh kosong.");
}

function getMenuId($conn, $kategori, $nama) {
    $sql = "SELECT m.id FROM menu m
            JOIN kategori k ON m.kategori_id = k.id
            WHERE k.nama = ? AND m.nama = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $kategori, $nama);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    }
    return null;
}

// Gabungkan pesanan yang sama
$gabungan_pesanan = [];
foreach ($pesanan as $kategori => $items) {
    foreach ($items as $nama => $jumlah) {
        $jumlah = (int)$jumlah;
        if ($jumlah > 0) {
            $key = $kategori . '||' . $nama;
            if (isset($gabungan_pesanan[$key])) {
                $gabungan_pesanan[$key]['jumlah'] += $jumlah;
            } else {
                $gabungan_pesanan[$key] = [
                    'kategori' => $kategori,
                    'nama' => $nama,
                    'jumlah' => $jumlah
                ];
            }
        }
    }
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

    foreach ($gabungan_pesanan as $item) {
        $menu_id = getMenuId($conn, $item['kategori'], $item['nama']);
        if ($menu_id === null) {
            throw new Exception("Menu '{$item['nama']}' pada kategori '{$item['kategori']}' tidak ditemukan.");
        }
        $stmtDetail->bind_param("iii", $pesanan_id, $menu_id, $item['jumlah']);
        $stmtDetail->execute();
    }
    $stmtDetail->close();

    $conn->commit();

    echo "<h2>Pesanan berhasil disimpan. Terima kasih!</h2>";
    echo "<h3>Ringkasan Pesanan:</h3>";
    echo "<p>Nomor Meja: " . htmlspecialchars($nomor_meja) . "</p>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr><th>Kategori</th><th>Menu</th><th>Jumlah</th></tr>";
    foreach ($gabungan_pesanan as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['kategori']) . "</td>";
        echo "<td>" . htmlspecialchars($item['nama']) . "</td>";
        echo "<td>" . (int)$item['jumlah'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    if ($e->getCode() == 1062) { // Duplicate entry error code
        echo "Pesanan duplikat terdeteksi. Silakan periksa kembali pesanan Anda.";
    } else {
        echo "Terjadi kesalahan saat menyimpan pesanan: " . $e->getMessage();
    }
} catch (Exception $e) {
    $conn->rollback();
    echo "Terjadi kesalahan saat menyimpan pesanan: " . $e->getMessage();
}

$conn->close();
?>
