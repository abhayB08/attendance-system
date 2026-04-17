<!DOCTYPE html>
<html>
<head>
    <title>Smart Attendance System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white;
        }

        /* Navbar */
        .navbar {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
        }

        /* Hero */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Glass Card */
        .glass {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 15px;
        }

        .btn-custom {
            padding: 12px 30px;
            font-size: 18px;
        }

        /* Features */
        .feature-box {
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 15px;
            margin: 10px;
            transition: 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        /* Footer */
        footer {
            background: rgba(0,0,0,0.7);
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <span class="navbar-brand">📊 Smart Attendance</span>

        <div>
            <a href="login.php" class="btn btn-success me-2">Login</a>
            <a href="register.php" class="btn btn-warning">Register</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="glass">
        <h1 class="display-4">Smart Attendance System</h1>
        <p class="lead">QR Code + Time Validation + Real-Time Reports</p>

        <a href="login.php" class="btn btn-light btn-custom m-2">
            🚀 Get Started
        </a>

        <a href="#features" class="btn btn-outline-light btn-custom m-2">
            Learn More
        </a>
    </div>
</div>

<!-- Features -->
<div class="container text-center mt-5" id="features">
    <h2 class="mb-4">✨ Features</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="feature-box">
                <i class="fas fa-qrcode fa-2x mb-2"></i>
                <h5>QR Attendance</h5>
                <p>Scan QR to mark attendance instantly.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-box">
                <i class="fas fa-clock fa-2x mb-2"></i>
                <h5>Time Validation</h5>
                <p>Automatically detects late and absent.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-box">
                <i class="fas fa-chart-pie fa-2x mb-2"></i>
                <h5>Reports & Charts</h5>
                <p>Detailed analytics with graphs.</p>
            </div>
        </div>
    </div>
</div>

<!-- About -->
<div class="container text-center mt-5">
    <h2>About Project</h2>
    <p>
        This system provides a smart and secure way to manage attendance using QR codes,
        reducing manual work and improving accuracy.
    </p>
</div>

<!-- Footer -->
<footer class="text-center p-3 mt-5">
    © 2026 Smart Attendance System | Developed by Abhay, Harsh, Mann
</footer>

</body>
</html>