<?php
require 'db.php';
session_start();
$hr_id = 1;

$data = json_decode(file_get_contents("php://input"), true);
$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);

$sql = "UPDATE hr_users SET full_name='$name', email='$email' WHERE id=$hr_id";
echo $conn->query($sql) ? 'success' : 'error';
?>
