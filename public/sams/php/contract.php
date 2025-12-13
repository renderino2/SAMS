<?php
header('Content-Type: application/json');

// Enable error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';

$query = "SELECT c.contract_id, c.student_id_number, u.full_name, c.start_date, c.end_date, c.status, u.office
          FROM contracts c
          JOIN users u ON c.student_id_number = u.student_id_number
          ORDER BY c.end_date DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . mysqli_error($conn)]);
    exit;
}

$contracts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $contracts[] = $row;
}

echo json_encode($contracts);
?>
