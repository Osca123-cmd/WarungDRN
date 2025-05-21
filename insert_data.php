<?php
require 'config.php';

$kategori = ['Miso', 'Soto', 'Geprek', 'Mie', 'Minuman', 'Cemilan'];

$menu = [
    'Miso' => [
        ['Babat', 8000],
        ['Cincang', 8000],
        ['Ayam Bacok', 10000],
        ['Ayam Suir', 8000],
        ['Bakso', 8000],
        ['Mie Ayam', 8000],
        ['Mie Ayam Bakso', 15000],
    ],
    'Soto' => [
        ['Babat', 10000],
        ['Ayam', 10000],
        ['Cincang', 10000],
        ['Nasi Soto', 15000],
    ],
    'Geprek' => [
        ['Sambal Doer', 13000],
    ],
    'Mie' => [
        ['Mie Goreng', 8000],
        ['Indomie Goreng', 8000],
        ['Tumis', 8000],
    ],
    'Minuman' => [
        ['Mandi', 5000],
        ['Tebot', 5000],
        ['Fruit', 5000],
        ['Badak', 10000],
        ['Kurnia', 5000],
        ['Nutrisari', 5000],
        ['Hilo', 7000],
        ['Cappucino', 7000],
    ],
    'Cemilan' => [
        ['Sate Kerang', null],
        ['Bakuan', null],
        ['Kacang', null],
        ['Telur Rebus', null],
    ],
];

$conn->begin_transaction();

try {
    // Insert kategori jika belum ada
    $stmtCheck = $conn->prepare("SELECT id FROM kategori WHERE nama = ?");
    $stmtInsertKategori = $conn->prepare("INSERT INTO kategori (nama) VALUES (?)");

    foreach ($kategori as $namaKategori) {
        $stmtCheck->bind_param("s", $namaKategori);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows === 0) {
            $stmtInsertKategori->bind_param("s", $namaKategori);
            $stmtInsertKategori->execute();
        }
    }
    $stmtCheck->close();
    $stmtInsertKategori->close();

    // Insert menu
    $stmtMenu = $conn->prepare("INSERT INTO menu (kategori_id, nama, harga) VALUES (?, ?, ?)");

    foreach ($menu as $namaKategori => $items) {
        $result = $conn->query("SELECT id FROM kategori WHERE nama = '" . $conn->real_escape_string($namaKategori) . "' LIMIT 1");
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $kategori_id = $row['id'];

            foreach ($items as $item) {
                $namaMenu = $item[0];
                $harga = $item[1];
                $stmtMenu->bind_param("isi", $kategori_id, $namaMenu, $harga);
                $stmtMenu->execute();
            }
        } else {
            throw new Exception("Kategori tidak ditemukan: $namaKategori");
        }
    }
    $stmtMenu->close();

    $conn->commit();
    echo "Data kategori dan menu berhasil dimasukkan ke database.";
} catch (Exception $e) {
    $conn->rollback();
    echo "Terjadi kesalahan: " . $e->getMessage();
}

$conn->close();
?>
