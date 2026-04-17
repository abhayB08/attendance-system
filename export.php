<?php
session_start();
include 'db.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance_report.xls");

$student_id = $_SESSION['user_id'];

echo "Subject\tStatus\tTime\n";

$result = $conn->query("
    SELECT lectures.subject, attendance.status, attendance.timestamp 
    FROM attendance 
    JOIN lectures ON attendance.lecture_id = lectures.id 
    WHERE attendance.student_id = $student_id
");

while ($row = $result->fetch_assoc()) {
    echo "{$row['subject']}\t{$row['status']}\t{$row['timestamp']}\n";
}
?>