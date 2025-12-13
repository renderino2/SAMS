<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['student_id_number'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$student_id = $_SESSION['student_id_number'];

$stmt = $conn->prepare("SELECT student_id_number, full_name, email, contact, course, year_level, section FROM student_assistants WHERE student_id_number = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Student record not found"]);
}
