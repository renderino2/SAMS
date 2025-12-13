<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Student Assistant') {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$student_id = $_SESSION['user']['student_id_number'];

$stmt = $conn->prepare("SELECT skill_name, added_on, is_verified, note FROM skill_inventory WHERE student_id = ? ORDER BY added_on DESC");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$skills = [];
while ($row = $result->fetch_assoc()) {
    $skills[] = $row;
}

echo json_encode($skills);
$stmt->close();
$conn->close();
