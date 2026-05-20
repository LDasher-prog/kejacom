<?php
require_once("../auth/auth_check.php"); // session check
require_once("../includes/db_connect.php");

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - Shika Keja.Com</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand" href="home.php">Shika Keja.Com</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../auth/logout.php" class="nav-link text-white">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <div class="container mt-5">
        <div class="alert alert-success">
            <h4>Welcome, <?= htmlspecialchars($user_name); ?>!</h4>
            <p>You are logged in as <strong><?= ucfirst($user_role); ?></strong>.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Browse Available Houses</h5>
                        <p class="card-text">View and book rentals based on your preferences.</p>
                        <a href="available_rentals.php" class="btn btn-primary">View Listings</a>
                    </div>
                </div>
            </div>

            <?php if ($user_role === 'student'): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">My Bookings</h5>
                            <p class="card-text">Check the status of your house bookings.</p>
                            <a href="booking_status.php" class="btn btn-outline-primary">View Bookings</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">My Wishlist</h5>
                            <p class="card-text">Save rentals you want to compare or book later.</p>
                            <a href="wishlist.php" class="btn btn-outline-secondary">View Wishlist</a>
                        </div>
                    </div>
                </div>
            <?php elseif ($user_role === 'landlord'): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Manage Listings</h5>
                            <p class="card-text">Add or edit your rental houses.</p>
                            <a href="home_landlord.php" class="btn btn-outline-primary">Manage Listings</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
