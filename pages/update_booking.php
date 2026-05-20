<?php
require_once("../auth/auth_check.php");
require_once("../includes/db_connect.php");

if ($_SESSION['user_role'] !== 'landlord') {
    header("Location: ../pages/home.php");
    exit();
}

$landlord_id = $_SESSION['user_id'];

if (!isset($_GET['id'], $_GET['action'])) {
    die("Invalid request.");
}

$booking_id = (int) $_GET['id'];
$action = $_GET['action'];

$status = match ($action) {
    "approve" => "confirmed",
    "reject"  => "cancelled",
    default   => null,
};

if (!$status) {
    die("Invalid action.");
}

// Verify that the booking belongs to a house owned by this landlord
$sql = "SELECT bookings.id 
        FROM bookings 
        JOIN houses ON bookings.house_id = houses.id 
        WHERE bookings.id = ? AND houses.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $landlord_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Unauthorized or booking not found.");
}

// Update the booking status
$update = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$update->bind_param("si", $status, $booking_id);
$update->execute();

// If approved, lock the house (mark unavailable)
if ($status === "confirmed") {
    $lock = $conn->prepare("UPDATE houses 
        SET available = 0 
        WHERE id = (SELECT house_id FROM bookings WHERE id = ?)");
    $lock->bind_param("i", $booking_id);
    $lock->execute();
}

/*
 * Send Telegram Notification to the student.
 * We assume that the `users` table has a 'telegram_id' column.
 */
$notifyStmt = $conn->prepare("
    SELECT users.telegram_id, users.full_name, houses.title 
    FROM bookings 
    JOIN users ON bookings.user_id = users.id
    JOIN houses ON bookings.house_id = houses.id
    WHERE bookings.id = ?
");
$notifyStmt->bind_param("i", $booking_id);
$notifyStmt->execute();
$notifyResult = $notifyStmt->get_result();

if ($notifyResult->num_rows > 0) {
    $row = $notifyResult->fetch_assoc();
    $chat_id = $row['telegram_id'];
    $name = $row['full_name'];
    $house_title = $row['title'];

    $subject = "Booking Status Update - $house_title"; // Subject in email style (for context)
    $message = match($status) {
        "confirmed" => "Hello $name,\n\nYour booking for '$house_title' has been approved. Kindly reach out to the landlord for further instructions.\n\nRegards,\nRentEase Team",
        "cancelled" => "Hello $name,\n\nUnfortunately, your booking for '$house_title' has been rejected. Please explore other listings on RentEase.\n\nRegards,\nRentEase Team",
        default => ""
    };

    // Only send notification if a Telegram chat id is available
    if ($chat_id) {
        $telegram_token = '7915188956:AAFSjuTYrvsZGzbo10sFfA5Xi2OahyY24HQ'; // Replace with your bot token
        $telegram_url = "https://api.telegram.org/bot{$telegram_token}/sendMessage";

        $postData = http_build_query([
            'chat_id' => $chat_id,
            'text'    => $message,
        ]);

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => $postData,
            ],
        ];
        $context  = stream_context_create($options);
        $telegramResponse = file_get_contents($telegram_url, false, $context);
        // For debugging purposes, you can log $telegramResponse if needed
    }
}

header("Location: view_bookings.php?status_updated=1");
exit();
