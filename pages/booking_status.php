<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'student') {
    header("Location: home_landlord.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT bookings.*, houses.title, houses.location, houses.price, houses.type
        FROM bookings
        JOIN houses ON bookings.house_id = houses.id
        WHERE bookings.user_id = ?
        ORDER BY bookings.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<?php require_once("../includes/header.php"); ?>

<!-- Bookings Section -->
<div class="container mt-5">
    <h3 class="mb-4">My Bookings</h3>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>House Title</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Price (KES)</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Booked On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['title']); ?></td>
                            <td><?= htmlspecialchars($booking['location']); ?></td>
                            <td><?= htmlspecialchars($booking['type']); ?></td>
                            <td><?= number_format($booking['price']); ?></td>
                            <td><?= htmlspecialchars($booking['start_date']); ?></td>
                            <td><?= htmlspecialchars($booking['end_date'] ?? '-'); ?></td>
                            <td>
                                <?php
                                $status = $booking['status'];
                                $badge = match($status) {
                                    'confirmed' => 'success',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $badge; ?>"><?= ucfirst($status); ?></span>
                            </td>
                            <td><?= date("d M Y", strtotime($booking['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">You have not made any bookings yet.</div>
    <?php endif; ?>
</div>
<?php require_once("../includes/footer.php"); ?>
