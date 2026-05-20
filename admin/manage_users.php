<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/home.php");
    exit();
}

$sql = "SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - RentEase</title>
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
    <h3>Users</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= htmlspecialchars($user['full_name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td><?= date('d M Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
