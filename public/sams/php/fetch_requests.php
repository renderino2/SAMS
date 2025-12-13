<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "sams");

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT * FROM requests ORDER BY date DESC";
$result = $conn->query($sql);

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);
$conn->close();
?>
