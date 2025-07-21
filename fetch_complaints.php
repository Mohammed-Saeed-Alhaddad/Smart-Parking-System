<?php
include('includes/db.php');
include('includes/functions.php');
startSession();

if (!isset($_SESSION['admin_id'])) {
    redirectWithMessage('login_admin.php', 'Please login first');
}

$conn = new mysqli('localhost', 'root', '', 'p_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter === 'solved') {
    $sql = "SELECT * FROM Complaints WHERE state = 'solved'";
} elseif ($filter === 'unsolved') {
    $sql = "SELECT * FROM Complaints WHERE state = 'unsolved'";
} else {
    $sql = "SELECT * FROM Complaints";
}

$result = $conn->query($sql);

$complaints = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($complaints);
