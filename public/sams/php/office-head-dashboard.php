<?php
require 'db.php';
header('Content-Type: application/json');

// Prepare default JSON response
$response = [
    'success' => false,
    'assistants' => 0,
    'present' => 0,
    'evaluated' => 0,
    'dtr' => [],
    'error' => null
];

// Error handler to capture PHP errors and still return JSON
set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$response) {
    $response['error'] = "PHP Error: $errstr in $errfile on line $errline";
    echo json_encode($response);
    exit;
});

try {
    // Check DB connection
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Count assigned student assistants
    $stmt1 = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'Student Assistant'");
    if ($stmt1) {
        $row = $stmt1->fetch_assoc();
        $response['assistants'] = (int)($row['total'] ?? 0);
    } else {
        throw new Exception("Error fetching assistants: " . $conn->error);
    }

    // Attendance today
    $stmt2 = $conn->query("SELECT COUNT(DISTINCT student_id) AS present FROM attendance WHERE DATE(date) = CURDATE()");
    if ($stmt2) {
        $row = $stmt2->fetch_assoc();
        $response['present'] = (int)($row['present'] ?? 0);
    } else {
        throw new Exception("Error fetching attendance: " . $conn->error);
    }

    // Dummy evaluation count (replace with real query if needed)
    $response['evaluated'] = rand(5, 20);

    // DTR Summary for today
    $stmt3 = $conn->query("
        SELECT 
            u.full_name AS name, 
            a.date, 
            a.time_in, 
            a.time_out, 
            a.status
        FROM attendance a
        JOIN users u ON a.student_id = u.student_id_number
        WHERE DATE(a.date) = CURDATE()
        ORDER BY a.time_in ASC
    ");
    if ($stmt3) {
        while ($row = $stmt3->fetch_assoc()) {
            $response['dtr'][] = $row;
        }
    } else {
        throw new Exception("Error fetching DTR: " . $conn->error);
    }

    $response['success'] = true;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
