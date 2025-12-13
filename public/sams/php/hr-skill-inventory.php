<?php
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(403);
    echo json_encode(["error" => "Forbidden"]);
    exit;
}

require_once 'db.php';

$sql = "
    SELECT 
        u.student_id_number,
        u.full_name,
        u.office,
        sk.skill_name,
        sk.proficiency,
        sk.updated_at,
        sk.is_verified,
        sk.note
    FROM 
        skill_inventory sk
    JOIN 
        users u ON sk.student_id_number = u.student_id_number
    ORDER BY 
        sk.updated_at DESC
";

$result = $conn->query($sql);

$skills = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['status'] = $row['is_verified'] ? 'Verified' : 'Not Verified';
        $skills[] = $row;
    }
}

echo json_encode($skills);
$conn->close();
