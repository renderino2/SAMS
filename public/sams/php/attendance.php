<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

// Make sure user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Student Assistant') {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);
if (!$action) $action = $data['action'] ?? '';

$studentId = $_SESSION['user']['student_id_number'];
$name = $_SESSION['user']['full_name'];
$office = $_SESSION['user']['office'];
$date = date("Y-m-d");

if ($action === 'time_in') {
    $timeIn = date("H:i:s");

    // Check if already timed in
    $check = $conn->prepare("SELECT id FROM attendance WHERE student_id = ? AND date = ?");
    $check->bind_param("ss", $studentId, $date);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "You already timed in today."]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO attendance (name, student_id, office, date, time_in, status) VALUES (?, ?, ?, ?, ?, 'Present')");
    $stmt->bind_param("sssss", $name, $studentId, $office, $date, $timeIn);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "time_in" => date("g:i A", strtotime($timeIn))]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error."]);
    }
    exit();
}

if ($action === 'time_out') {
    $timeOut = date("H:i:s");

    $stmt = $conn->prepare("UPDATE attendance SET time_out = ? WHERE student_id = ? AND date = ?");
    $stmt->bind_param("sss", $timeOut, $studentId, $date);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "time_out" => date("g:i A", strtotime($timeOut))]);
    } else {
        echo json_encode(["success" => false, "message" => "Time Out update failed."]);
    }
    exit();
}

if ($action === 'fetch') {
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = ? ORDER BY date DESC LIMIT 10");
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $records = [];

    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    echo json_encode(["records" => $records]);
    exit();
}

echo json_encode(["error" => "Invalid action"]);
