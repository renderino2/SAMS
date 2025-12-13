<?php
session_start();
include 'db.php';
$hr_id = $_SESSION['hr_id'] ?? 1;
$sql = "UPDATE hr_accounts SET is_deactivated = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hr_id);
echo $stmt->execute() ? "deactivated" : "error";
?>
