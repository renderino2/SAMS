<?php
require 'db.php';

$student_id = $_GET['student_id'] ?? '';

$stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = ? ORDER BY date DESC");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
  $rows[] = $row;
}

echo json_encode($rows);

$stmt->close();
$conn->close();
?>
