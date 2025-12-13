<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "sams");

if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT name, office, attendance, late, absences, eval_score FROM performance_reports";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = [
    "name" => $row['name'],
    "office" => $row['office'],
    "attendance" => $row['attendance'] . '%',
    "late" => $row['late'],
    "absences" => $row['absences'],
    "evalScore" => $row['eval_score']
  ];
}

echo json_encode($data);
$conn->close();
