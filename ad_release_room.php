<?php
include('includes/db.php');
include('includes/functions.php');
startSession();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    redirectWithMessage('login_admin.php', 'Please login first');
}

$parking_id = $_POST['parking_id']; // Get the room ID to release

// Fetch the room details
$stmt = $conn->prepare("SELECT * FROM parkings WHERE parking_id = :parking_id ");
$stmt->execute(['parking_id' => $parking_id]);
$parking = $stmt->fetch();

if ($parking) {
    // Update room status to available and remove booking
    $stmt = $conn->prepare("UPDATE parkings SET status = 'available', booking_time = 0, booked_by = NULL, expiry_time = NULL WHERE parking_id = :parking_id");
    $stmt->execute(['parking_id' => $parking_id]);

    $stmt = $conn->prepare("INSERT INTO operation_records (user_id, amount, operation_type, parking_id) VALUES (:user_id, '0', 'release by admin', :parking_id)");
    $stmt->execute(['user_id' => $parking['booked_by'], 'parking_id' => $parking_id]);

    // Redirect user to dashboard with success message
    redirectWithMessage('dashboard_admin.php', 'Parking released successfully!');
} else {
    // If the room is not booked by the user
    redirectWithMessage('dashboard_admin.php', 'Error: You can only release parkingS that you have booked.');
}
?>
