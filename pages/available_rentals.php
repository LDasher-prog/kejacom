<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if (isset($_GET['wishlist_add']) && is_numeric($_GET['wishlist_add'])) {
    $wishlistId = (int) $_GET['wishlist_add'];
    if (!in_array($wishlistId, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $wishlistId;
    }
    header("Location: available_rentals.php?added=1");
    exit();
}

// Fetch available houses
$sql = "SELECT houses.*, users.full_name AS landlord_name 
        FROM houses 
        JOIN users ON houses.user_id = users.id 
        WHERE houses.available = 1 
        ORDER BY houses.created_at DESC";
$result = $conn->query($sql);
?>
<?php require_once("../includes/header.php"); ?>

<!-- Rentals Section -->
<div class="container mt-5">
    <h3 class="mb-4">Available Rental Houses</h3>

    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card shadow">
                        <img src="../assets/images/<?= $row['image'] ? $row['image'] : 'house1.jpg'; ?>" class="card-img-top" alt="House Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text">
                                <strong>Location:</strong> <?= htmlspecialchars($row['location']); ?><br>
                                <strong>Type:</strong> <?= htmlspecialchars($row['type']); ?><br>
                                <strong>Price:</strong> KES <?= number_format($row['price']); ?><br>
                                <small>Landlord: <?= htmlspecialchars($row['landlord_name']); ?></small>
                            </p>
                            <div class="d-grid gap-2">
                                <a href="book_now.php?id=<?= $row['id']; ?>" class="btn btn-primary">Book Now</a>
                                <?php if ($_SESSION['user_role'] === 'student'): ?>
                                    <?php if (in_array($row['id'], $_SESSION['wishlist'])): ?>
                                        <button class="btn btn-secondary" disabled>Saved to Wishlist</button>
                                    <?php else: ?>
                                        <a href="available_rentals.php?wishlist_add=<?= $row['id']; ?>" class="btn btn-outline-light">Add to Wishlist</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No rentals available at the moment.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once("../includes/footer.php"); ?>
