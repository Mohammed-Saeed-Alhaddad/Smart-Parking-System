<?php
$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "p_system";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$stmt = $conn->prepare("SELECT * FROM parkings WHERE booked_by IS NOT NULL");
$stmt->execute();
$room = $stmt->fetch();

$cuurent_ti = date('Y-m-d H:i:s');

if($room) {
    if($room['booking_time'] == 1){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+1 hours'));
    } elseif($room['booking_time'] == 5){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+5 hours'));
    } elseif($room['booking_time'] == 10){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+10 hours'));
    }elseif($room['booking_time'] == 0.5){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+1 minutes'));
    }
    if($room['expiry_time'] <= $cuurent_ti){
        // Update room status to available and remove booking
        $stmt = $conn->prepare("UPDATE parkings SET status = 'available', booking_time = 0, booked_by = NULL, expiry_time = NULL WHERE parking_id = :parking_id");
        $stmt->execute(['parking_id' => $room['parking_id']]);

        $stmt = $conn->prepare("INSERT INTO operation_records (user_id, amount, operation_type, parking_id) VALUES (:user_id, '0', 'auto release', :parking_id)");
        $stmt->execute(['user_id' => $room['booked_by'], 'parking_id' => $room['parking_id']]);
    }  
}


?>
