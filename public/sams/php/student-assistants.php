<?php
// /php/student-assistants.php

header('Content-Type: application/json');

// Include DB connection
include 'db.php'; // Make sure this file exists

try {
    $query = "SELECT student_id_number, full_name, email, office, status FROM users WHERE role = 'Student Assistant'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed']);
        exit;
    }

    $studentAssistants = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $studentAssistants[] = $row;
    }

    echo json_encode($studentAssistants);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
