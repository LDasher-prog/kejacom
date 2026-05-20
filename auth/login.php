<?php
session_start();
require_once("../includes/db_connect.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $errors[] = "Both email and password are required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header("Location: ../admin/adm_dashboard.php");
                } elseif ($user['role'] == 'landlord') {
                    header("Location: ../pages/home_landlord.php"); 
                } else {
                    header("Location: ../pages/home.php");
                }
                exit();
            } else {
                $errors[] = "Invalid password.";
            }
        } else {
            $errors[] = "No user found with that email.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Shika Keja.Com</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 bg-white p-4 shadow rounded">
            <h3 class="text-center mb-4">Login</h3>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>




