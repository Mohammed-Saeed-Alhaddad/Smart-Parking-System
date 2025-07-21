<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "p_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaintId = $_POST['id'];

    if (empty($complaintId)) {
        echo json_encode(['success' => false, 'error' => 'Complaint ID is missing.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE complaints SET state = 'solved' WHERE id = ?");
    $stmt->bind_param('i', $complaintId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update complaint.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
$conn->close();
?>