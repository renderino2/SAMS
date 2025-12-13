<?php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$student_id = $_POST['student_id_number'] ?? '';
$start_date = $_POST['contract_start'] ?? '';
$end_date = $_POST['contract_end'] ?? '';
$status = $_POST['contract_status'] ?? 'Pending';

if (!$student_id || !$start_date || !$end_date) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO contracts (student_id_number, start_date, end_date, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $student_id, $start_date, $end_date, $status);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
