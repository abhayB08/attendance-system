<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
include 'db.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $password = md5($password);

    $sql = "INSERT INTO users (name, email, password, role)
            VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql)) {
        echo "<div class='alert alert-success text-center'>
        ✅ Registered Successfully!
      </div>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<div class="container mt-5">
    <div class="card p-4 shadow col-md-6 mx-auto">
        <h3 class="text-center">Register</h3>

 <form method="POST">
            <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

            <select name="role" class="form-control mb-3">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <button name="register" class="btn btn-success w-100">Register</button>
        </form>
    </div>
</div>