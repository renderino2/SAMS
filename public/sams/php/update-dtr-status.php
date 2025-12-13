<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../db.php';
header('Content-Type: application/json');

// 📥 Decode incoming JSON
$data = json_decode(file_get_contents("php://input"), true);

// ❗ Validate
if (!$data || !isset($data['id']) || !isset($data['action'])) {
    echo json_encode(["success" => false, "error" => "Missing required fields."]);
    exit;
}

$id = intval($data['id']);
$action = trim($data['action']);
$remarks = trim($data['remarks'] ?? '');

// ✅ Prepare & bind
$stmt = $conn->prepare("UPDATE dtr_records SET status = ?, remarks = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "SQL error: " . $conn->error]);
    exit;
}
$stmt->bind_param("ssi", $action, $remarks, $id);

// ✅ Execute & respond
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Execute error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
