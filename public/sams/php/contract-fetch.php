<?php
// php/contract-fetch.php
header('Content-Type: application/json');
session_start();

// ✅ Connect to DB
require_once 'db.php';

// ✅ Access Control: Only HR can fetch contract data
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'HR') {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// ✅ SQL Query to join contracts and user data
$sql = "
    SELECT 
        c.contract_id, 
        c.student_id_number, 
        u.full_name, 
        u.office, 
        c.start_date, 
        c.end_date, 
        c.status
    FROM contracts c
    JOIN users u ON c.student_id_number = u.student_id_number
    ORDER BY c.end_date DESC
";

// ✅ Execute query
$result = $conn->query($sql);

// ✅ Error check
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
    exit;
}

// ✅ Prepare data array
$contracts = [];
while ($row = $result->fetch_assoc()) {
    $contracts[] = $row;
}

// ✅ Return JSON data
echo json_encode($contracts);
exit;
?>
