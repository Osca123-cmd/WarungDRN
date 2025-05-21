<?php
require 'config.php';

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    // Delete order and its details (due to ON DELETE CASCADE)
    $stmtDel = $conn->prepare("DELETE FROM pesanan WHERE id = ?");
    $stmtDel->bind_param("i", $delete_id);
    $stmtDel->execute();
    $stmtDel->close();
    header("Location: admin.php");
    exit;
}

// Fetch count of new orders (is_read = 0)
$newOrdersCount = 0;
$resultNew = $conn->query("SELECT COUNT(*) AS count FROM pesanan WHERE is_read = 0");
if ($resultNew) {
    $rowNew = $resultNew->fetch_assoc();
    $newOrdersCount = (int)$rowNew['count'];
}

// Fetch all orders with details
$sql = "
    SELECT 
        p.id AS pesanan_id,
        p.nomor_meja,
        p.tanggal,
        m.nama AS menu_nama,
        k.nama AS kategori_nama,
        dp.jumlah,
        m.harga,
        (dp.jumlah * m.harga) AS subtotal
    FROM pesanan p
    JOIN detail_pesanan dp ON p.id = dp.pesanan_id
    JOIN menu m ON dp.menu_id = m.id
    JOIN kategori k ON m.kategori_id = k.id
    ORDER BY p.tanggal DESC, p.id DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}

// Group orders by pesanan_id
$pesanan_list = [];
while ($row = $result->fetch_assoc()) {
    $pesanan_id = $row['pesanan_id'];
    if (!isset($pesanan_list[$pesanan_id])) {
        $pesanan_list[$pesanan_id] = [
            'nomor_meja' => $row['nomor_meja'],
            'tanggal' => $row['tanggal'],
            'items' => [],
            'total' => 0,
        ];
    }
    $pesanan_list[$pesanan_id]['items'][] = [
        'kategori' => $row['kategori_nama'],
        'nama' => $row['menu_nama'],
        'jumlah' => $row['jumlah'],
        'harga' => $row['harga'],
        'subtotal' => $row['subtotal'],
    ];
    $pesanan_list[$pesanan_id]['total'] += $row['subtotal'];
}

// Mark all new orders as read
$conn->query("UPDATE pesanan SET is_read = 1 WHERE is_read = 0");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Data Pesanan</title>
    <style>
        /* (Tetap gunakan style yang sudah ada) */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #f4f6f8;
            color: #333;
        }
        h1 {
            margin-bottom: 10px;
            text-align: center;
            color: #2c3e50;
        }
        .notification {
            max-width: 900px;
            margin: 0 auto 20px;
            text-align: right;
            font-weight: bold;
            font-size: 1.1em;
        }
        .notification .badge {
            background-color: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
        }
        /* Style lainnya tetap sama seperti sebelumnya */
        .pesanan-container {
            max-width: 900px;
            margin: 0 auto 40px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px 30px;
        }
        .pesanan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2980b9;
            color: white;
            padding: 10px 15px;
            border-radius: 6px 6px 0 0;
            font-weight: bold;
            font-size: 1.1em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        tr:hover {
            background-color: #f1f9ff;
        }
        tfoot td {
            font-weight: bold;
            font-size: 1.1em;
            border-top: 2px solid #2980b9;
            text-align: right;
        }
        .action-buttons {
            margin-top: 15px;
            text-align: right;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin-left: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            user-select: none;
        }
        .btn-edit {
            background-color: #27ae60;
            color: white;
        }
        .btn-edit:hover {
            background-color: #219150;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .no-data {
            text-align: center;
            font-size: 1.2em;
            color: #7f8c8d;
            margin-top: 50px;
        }
    </style>
</head>
<script>
function updateNotification() {
    fetch('check_new_orders.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification .badge');
            const notificationDiv = document.querySelector('.notification');
            if (data.newOrdersCount > 0) {
                if (badge) {
                    badge.textContent = 'Pesanan Baru: ' + data.newOrdersCount;
                } else {
                    const span = document.createElement('span');
                    span.className = 'badge';
                    span.textContent = 'Pesanan Baru: ' + data.newOrdersCount;
                    notificationDiv.innerHTML = '';
                    notificationDiv.appendChild(span);
                }
            } else {
                notificationDiv.textContent = 'Tidak ada pesanan baru.';
            }
        })
        .catch(error => {
            console.error('Error fetching new orders:', error);
        });
}

// Jalankan updateNotification setiap 10 detik
setInterval(updateNotification, 10000);

// Jalankan sekali saat halaman dimuat
updateNotification();
</script>

<body>
    <h1>Data Pesanan</h1>

    <div class="notification">
        <?php if ($newOrdersCount > 0): ?>
            <span class="badge">Pesanan Baru: <?= $newOrdersCount ?></span>
        <?php else: ?>
            <span>Tidak ada pesanan baru.</span>
        <?php endif; ?>
    </div>

    <?php if (empty($pesanan_list)): ?>
        <p class="no-data">Belum ada data pesanan.</p>
    <?php else: ?>
        <?php foreach ($pesanan_list as $id => $pesanan): ?>
            <div class="pesanan-container">
                <div class="pesanan-header">
                    <div>
                        Pesanan ID: <?= htmlspecialchars($id) ?> |
                        Nomor Meja: <?= htmlspecialchars($pesanan['nomor_meja']) ?> |
                        Tanggal: <?= htmlspecialchars($pesanan['tanggal']) ?>
                    </div>
                    <div class="action-buttons">
                        <a href="edit_order.php?id=<?= $id ?>" class="btn btn-edit" title="Edit Pesanan">Edit</a>
                        <a href="admin.php?delete=<?= $id ?>" class="btn btn-delete" title="Hapus Pesanan" onclick="return confirm('Yakin ingin menghapus pesanan ini?');">Hapus</a>
                    </div>
                </div>
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
                        <?php foreach ($pesanan['items'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['kategori']) ?></td>
                                <td><?= htmlspecialchars($item['nama']) ?></td>
                                <td><?= htmlspecialchars($item['jumlah']) ?></td>
                                <td><?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total Harga:</td>
                            <td><?= number_format($pesanan['total'], 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
