<?php
header('Content-Type: application/json');

// Optional: show PHP errors during development
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require '../db.php';

// Read and decode JSON input
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

// Validate input
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid JSON input"]);
    exit;
}

// Sanitize and assign fields
$name      = trim($data['name'] ?? '');
$studentId = trim($data['student_id'] ?? '');
$office    = trim($data['assigned_office'] ?? '');
$status    = trim($data['status'] ?? '');
$notes     = trim($data['service_record'] ?? '');

// Basic validation
if ($name === '' || $studentId === '' || $office === '' || $status === '') {
    http_response_code(422); // Unprocessable Entity
    echo json_encode([
        "success" => false,
        "error" => "Missing required fields: name, student_id, assigned_office, status"
    ]);
    exit;
}

// Prepare SQL statement
$stmt = $conn->prepare("
    INSERT INTO student_assistants (full_name, student_id, assigned_office, status, service_record)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Database error: " . $conn->error]);
    exit;
}

// Bind and execute
$stmt->bind_param("sssss", $name, $studentId, $office, $status, $notes);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Execute failed: " . $stmt->error]);
}

// Cleanup
$stmt->close();
$conn->close();
