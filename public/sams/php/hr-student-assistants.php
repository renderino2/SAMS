<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $studentId = $_POST["studentId"];
  $office = $_POST["office"];
  $status = $_POST["status"];
  $notes = $_POST["notes"];

  $stmt = $conn->prepare("INSERT INTO student_assistants (full_name, student_id, office, status, notes) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $studentId, $office, $status, $notes);

  if ($stmt->execute()) {
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
  }

  $stmt->close();
  $conn->close();
}
?>
