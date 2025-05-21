<?php
require 'config.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT COUNT(*) AS count FROM pesanan WHERE is_read = 0");
$count = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $count = (int)$row['count'];
}

echo json_encode(['newOrdersCount' => $count]);
?>
