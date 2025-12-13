<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$userId = $_SESSION['user']['id'] ?? null;
$fullName = trim($data['full_name']);
$email = trim($data['email']);
$password = trim($data['password']);

// Validate
if (!$userId || !$fullName || !$email) {
    echo json_encode(['error' => 'Required fields missing.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email format.']);
    exit();
}

if ($password) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, password_hash=? WHERE id=?");
    $stmt->bind_param("sssi", $fullName, $email, $passwordHash, $userId);
} else {
    $stmt = $conn->prepare("UPDATE users SET full_name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $fullName, $email, $userId);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update.']);
}

$stmt->close();
$conn->close();
