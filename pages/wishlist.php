<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'student') {
    header("Location: home.php");
    exit();
}

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if (isset($_GET['remove_id']) && is_numeric($_GET['remove_id'])) {
    $removeId = (int) $_GET['remove_id'];
    $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$removeId]));
    header("Location: wishlist.php?removed=1");
    exit();
}

$wishlistIds = array_unique(array_map('intval', $_SESSION['wishlist']));
$houses = [];

if (!empty($wishlistIds)) {
    $idList = implode(',', $wishlistIds);
    $sql = "SELECT houses.*, users.full_name AS landlord_name 
            FROM houses 
            JOIN users ON houses.user_id = users.id 
            WHERE houses.id IN ($idList)";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $houses[] = $row;
        }
    }
}
?>
<?php require_once("../includes/header.php"); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>My Wishlist</h3>
        <a href="available_rentals.php" class="btn btn-primary">Browse Rentals</a>
    </div>

    <?php if (isset($_GET['removed'])): ?>
        <div class="alert alert-success">Removed from wishlist.</div>
    <?php endif; ?>

    <?php if (empty($wishlistIds)): ?>
        <div class="alert alert-info">Your wishlist is empty. Add favorites while browsing rentals.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($houses as $house): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <img src="../assets/images/<?= $house['image'] ? $house['image'] : 'house1.jpg'; ?>" class="card-img-top" alt="<?= htmlspecialchars($house['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($house['title']); ?></h5>
                            <p class="card-text">
                                <strong>Location:</strong> <?= htmlspecialchars($house['location']); ?><br>
                                <strong>Type:</strong> <?= htmlspecialchars($house['type']); ?><br>
                                <strong>Price:</strong> KES <?= number_format($house['price']); ?><br>
                                <small>Landlord: <?= htmlspecialchars($house['landlord_name']); ?></small>
                            </p>
                            <div class="d-grid gap-2">
                                <a href="book_now.php?id=<?= $house['id']; ?>" class="btn btn-primary">Book Now</a>
                                <a href="wishlist.php?remove_id=<?= $house['id']; ?>" class="btn btn-outline-danger">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once("../includes/footer.php"); ?>
