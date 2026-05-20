<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'landlord') {
    header("Location: home.php");
    exit();
}

$errors = [];
$success = "";

$landlord_id = $_SESSION['user_id'];

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid listing ID.");
}

$house_id = $_GET['id'];

// Fetch listing details
$stmt = $conn->prepare("SELECT * FROM houses WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $house_id, $landlord_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Listing not found or access denied.");
}

$house = $result->fetch_assoc();

// Handle POST request (update)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $type = trim($_POST['type']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $image_filename = $house['image'];

    // Image upload (if updated)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $image_filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $errors[] = "Image upload failed.";
        }
    }

    // Validation
    if (empty($title) || empty($location) || empty($type) || $price <= 0) {
        $errors[] = "All fields except image are required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE houses SET title=?, location=?, type=?, price=?, description=?, image=? WHERE id=? AND user_id=?");
        $stmt->bind_param("sssdssii", $title, $location, $type, $price, $description, $image_filename, $house_id, $landlord_id);

        if ($stmt->execute()) {
            $success = "Listing updated successfully!";
            // Reload updated data
            header("Location: home.php?updated=1");
            exit();
        } else {
            $errors[] = "Update failed: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Listing - RentEase</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="home.php">Landlord Dashboard</a>
    </div>
</nav>

<div class="container mt-5">
    <h3>Edit Listing</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($house['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Location *</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($house['location']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Type *</label>
            <select name="type" class="form-select" required>
                <?php
                $types = ["Apartment", "Bungalow", "Bedsitter", "Single Room", "Studio"];
                foreach ($types as $type) {
                    $selected = $house['type'] === $type ? "selected" : "";
                    echo "<option value=\"$type\" $selected>$type</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (KES) *</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?= $house['price']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($house['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if ($house['image']): ?>
                <img src="../assets/images/<?= $house['image']; ?>" width="200" class="img-thumbnail mb-2">
            <?php else: ?>
                <p>No image uploaded</p>
            <?php endif; ?>
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button type="submit" class="btn btn-warning">Update Listing</button>
        <a href="home.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
