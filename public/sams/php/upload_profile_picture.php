<?php
session_start();
include 'db.php';
$hr_id = $_SESSION['hr_id'] ?? 1;

if ($_FILES['file']) {
  $file = $_FILES['file'];
  $targetDir = "../assets/uploads/";
  $filename = uniqid() . "_" . basename($file['name']);
  $targetPath = $targetDir . $filename;

  if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    $sql = "UPDATE hr_accounts SET profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $filename, $hr_id);
    $stmt->execute();
    echo "Uploaded";
  } else {
    echo "Upload failed";
  }
}
?>
