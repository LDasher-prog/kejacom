<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'landlord') {
    header("Location: home.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid listing ID.");
}

$house_id = (int) $_GET['id'];
$landlord_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM houses WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $house_id, $landlord_id);
$stmt->execute();
$stmt->close();

header("Location: home_landlord.php?deleted=1");
exit();
