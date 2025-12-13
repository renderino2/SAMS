<?php
require_once 'db.php';

$query = "SELECT * FROM student_assistants ORDER BY created_at DESC";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = [
    "name" => $row["full_name"],
    "student_id" => $row["student_id"],
    "office" => $row["office"],
    "status" => $row["status"],
    "notes" => $row["notes"]
  ];
}

header("Content-Type: application/json");
echo json_encode($data);
?>
