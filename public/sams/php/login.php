<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

// 🧠 Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// 📌 Extract and trim data
$studentId = trim($data['studentId'] ?? '');
$email     = trim($data['email'] ?? '');
$password  = trim($data['password'] ?? '');
$role      = trim($data['role'] ?? '');

// 🚫 Validate input
if (!$studentId || !$email || !$password || !$role) {
    echo json_encode(["error" => "All fields are required."]);
    exit;
}

// 🔍 Look up the user
$stmt = $conn->prepare("SELECT * FROM users WHERE student_id_number = ? AND email = ? AND role = ?");
$stmt->bind_param("sss", $studentId, $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password_hash'])) {
        // 🛡️ Security: Regenerate session ID
        session_regenerate_id(true);

        // ✅ Store full user info in session
        $_SESSION['user'] = [
            "id" => $user['id'] ?? null, // Optional if you have numeric ID
            "student_id_number" => $user['student_id_number'],
            "full_name" => $user['full_name'],
            "role" => $user['role'],
            "email" => $user['email'],
            "office" => $user['office'] ?? null
        ];

        // 🔁 Set redirect per role
        $redirect = match ($user['role']) {
            'Student Assistant' => "attendance.html",
            'Office Head'       => "office-head-dashboard.html",
            'HR'                => "hr-dashboard.html",
            default             => "dashboard.html"
        };

        echo json_encode(["success" => true, "redirect" => $redirect]);
    } else {
        echo json_encode(["error" => "Invalid password."]);
    }
} else {
    echo json_encode(["error" => "User not found."]);
}

$stmt->close();
$conn->close();
