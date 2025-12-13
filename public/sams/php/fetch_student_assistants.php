<?php
// Enable detailed error reporting (dev only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set correct content-type
header('Content-Type: application/json');

// Include DB connection
require '../db.php';

// Verify connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$sql = "SELECT full_name, student_id, assigned_office, status, service_record FROM student_assistants ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit();
}

$assistants = [];

while ($row = $result->fetch_assoc()) {
    $assistants[] = $row;
}

echo json_encode($assistants);
$conn->close();
?>
