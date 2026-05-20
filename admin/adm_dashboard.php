<?php
require_once("../auth/auth_check.php"); // Session check
require_once("../includes/db_connect.php");

// Redirect non-admins
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - RentEase</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="adm_dashboard.php">Admin Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="../auth/logout.php" class="nav-link text-white">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Admin Dashboard Content -->
<div class="container mt-5">
    <h2 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?> (Admin)</h2>

    <div class="row g-4">
        <!-- Manage Users -->
        <div class="col-md-4">
            <div class="card shadow border-left-primary">
                <div class="card-body">
                    <h5 class="card-title">Manage Users</h5>
                    <p class="card-text">View all students and landlords.</p>
                    <a href="manage_users.php" class="btn btn-outline-primary">View Users</a>
                </div>
            </div>
        </div>

        <!-- Manage Listings -->
        <div class="col-md-4">
            <div class="card shadow border-left-success">
                <div class="card-body">
                    <h5 class="card-title">Manage Listings</h5>
                    <p class="card-text">View and moderate house listings.</p>
                    <a href="manage_listings.php" class="btn btn-outline-success">View Listings</a>
                </div>
            </div>
        </div>

        <!-- View Bookings -->
        <div class="col-md-4">
            <div class="card shadow border-left-warning">
                <div class="card-body">
                    <h5 class="card-title">View Bookings</h5>
                    <p class="card-text">Check current booking activity.</p>
                    <a href="view_bookings.php" class="btn btn-outline-warning">View Bookings</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
