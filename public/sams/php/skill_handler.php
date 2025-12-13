<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$id = intval($data['id'] ?? 0);

if ($action === 'add') {
    $stmt = $conn->prepare("INSERT INTO skill_inventory (student_name, student_id, office, skill_tag, date_added, status, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $data['name'], $data['student_id'], $data['office'], $data['skill'], $data['date'], $data['status'], $data['remarks']);
    $success = $stmt->execute();
    echo json_encode(['success'=>$success, 'error'=> $stmt->error]);
} elseif ($action === 'verify' && $id) {
    $stmt = $conn->prepare("UPDATE skill_inventory SET status='Verified' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success'=>true]);
} elseif ($action === 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM skill_inventory WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error'=>'Invalid action']);
}

$conn->close();
