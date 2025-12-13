<?php
session_start();
header('Content-Type: application/json');
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Student Assistant') {
    echo json_encode(["error" => "Unauthorized. Please log in."]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$today = date("Y-m-d");

// Time In/Out + Hours Today
$dtrQuery = $conn->prepare("SELECT time_in, time_out FROM dtr WHERE user_id = ? AND date = ?");
$dtrQuery->bind_param("is", $user_id, $today);
$dtrQuery->execute();
$dtrResult = $dtrQuery->get_result()->fetch_assoc();

$timeIn = $dtrResult['time_in'] ?? '—';
$timeOut = $dtrResult['time_out'] ?? '—';

// Calculate total hours if both times exist
$totalHours = '—';
if ($dtrResult['time_in'] && $dtrResult['time_out']) {
    $start = strtotime($dtrResult['time_in']);
    $end = strtotime($dtrResult['time_out']);
    $hours = round(($end - $start) / 3600, 2);
    $totalHours = "{$hours} hrs";
}

// Contract Status
$contractQuery = $conn->prepare("SELECT status FROM contracts WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$contractQuery->bind_param("i", $user_id);
$contractQuery->execute();
$contractResult = $contractQuery->get_result()->fetch_assoc();
$contractStatus = $contractResult['status'] ?? '—';

// Last Request
$requestQuery = $conn->prepare("SELECT type, status FROM requests WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$requestQuery->bind_param("i", $user_id);
$requestQuery->execute();
$requestResult = $requestQuery->get_result()->fetch_assoc();
$lastRequest = $requestResult ? $requestResult['type'] . " (" . $requestResult['status'] . ")" : '—';

echo json_encode([
    "time_in" => $timeIn,
    "time_out" => $timeOut,
    "total_hours" => $totalHours,
    "contract_status" => $contractStatus,
    "last_request" => $lastRequest
]);
?>
