<?php
session_start();

// only student allowed
if ($_SESSION['role'] != 'student') {
    die("Access Denied");
}
?>

<h2>Scan QR</h2>

<p>👉 Paste QR link here:</p>

<form method="GET" action="mark.php">
    <input type="number" name="lecture_id" placeholder="Enter Lecture ID">
    <button type="submit">Mark Attendance</button>
</form>