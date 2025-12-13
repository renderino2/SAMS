<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is a Student Assistant
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Student Assistant') {
    // Return user session data
    echo json_encode([
        "success" => true,
        "student_id_number" => $_SESSION['user']['student_id_number'],
        "full_name" => $_SESSION['user']['full_name'],
        "office" => $_SESSION['user']['office'],
        "role" => $_SESSION['user']['role']
    ]);
} else {
    // Unauthorized access
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized or session expired"
    ]);
}
