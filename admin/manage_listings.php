<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/home.php");
    exit();
}

$sql = "SELECT houses.*, users.full_name AS landlord_name FROM houses JOIN users ON houses.user_id = users.id ORDER BY houses.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Listings - RentEase</title>
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
    <h3>Rental Listings</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Landlord</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Available</th>
                    <th>Posted</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($house = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $house['id']; ?></td>
                        <td><?= htmlspecialchars($house['title']); ?></td>
                        <td><?= htmlspecialchars($house['landlord_name']); ?></td>
                        <td><?= htmlspecialchars($house['location']); ?></td>
                        <td>KES <?= number_format($house['price']); ?></td>
                        <td><?= $house['available'] ? 'Yes' : 'No'; ?></td>
                        <td><?= date('d M Y', strtotime($house['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
