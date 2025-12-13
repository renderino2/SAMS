<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['error' => 'Not authenticated.']);
    exit();
}

$userId = $_SESSION['user']['id'];

$query = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    echo json_encode(['error' => 'User not found.']);
}

$conn->close();
