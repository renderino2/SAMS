<?php
header('Content-Type: application/json');
require_once 'db.php';

$query = $conn->query("SELECT * FROM skill_inventory ORDER BY date_added DESC");
$data = $query->fetch_all(MYSQLI_ASSOC);
echo json_encode(['success'=>true, 'data'=>$data]);
$conn->close();
