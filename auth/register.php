<?php
session_start();
require_once("../includes/db_connect.php");

$errors = [];
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];  // student or landlord

    // Validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, insert into DB
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $success = "Registration successful! <a href='login.php'>Login here</a>.";
        } else {
            $errors[] = "Email already exists";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - RentEase</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 shadow rounded">
            <h3 class="text-center mb-4">Create Your Account</h3>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="student">Student</option>
                        <option value="landlord">Landlord</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
