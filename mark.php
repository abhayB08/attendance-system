<?php
session_start();
include 'db.php';

$student_id = $_SESSION['user_id'];
$lecture_id = $_GET['lecture_id'];

// get lecture
$lecture = $conn->query("
    SELECT lectures.*, users.name AS teacher_name
    FROM lectures
    JOIN users ON lectures.teacher_id = users.id
    WHERE lectures.id = $lecture_id
")->fetch_assoc();

// check duplicate
$check = $conn->query("SELECT * FROM attendance WHERE student_id=$student_id AND lecture_id=$lecture_id");

if ($check->num_rows > 0) {
    $message = "⚠️ Attendance already marked!";
    $color = "alert-info";
} else {

    $current_time = time();
    $lecture_time = strtotime($lecture['created_at']);
    $diff = ($current_time - $lecture_time) / 60; // minutes

    if ($diff <= 10) $status = "present";
    elseif ($diff <= 20) $status = "late";
    else $status = "absent";

    // insert
    $conn->query("INSERT INTO attendance (student_id, lecture_id, status) 
                  VALUES ($student_id, $lecture_id, '$status')");

    $message = "✅ Attendance Marked";
    $color = "alert-success";
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <div class="card shadow p-4 text-center">

        <h3 class="mb-3">Attendance Status</h3>

        <div class="alert <?php echo $color; ?>">
            <?php echo $message; ?>
        </div>

<?php if(isset($status)) { ?>

        <h4>
            Status:
            <?php
            if($status == "present") echo "<span class='text-success'>Present</span>";
            elseif($status == "late") echo "<span class='text-warning'>Late</span>";
            else echo "<span class='text-danger'>Absent</span>";
            ?>
        </h4>

        <p>📘 Subject: <?php echo $lecture['subject']; ?></p>
        <p>👨‍🏫 Teacher: <?php echo $lecture['teacher_name']; ?></p>
        <p>🕒 Time: <?php echo date("Y-m-d H:i:s"); ?></p>

<?php } ?>

        <a href="dashboard.php" class="btn btn-primary mt-3">
            🔙 Back to Dashboard
        </a>

    </div>

</div>