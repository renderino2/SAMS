    <?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $sql = "
        SELECT 
            u.office,
            COUNT(a.id) AS total,
            SUM(a.status = 'Present') AS present,
            SUM(a.status = 'Late') AS late,
            SUM(a.status = 'Absent') AS absent,
            SUM(a.status = 'Overtime') AS overtime
        FROM attendance a
        JOIN users u ON a.student_id = u.student_id_number
        GROUP BY u.office
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query error: " . $conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $data]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
