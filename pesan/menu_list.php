<?php
// menu_list.php
require 'config.php';

// Ambil data kategori dan menu
$sql = "SELECT k.nama AS kategori, m.nama AS menu, m.harga 
        FROM menu m 
        JOIN kategori k ON m.kategori_id = k.id
        ORDER BY k.id, m.nama";

$result = $conn->query($sql);

$menu = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kategori = $row['kategori'];
        if (!isset($menu[$kategori])) {
            $menu[$kategori] = [];
        }
        $menu[$kategori][] = [
            'nama' => $row['menu'],
            'harga' => $row['harga']
        ];
    }
} else {
    echo "Tidak ada data menu.";
    exit;
}

// Tampilkan data menu (contoh sederhana)
foreach ($menu as $kategori => $items) {
    echo "<h3>" . htmlspecialchars($kategori) . "</h3><ul>";
    foreach ($items as $item) {
        echo "<li>" . htmlspecialchars($item['nama']) . " - Rp" . number_format($item['harga'], 0, ',', '.') . "</li>";
    }
    echo "</ul>";
}

$conn->close();
?>
