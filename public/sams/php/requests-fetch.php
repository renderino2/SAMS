<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Student Assistant") {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$studentId = $_SESSION["user"]["student_id_number"];
$stmt = $conn->prepare("SELECT * FROM requests WHERE student_id = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$requests = [];

while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode(["success" => true, "requests" => $requests]);
