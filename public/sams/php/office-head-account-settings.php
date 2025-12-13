<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['office_head_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$officeHeadId = $_SESSION['office_head_id'];

// GET — Load Profile
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT full_name, email, two_fa, notify_attendance, notify_evaluation, notify_weekly_summary FROM office_heads WHERE id = ?");
    $stmt->bind_param("i", $officeHeadId);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
    exit;
}

// PUT — Update Password
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $input);
    $currentPassword = $input['currentPassword'];
    $newPasswordHash = password_hash($input['newPassword'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT password FROM office_heads WHERE id = ?");
    $stmt->bind_param("i", $officeHeadId);
    $stmt->execute();
    $stored = $stmt->get_result()->fetch_assoc();

    if (!password_verify($currentPassword, $stored['password'])) {
        echo json_encode(["success" => false, "error" => "Incorrect current password"]);
        exit;
    }

    $update = $conn->prepare("UPDATE office_heads SET password = ? WHERE id = ?");
    $update->bind_param("si", $newPasswordHash, $officeHeadId);
    $update->execute();
    echo json_encode(["success" => true]);
    exit;
}

// POST — Save Settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $name = $input['name'];
    $email = $input['email'];
    $twoFA = $input['twoFA'] === 'enabled' ? 1 : 0;
    $notify1 = $input['notifyAttendance'] ? 1 : 0;
    $notify2 = $input['notifyEvaluation'] ? 1 : 0;
    $notify3 = $input['notifyWeekly'] ? 1 : 0;

    $stmt = $conn->prepare("UPDATE office_heads SET full_name=?, email=?, two_fa=?, notify_attendance=?, notify_evaluation=?, notify_weekly_summary=? WHERE id=?");
    $stmt->bind_param("ssiiiii", $name, $email, $twoFA, $notify1, $notify2, $notify3, $officeHeadId);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}
?>
