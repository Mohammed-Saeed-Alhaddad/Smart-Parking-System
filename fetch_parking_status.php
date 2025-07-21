<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "p_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT parking_id, id_sen, status_light FROM parkings";
$result = $conn->query($sql);

$parking_data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $parking_data[] = $row;
    }
}
$conn->close();

echo json_encode($parking_data);
