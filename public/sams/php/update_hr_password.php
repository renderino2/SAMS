<?php
session_start();
include 'db.php';
$data = json_decode(file_get_contents("php://input"));
$current = $data->current;
$new = $data->new;
$hr_id = $_SESSION['hr_id'] ?? 1;

$sql = "SELECT password_hash FROM hr_accounts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hr_id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($current, $hash)) {
  echo "incorrect";
  exit;
}

$newHash = password_hash($new, PASSWORD_BCRYPT);
$sql = "UPDATE hr_accounts SET password_hash = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newHash, $hr_id);
echo $stmt->execute() ? "success" : "error";
?>
