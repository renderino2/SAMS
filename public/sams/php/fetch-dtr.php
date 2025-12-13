<?php
require 'db.php';
header('Content-Type: application/json');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $sql = "SELECT 
                a.id,
                u.full_name AS name,
                a.student_id,
                u.office,
                a.date,
                a.time_in,
                a.time_out,
                a.status
            FROM attendance a
            JOIN users u ON a.student_id = u.student_id_number
            ORDER BY a.date DESC";

    $result = $conn->query($sql);
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "student_id" => $row["student_id"],
            "office" => $row["office"],
            "date" => $row["date"],
            "time_in" => $row["time_in"],
            "time_out" => $row["time_out"],
            "status" => $row["status"]
        ];
    }

    echo json_encode($data);
} catch (Exception $e) {
    echo json_encode(["error" => "Server Error", "message" => $e->getMessage()]);
}
$conn->close();
?>
