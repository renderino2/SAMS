<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Student Assistant") {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$request_type = trim($data['request_type'] ?? '');
$message = trim($data['message'] ?? '');
$studentId = $_SESSION["user"]["student_id_number"];

if ($request_type && $message) {
    $stmt = $conn->prepare("INSERT INTO requests (student_id, request_type, message, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("sss", $studentId, $request_type, $message);
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
        exit();
    }
}

echo json_encode(["success" => false]);
