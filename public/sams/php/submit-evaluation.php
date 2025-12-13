<?php
require '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Sanitize and extract data
$student_name = trim(mysqli_real_escape_string($conn, $data['studentName']));
$nature_of_work = trim(mysqli_real_escape_string($conn, $data['natureOfWork']));
$office = trim(mysqli_real_escape_string($conn, $data['office']));
$comments = trim(mysqli_real_escape_string($conn, $data['comments']));
$date = mysqli_real_escape_string($conn, $data['date']);
$rated_by = trim(mysqli_real_escape_string($conn, $data['ratedBy']));
$head = trim(mysqli_real_escape_string($conn, $data['head']));

$rates = [];
for ($i = 1; $i <= 10; $i++) {
    $rate = intval($data["rate$i"]);
    if ($rate < 1 || $rate > 10) {
        echo json_encode(["error" => "Rating $i must be between 1 and 10."]);
        exit;
    }
    $rates[] = $rate;
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO evaluations (
    student_name, nature_of_work, office,
    rate1, rate2, rate3, rate4, rate5,
    rate6, rate7, rate8, rate9, rate10,
    comments, date, rated_by, head
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssiiiiiiiiiiiisss",
    $student_name, $nature_of_work, $office,
    $rates[0], $rates[1], $rates[2], $rates[3], $rates[4],
    $rates[5], $rates[6], $rates[7], $rates[8], $rates[9],
    $comments, $date, $rated_by, $head
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Evaluation submitted successfully."]);
} else {
    echo json_encode(["error" => "Database error."]);
}

$stmt->close();
$conn->close();
