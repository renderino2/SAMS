<?php
session_start();
require_once "db.php";

header('Content-Type: application/json'); // ✅ Ensure JSON response

// ✅ Session and role check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Office Head') {
    http_response_code(403);
    echo json_encode(["success" => false, "error" => "Forbidden"]);
    exit();
}

// ✅ Validate input
$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !in_array($status, ['Approved', 'Rejected'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid input"]);
    exit();
}

// ✅ Perform the update
$stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Failed to update request"]);
}
