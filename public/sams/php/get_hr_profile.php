<?php
session_start();
include 'db.php';
$hr_id = $_SESSION['hr_id'] ?? 1;

$sql = "SELECT full_name, email, profile_picture FROM hr_accounts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hr_id);
$stmt->execute();
$result = $stmt->get_result();
echo json_encode($result->fetch_assoc());
?>
