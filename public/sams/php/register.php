<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$required = ['student_id_number', 'full_name', 'email', 'role', 'password', 'confirm_password'];
foreach ($required as $field) {
    if (!isset($data[$field]) || trim($data[$field]) === '') {
        echo json_encode(['error' => "Missing field: $field"]);
        exit();
    }
}

$studentId = mysqli_real_escape_string($conn, trim($data['student_id_number']));
$fullName = mysqli_real_escape_string($conn, trim($data['full_name']));
$email = mysqli_real_escape_string($conn, trim($data['email']));
$role = mysqli_real_escape_string($conn, trim($data['role']));
$office = mysqli_real_escape_string($conn, trim($data['office'] ?? ''));
$password = mysqli_real_escape_string($conn, $data['password']);
$confirmPassword = mysqli_real_escape_string($conn, $data['confirm_password']);

if ($password !== $confirmPassword) {
    echo json_encode(['error' => 'Passwords do not match.']);
    exit();
}

// Check if student_id_number or email already exists
$check = $conn->prepare("SELECT student_id_number FROM users WHERE student_id_number = ? OR email = ?");
$check->bind_param("ss", $studentId, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['error' => 'Student ID or email already registered.']);
    exit();
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$insert = $conn->prepare("INSERT INTO users (student_id_number, full_name, email, password_hash, role, office, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$insert->bind_param("ssssss", $studentId, $fullName, $email, $passwordHash, $role, $office);

if ($insert->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Database insert failed.']);
}

$insert->close();
$conn->close();
?>
