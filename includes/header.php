<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shika Keja.Com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Optional icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-home text-primary"></i> RentEase</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="pages/available_rentals.php">Rentals</a></li>
                    <li class="nav-item"><a class="nav-link" href="pages/contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="pages/about.php">About</a></li>
                </ul>
                <a href="auth/login.php" class="btn btn-outline-primary ms-3">Login</a>
                <a href="auth/register.php" class="btn btn-primary ms-2">Sign Up</a>
            </div>
        </div>
    </nav>
