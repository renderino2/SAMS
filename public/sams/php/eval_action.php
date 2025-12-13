<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "sams");

if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "DB connection failed."]);
  exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';
$id = $data['id'] ?? 0;

if ($action === 'markReviewed' && $id > 0) {
  $stmt = $conn->prepare("UPDATE evaluation_reviews SET status = 'Reviewed' WHERE id = ?");
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "message" => "Update failed."]);
  }
  $stmt->close();
} else {
  echo json_encode(["success" => false, "message" => "Invalid action or ID."]);
}

$conn->close();
?>
