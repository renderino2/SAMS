<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'HR') {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$name     = trim($_POST['name'] ?? '');
$id       = trim($_POST['id'] ?? '');
$office   = trim($_POST['office'] ?? '');
$skill    = trim($_POST['skill'] ?? '');
$date     = $_POST['date'] ?? date("Y-m-d");
$status   = $_POST['status'] ?? 'Not Verified';
$remarks  = trim($_POST['remarks'] ?? '');

if (!$id || !$skill || !$date) {
    echo json_encode(["error" => "Required fields missing."]);
    exit;
}

// Insert skill
$is_verified = $status === 'Verified' ? 1 : 0;
$stmt = $conn->prepare("INSERT INTO skill_inventory (student_id, skill_name, note, added_on, is_verified) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $id, $skill, $remarks, $date, $is_verified);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to add skill."]);
}
