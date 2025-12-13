<?php
header('Content-Type: application/json');

// Enable error reporting for debugging (remove or disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = new mysqli("localhost", "root", "", "sams");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Query: Get summary of attendance grouped by student
$sql = "SELECT 
            sa.full_name, 
            sa.student_id_number,
            COUNT(a.id) AS total_attendance,
            SUM(CASE WHEN a.time_out IS NULL THEN 1 ELSE 0 END) AS incomplete_logs
        FROM attendance a
        JOIN users sa ON a.student_id = sa.student_id_number
        WHERE sa.role = 'Student Assistant'
        GROUP BY sa.student_id_number";

$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "student_id" => $row["student_id_number"],
            "full_name" => $row["full_name"],
            "total_attendance" => $row["total_attendance"],
            "incomplete_logs" => $row["incomplete_logs"]
        ];
    }

    echo json_encode($data);
} else {
    echo json_encode([]);
}

$conn->close();
?>
