<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'landlord') {
    header("Location: home.php");
    exit();
}

$landlord_id = $_SESSION['user_id'];
$sql = "SELECT bookings.*, houses.title AS house_title, houses.location, users.full_name AS student_name 
        FROM bookings 
        JOIN houses ON bookings.house_id = houses.id 
        JOIN users ON bookings.user_id = users.id 
        WHERE houses.user_id = ? 
        ORDER BY bookings.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $landlord_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Landlord Bookings - RentEase</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="home_landlord.php">RentEase</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="home_landlord.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="../auth/logout.php" class="nav-link text-white">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3>Booking Requests</h3>
    <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>House</th>
                        <th>Student</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['house_title']); ?></td>
                            <td><?= htmlspecialchars($booking['student_name']); ?></td>
                            <td><?= htmlspecialchars($booking['location']); ?></td>
                            <td><?= htmlspecialchars($booking['start_date']); ?></td>
                            <td><?= htmlspecialchars($booking['end_date'] ?: '-'); ?></td>
                            <td><?= ucfirst($booking['status']); ?></td>
                            <td><?= date("d M Y", strtotime($booking['created_at'])); ?></td>
                            <td>
                                <?php if ($booking['status'] === 'pending'): ?>
                                    <a href="update_booking.php?id=<?= $booking['id']; ?>&action=approve" class="btn btn-sm btn-success">Approve</a>
                                    <a href="update_booking.php?id=<?= $booking['id']; ?>&action=reject" class="btn btn-sm btn-danger">Reject</a>
                                <?php else: ?>
                                    <span class="text-muted">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No booking requests found.</div>
    <?php endif; ?>
</div>
</body>
</html>
