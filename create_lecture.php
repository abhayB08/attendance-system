<?php
session_start();
include 'db.php';

// only teacher allowed
if ($_SESSION['role'] != 'teacher') {
    die("Access Denied");
}

if(isset($_POST['subject'])) {

    $subject = $_POST['subject'];
    $valid_until = date("Y-m-d H:i:s", strtotime("+30 minutes"));
    $teacher_id = $_SESSION['user_id'];

    // STEP 1: insert lecture
    $conn->query("INSERT INTO lectures (subject, valid_until, teacher_id) 
                  VALUES ('$subject', '$valid_until', '$teacher_id')");

    // STEP 2: get id
    $lecture_id = $conn->insert_id;

    // STEP 3: create QR
    $qr_code = "http://localhost/attendance_system/mark.php?lecture_id=$lecture_id";

    // STEP 4: update QR
    $conn->query("UPDATE lectures SET qr_code='$qr_code' WHERE id=$lecture_id");

    echo "<div class='alert alert-info'>
        👨‍🏫 Lecture created by: <strong>" . $_SESSION['name'] . "</strong>
      </div>";
    echo "<img src='https://quickchart.io/qr?text=" . urlencode($qr_code) . "'>";
}
?>

<form method="POST">
    Subject: <input type="text" name="subject"><br>
    <button name="create">Create Lecture</button>
</form>