<?php
include('includes/db.php');
include('includes/functions.php');
startSession();

if (!isset($_SESSION['username'])) {
    redirectWithMessage('login_user.php', 'Please login first');
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$userid = $stmt->fetch();

$user_id = $userid['user_id'];
$parking_id = $_POST['parking_id'];

$stmt = $conn->prepare("SELECT * FROM parkings WHERE parking_id = :parking_id AND booked_by = :user_id");
$stmt->execute(['parking_id' => $parking_id, 'user_id' => $user_id]);
$parking = $stmt->fetch();

if ($parking) {
    $stmt = $conn->prepare("UPDATE parkings SET status = 'available', booking_time = 0, booked_by = NULL, expiry_time = NULL WHERE parking_id = :parking_id");
    $stmt->execute(['parking_id' => $parking_id]);

    $stmt = $conn->prepare("INSERT INTO operation_records (user_id, amount, operation_type, parking_id) VALUES (:user_id, '0', 'release', :parking_id)");
    $stmt->execute(['user_id' => $parking['booked_by'], 'parking_id' => $parking_id]);

    redirectWithMessage('main.php', 'Parking released successfully!');
} else {
    redirectWithMessage('main.php', 'Error: You can only release parkingS that you have booked.');
}
?>
