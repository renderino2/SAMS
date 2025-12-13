<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['student_id_number'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$student_id = $_SESSION['student_id_number'];
$newContact = $_POST['newContact'] ?? '';

if (!preg_match('/^09\d{9}$/', $newContact)) {
    echo json_encode(["success" => false, "message" => "Invalid contact format"]);
    exit;
}

$stmt = $conn->prepare("UPDATE student_assistants SET contact = ? WHERE student_id_number = ?");
$stmt->bind_param("ss", $newContact, $student_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Contact updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Update failed."]);
}
