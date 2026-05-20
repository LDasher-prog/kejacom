<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'landlord') {
    header("Location: home.php");
    exit();
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $type = trim($_POST['type']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];
    $image_filename = null;

    // Image upload (optional)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $image_filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $errors[] = "Image upload failed. Try again.";
        }
    }

    // Validate required fields
    if (empty($title) || empty($location) || empty($type) || $price <= 0) {
        $errors[] = "All fields except image are required.";
    }

    // Insert into DB
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO houses (user_id, title, location, type, price, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdss", $user_id, $title, $location, $type, $price, $description, $image_filename);

        if ($stmt->execute()) {
            $success = "Listing added successfully!";
        } else {
            $errors[] = "Error saving listing: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Listing - RentEase</title>
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
    <h3>Add New Listing</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Location *</label>
            <input type="text" name="location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Type *</label>
            <select name="type" class="form-select" required>
                <option value="">-- Select Type --</option>
                <option value="Apartment">Apartment</option>
                <option value="Bungalow">Bungalow</option>
                <option value="Bedsitter">Bedsitter</option>
                <option value="Single Room">Single Room</option>
                <option value="Studio">Studio</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (KES) *</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Image (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save Listing</button>
        <a href="home.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
