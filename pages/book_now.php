<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'student') {
    header("Location: home_landlord.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid house ID.");
}

$house_id = (int) $_GET['id'];
$student_id = $_SESSION['user_id'];

// Fetch house details
$stmt = $conn->prepare("SELECT * FROM houses WHERE id = ?");
$stmt->bind_param("i", $house_id);
$stmt->execute();
$result = $stmt->get_result();
$house = $result->fetch_assoc();

if (!$house) {
    die("House not found.");
}

if ($house['available'] == 0) {
    die("<h3>This house is no longer available for booking.</h3><a href='available_rentals.php'>← Back to Listings</a>");
}

$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!$start_date) {
        $errors[] = "Start date is required.";
    }

    // Insert booking
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, house_id, start_date, end_date, status, created_at) 
                                VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("iiss", $student_id, $house_id, $start_date, $end_date);
        if ($stmt->execute()) {
            $success = "Booking submitted successfully! Await confirmation.";
        } else {
            $errors[] = "Error processing booking.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book House - RentEase</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <a href="available_rentals.php" class="btn btn-sm btn-secondary mb-3">← Back to Listings</a>

    <div class="card shadow">
        <div class="row g-0">
            <div class="col-md-4">
                <?php if ($house['image']): ?>
                    <img src="../assets/images/<?= $house['image']; ?>" class="img-fluid rounded-start" alt="House">
                <?php else: ?>
                    <img src="../assets/images/house1.jpg" class="img-fluid rounded-start" alt="No image">
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($house['title']); ?></h4>
                    <p class="card-text"><?= htmlspecialchars($house['description']); ?></p>
                    <p class="card-text"><strong>Location:</strong> <?= $house['location']; ?></p>
                    <p class="card-text"><strong>Type:</strong> <?= $house['type']; ?></p>
                    <p class="card-text"><strong>Price:</strong> KES <?= number_format($house['price'], 2); ?></p>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success; ?></div>
                    <?php elseif (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="mt-4">
                        <div class="mb-3">
                            <label class="form-label">Start Date *</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date (Optional)</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
