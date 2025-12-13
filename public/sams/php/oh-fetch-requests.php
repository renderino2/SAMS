<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');
require_once "db.php";

// ✅ Validate session and role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Office Head') {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// ✅ Get all student assistant requests
$sql = "SELECT 
            r.id,
            u.full_name AS name,
            u.student_id_number AS student_id,
            r.request_type AS type,
            r.created_at AS date,
            r.status,
            r.message AS reason
        FROM requests r
        JOIN users u ON u.student_id_number = r.student_id
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Server error: " . $conn->error]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
