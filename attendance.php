<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// totals
$total = $conn->query("SELECT COUNT(*) as total FROM lectures")->fetch_assoc()['total'];
$attended = $conn->query("SELECT COUNT(*) as total FROM attendance WHERE student_id=$student_id AND status!='absent'")
->fetch_assoc()['total'];
$percentage = ($total > 0) ? ($attended / $total) * 100 : 0;

// status counts
$p = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE status='present' AND student_id=$student_id")->fetch_assoc()['c'];
$l = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE status='late' AND student_id=$student_id")->fetch_assoc()['c'];
$a = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE status='absent' AND student_id=$student_id")->fetch_assoc()['c'];

// subject-wise
$subject_data = $conn->query("
    SELECT lectures.subject, COUNT(attendance.id) as total
    FROM attendance 
    JOIN lectures ON attendance.lecture_id = lectures.id
    WHERE attendance.student_id = $student_id
    GROUP BY lectures.subject
");

$subjects = [];
$counts = [];

while ($row = $subject_data->fetch_assoc()) {
    $subjects[] = $row['subject'];
    $counts[] = $row['total'];
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card-box {
    border-radius: 12px;
    padding: 20px;
    color: white;
    text-align: center;
}
.bg1 { background: #0d6efd; }
.bg2 { background: #198754; }
.bg3 { background: #0dcaf0; }
.table td, .table th {
    vertical-align: middle;
}
</style>

<div class="container mt-5">

    <h2 class="mb-4 text-center">📊 Attendance Report</h2>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card-box bg1">
                <h5>Total Lectures</h5>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg2">
                <h5>Attended</h5>
                <h2><?php echo $attended; ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg3">
                <h5>Percentage</h5>
                <h2><?php echo round($percentage,2); ?>%</h2>
            </div>
        </div>
    </div>

    <!-- Export Button -->
    <div class="text-end mb-3">
        <a href="export.php" class="btn btn-success">📥 Download Excel</a>
    </div>

    <!-- Charts Row -->
    <div class="row">

        <!-- Pie Chart -->
        <div class="col-md-6 text-center">
            <h5>Attendance Distribution</h5>
            <div style="width:300px; height:300px; margin:auto;">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="col-md-6">
            <h5>Subject-wise Attendance</h5>
            <canvas id="barChart"></canvas>
        </div>

    </div>

    <!-- Table -->
    <div class="card mt-4 shadow p-3">
        <h5>📋 Detailed Report</h5>

        <table class="table table-bordered table-hover mt-3">
            <tr class="table-dark">
                <th>Subject</th>
                <th>Status</th>
                <th>Time</th>
            </tr>

            <?php
            $result = $conn->query("
                SELECT lectures.subject, attendance.status, attendance.timestamp 
                FROM attendance 
                JOIN lectures ON attendance.lecture_id = lectures.id 
                WHERE attendance.student_id = $student_id
            ");

            while ($row = $result->fetch_assoc()) {

                // status color
                $color = "";
                if ($row['status'] == "present") $color = "text-success";
                elseif ($row['status'] == "late") $color = "text-warning";
                else $color = "text-danger";

                echo "<tr>
                <td>{$row['subject']}</td>
                <td class='$color'><strong>{$row['status']}</strong></td>
                <td>{$row['timestamp']}</td>
                </tr>";
            }
            ?>
        </table>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// PIE
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Present', 'Late', 'Absent'],
        datasets: [{
            data: [<?php echo "$p,$l,$a"; ?>]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// BAR
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($subjects); ?>,
        datasets: [{
            label: 'Attendance Count',
            data: <?php echo json_encode($counts); ?>
        }]
    }
});
</script>