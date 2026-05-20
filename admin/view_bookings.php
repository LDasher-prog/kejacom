<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/home.php");
    exit();
}

$sql = "SELECT bookings.*, houses.title AS house_title, users.full_name AS student_name, houses.location 
        FROM bookings 
        JOIN houses ON bookings.house_id = houses.id 
        JOIN users ON bookings.user_id = users.id 
        ORDER BY bookings.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings - RentEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="adm_dashboard.php">Admin Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="adm_dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="../auth/logout.php" class="nav-link text-white">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3>All Bookings</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>House</th>
                    <th>Student</th>
                    <th>Location</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Requested On</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $booking['id']; ?></td>
                        <td><?= htmlspecialchars($booking['house_title']); ?></td>
                        <td><?= htmlspecialchars($booking['student_name']); ?></td>
                        <td><?= htmlspecialchars($booking['location']); ?></td>
                        <td><?= htmlspecialchars($booking['start_date']); ?></td>
                        <td><?= htmlspecialchars($booking['end_date'] ?: '-'); ?></td>
                        <td><?= ucfirst($booking['status']); ?></td>
                        <td><?= date('d M Y', strtotime($booking['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
