<?php
// eval_fetch.php
header('Content-Type: application/json');

// Connect to DB
$conn = new mysqli("localhost", "root", "", "sams");
if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "Database connection failed"]);
  exit;
}

$sql = "SELECT id, evaluation_date, student_name, office, rated_by, average_rating, status FROM evaluations ORDER BY evaluation_date DESC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode(["success" => true, "data" => $data]);
$conn->close();
