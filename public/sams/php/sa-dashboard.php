<?php
session_start();
header('Content-Type: application/json'); // Always JSON

require_once "db.php";

// Turn on error reporting for debug (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Student Assistant') {
    echo json_encode(["error" => "Unauthorized or session expired"]);
    exit();
}

$student_id = $_SESSION['user']['student_id_number'];
$today = date("Y-m-d");

// Get today's attendance
$stmt = $conn->prepare("
    SELECT time_in, time_out, TIMESTAMPDIFF(MINUTE, time_in, time_out) AS total_minutes 
    FROM attendance 
    WHERE student_id = ? AND DATE(log_date) = ?
");
if (!$stmt) {
    echo json_encode(["error" => "SQL Prepare failed: " . $conn->error]);
    exit();
}
$stmt->bind_param("ss", $student_id, $today);
$stmt->execute();
$result = $stmt->get_result();
$attendance = $result->fetch_assoc();

$time_in = $attendance['time_in'] ?? '—';
$time_out = $attendance['time_out'] ?? '—';
$total_minutes = $attendance['total_minutes'] ?? null;
$total_hours = $total_minutes ? round($total_minutes / 60, 2) . ' hrs' : '—';

// Get latest contract request
$stmt = $conn->prepare("
    SELECT contract_status, request_type, requested_on 
    FROM contract_requests 
    WHERE student_id = ? 
    ORDER BY requested_on DESC LIMIT 1
");
if (!$stmt) {
    echo json_encode(["error" => "SQL Prepare failed: " . $conn->error]);
    exit();
}
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

$contract_status = $request['contract_status'] ?? '—';
$last_request = $request 
    ? $request['request_type'] . " on " . date("M d, Y", strtotime($request['requested_on'])) 
    : '—';

echo json_encode([
    "time_in" => $time_in,
    "time_out" => $time_out,
    "total_hours" => $total_hours,
    "contract_status" => $contract_status,
    "last_request" => $last_request
]);
