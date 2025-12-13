<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Student Assistant') {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$student_id = $_SESSION['user']['student_id_number'];
$skill_name = trim($_POST['skillName'] ?? '');
$note = trim($_POST['skillNote'] ?? '');
$proof_path = '';

if (!$skill_name) {
    echo json_encode(["error" => "Skill name is required."]);
    exit();
}

// Handle file upload
if (isset($_FILES['proofUpload']) && $_FILES['proofUpload']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $filename = basename($_FILES["proofUpload"]["name"]);
    $target_file = $upload_dir . uniqid("proof_") . "_" . $filename;

    if (move_uploaded_file($_FILES["proofUpload"]["tmp_name"], $target_file)) {
        $proof_path = $target_file;
    }
}

$stmt = $conn->prepare("INSERT INTO skill_inventory (student_id, skill_name, note, proof_file) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $student_id, $skill_name, $note, $proof_path);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to add skill."]);
}

$stmt->close();
$conn->close();
