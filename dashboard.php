<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { margin: 0; }

/* Sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    background: #212529;
    color: white;
    padding: 20px;
}
.sidebar a {
    color: white;
    display: block;
    margin: 10px 0;
    text-decoration: none;
}
.sidebar a:hover {
    background: #343a40;
    padding-left: 10px;
    transition: 0.3s;
}

/* Animation */
.card {
    animation: fadeIn 0.5s ease-in;
}
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}

.btn:hover {
    transform: scale(1.05);
}
</style>

<div class="d-flex">

<!-- Sidebar -->
<div class="sidebar">
    <h4>📊 Attendance</h4>
    <hr>

    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>

    <?php if($_SESSION['role']=='student'): ?>
        <a href="attendance.php"><i class="fas fa-chart-bar"></i> Report</a>
        <a href="scan_qr.php"><i class="fas fa-qrcode"></i> Scan QR</a>
    <?php endif; ?>

    <?php if($_SESSION['role']=='teacher'): ?>
        <a href="create_lecture.php"><i class="fas fa-plus"></i> Create Lecture</a>
    <?php endif; ?>

    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main -->
<div class="p-4 w-100">

<div class="d-flex justify-content-between mb-4">
    <h2>Dashboard</h2>
    <div>
        👤 <strong><?php echo $_SESSION['name']; ?></strong>
        (<?php echo $_SESSION['role']; ?>)
    </div>
</div>

<div class="card shadow p-4">

<?php
// ================= TEACHER DASHBOARD =================
if ($_SESSION['role'] == 'teacher') {

    $total = $conn->query("SELECT COUNT(*) as total FROM lectures")->fetch_assoc()['total'];

    $today = date("Y-m-d");
    $today_count = $conn->query("SELECT COUNT(*) as total FROM lectures WHERE DATE(created_at)='$today'")
    ->fetch_assoc()['total'];
?>

<h4 class="mb-4">👨‍🏫 Teacher Dashboard</h4>

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-center p-3 bg-primary text-white">
            <h5>Total Lectures</h5>
            <h2><?php echo $total; ?></h2>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-center p-3 bg-success text-white">
            <h5>Today's Lectures</h5>
            <h2><?php echo $today_count; ?></h2>
        </div>
    </div>
</div>

<a href="create_lecture.php" class="btn btn-primary mb-4">➕ Create Lecture</a>

<!-- Lecture Table -->
<div class="card p-3 mb-4">
<h5>📋 Recent Lectures</h5>

<table class="table table-bordered">
<tr class="table-dark">
    <th>Subject</th>
    <th>Created</th>
    <th>Valid Till</th>
    <th>QR</th>
</tr>

<?php
$lectures = $conn->query("SELECT * FROM lectures ORDER BY id DESC LIMIT 5");

while ($row = $lectures->fetch_assoc()) {
    echo "<tr>
        <td>{$row['subject']}</td>
        <td>{$row['created_at']}</td>
        <td>{$row['valid_until']}</td>
        <td><img src='https://quickchart.io/qr?text=".urlencode($row['qr_code'])."&size=80'></td>
    </tr>";
}
?>
</table>
</div>

<!-- Student Attendance -->
<div class="card p-3">
<h5>👨‍🎓 Student Attendance</h5>

<table class="table table-bordered">
<tr class="table-dark">
    <th>Student</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Time</th>
</tr>

<?php
$attendance = $conn->query("
    SELECT users.name AS student_name, lectures.subject, attendance.status, attendance.timestamp
    FROM attendance
    JOIN users ON attendance.student_id = users.id
    JOIN lectures ON attendance.lecture_id = lectures.id
    ORDER BY attendance.timestamp DESC
");

while ($row = $attendance->fetch_assoc()) {

    $color = ($row['status']=="present")?"text-success":
             (($row['status']=="late")?"text-warning":"text-danger");

    echo "<tr>
        <td>{$row['student_name']}</td>
        <td>{$row['subject']}</td>
        <td class='$color'><b>{$row['status']}</b></td>
        <td>{$row['timestamp']}</td>
    </tr>";
}
?>
</table>
</div>

<?php
}
// ================= STUDENT DASHBOARD =================
else {

$result = $conn->query("
    SELECT lectures.*, users.name AS teacher_name
    FROM lectures
    JOIN users ON lectures.teacher_id = users.id
    ORDER BY lectures.id DESC LIMIT 1
");

if ($result->num_rows > 0) {
    $lecture = $result->fetch_assoc();
?>

<h4>👨‍🎓 Student Panel</h4>

<h5>📘 Subject: <?php echo $lecture['subject']; ?></h5>
<p>👨‍🏫 Teacher: <?php echo $lecture['teacher_name']; ?></p>
<p>🕒 Time: <?php echo $lecture['created_at']; ?></p>
<p>⏳ Valid Till: <?php echo $lecture['valid_until']; ?></p>

<div class="text-center">
<img src="https://quickchart.io/qr?text=<?php echo urlencode($lecture['qr_code']); ?>&size=200">
</div>

<div class="text-center mt-3">
<a href="<?php echo $lecture['qr_code']; ?>" class="btn btn-success">Mark Attendance</a>
<a href="attendance.php" class="btn btn-info">View Report</a>
</div>

<?php
} else {
    echo "<div class='alert alert-warning'>No lecture available</div>";
}
}
?>

</div>
</div>
</div>

<footer class="bg-dark text-white text-center p-3">
© 2026 Smart Attendance System | Developed by Abhay, Harsh, Mann
</footer>