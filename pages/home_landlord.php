<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'landlord') {
    header("Location: home.php");
    exit();
}

$landlord_id = $_SESSION['user_id'];

// Fetch landlord's listings
$sql = "SELECT * FROM houses WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $landlord_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<?php require_once("../includes/header.php"); ?>

<!-- Content -->
<div class="container mt-5">
    <h3>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></h3>
    <p class="text-muted">Manage your house listings</p>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Listing deleted successfully.</div>
    <?php endif; ?>

    <a href="add_listing.php" class="btn btn-primary mb-3">+ Add New Listing</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Price (KES)</th>
                    <th>Status</th>
                    <th>Date Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($house = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($house['title']); ?></td>
                            <td><?= htmlspecialchars($house['location']); ?></td>
                            <td><?= htmlspecialchars($house['type']); ?></td>
                            <td><?= number_format($house['price']); ?></td>
                            <td>
                                <span class="badge bg-<?= $house['available'] ? 'success' : 'secondary' ?>">
                                    <?= $house['available'] ? 'Available' : 'Unavailable' ?>
                                </span>
                            </td>
                            <td><?= date("d M Y", strtotime($house['created_at'])); ?></td>
                            <td>
                                <a href="edit_listing.php?id=<?= $house['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_listing.php?id=<?= $house['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No listings found. Add a new one!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once("../includes/footer.php"); ?>
