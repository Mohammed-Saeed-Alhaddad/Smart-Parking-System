<?php
include('includes/db.php');
include('includes/functions.php');
startSession();

if (!isset($_SESSION['admin_id'])) {
    redirectWithMessage('login_admin.php', 'Please login first');
}

$stmt = $conn->prepare("
    SELECT r.*, u.username AS booked_by_username
    FROM parkings r
    LEFT JOIN users u ON r.booked_by = u.user_id
");
$stmt->execute();
$rooms = $stmt->fetchAll();

$stmt = $conn->prepare("
SELECT *
FROM transactions
JOIN users
ON transactions.user_id = users.user_id
ORDER BY `transaction_id` DESC;
");
$stmt->execute();
$trans = $stmt->fetchAll();

$stmt = $conn->prepare("
SELECT *
FROM cars
JOIN users
ON cars.Username = users.username
");
$stmt->execute();
$result1 = $stmt->fetchAll();

$stmt = $conn->prepare("
SELECT * FROM operation_records 
INNER JOIN users ON operation_records.user_id = users.user_id 
INNER JOIN parkings ON operation_records.parking_id = parkings.parking_id 
ORDER BY `record_id` DESC;
");
$stmt->execute();
$recordd = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT * FROM parkings WHERE status = 'booked'");
$stmt->execute();
$booked_rooms = $stmt->fetchAll();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "p_system"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id_sen, status_light FROM parkings";
$result = $conn->query($sql);

$parkings = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $parkings[] = $row;
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

$cars = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$sql = "SELECT * FROM operation_records";
$recordss = $conn->query($sql);
if ($recordss->num_rows > 0) {
    while ($row = $recordss->fetch_assoc()) {
        $records[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator: Parkings Page</title>
    <link rel="icon" href="./logo.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background-size: cover;
        /* background: linear-gradient(135deg, #476894, #000000); */
        color: #333;
        align-items: center;

        background-repeat: no-repeat;
        background-attachment: fixed;
        background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.6)), url("./BKG_9.jpeg");
        /*background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.6)), url("./BKG_9.jpeg");*/
    }

    html {
        scrollbar-gutter: stable;
        scrollbar-color: #333 #e0e0e0;
        scrollbar-width: thin;
    }

    /* Header Styles */
    header {
        width: 100%;
        padding: 20px 0;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .logo {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-items: space-between;
    }

    #logoimg {
        margin-right: 20px;
    }

    .logo {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-items: space-between;
    }

    .logo h1 {
        color: #fff;
        font-size: 28px;
    }

    nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        transition: color 0.3s;
    }

    nav ul li a:hover {
        color: #ffdd57;
    }

    .login-btn {
        padding: 10px 25px;
        border: 2px solid #fff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
    }

    .login-btn:hover {
        background-color: #fff;
        color: #425699;
    }

    /* Main Content Styles */
    main {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 50px 20px;
    }

    /* Login Form Container */
    .login-form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: 500px;
        padding: 20px;
    }

    .login-form {
        background-color: #fff;
        border-radius: 10px;
        padding: 40px 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        width: 100%;
        text-align: center;
        min-height: 400px;
        position: relative;
        overflow: hidden;
        transition: min-height 0.3s ease;
    }

    .login-form h2 {
        font-size: 24px;
        margin-bottom: 30px;
        color: #333;
    }

    /* Improved Input Styles */
    .input-box {
        margin-bottom: 30px;
        text-align: center;
    }

    .input-box p {
        text-align: left;
    }

    .input-box input[type="text"],
    .input-box input[type="password"],
    .input-box input[type="email"],
    .input-box input[type="tel"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        color: #333;
        background-color: #f9f9f9;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
        margin-bottom: 10px;
    }

    .input-box input[type="text"]:focus,
    .input-box input[type="password"]:focus,
    .input-box input[type="email"]:focus,
    .input-box input[type="tel"]:focus {
        border-color: #425699;
        box-shadow: 0 0 5px rgba(66, 86, 153, 0.5);
    }

    .input-box label {
        display: block;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
    }

    /* Button Styles */
    button[type="button"],
    button[type="submit"] {
        padding: 10px 25px;
        background: #575757;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    button[type="button"]:hover,
    button[type="submit"]:hover {
        background-color: rgba(0, 0, 0, 0.9);
        transform: translateY(-2px);
    }

    /* Form Steps */
    .form-step {
        display: none;
    }

    .form-step-active {
        display: block;
    }

    /* Floor Selection */
    .container-floor-select {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: 1100px;
        padding: 50px 0;
    }

    .floor-selection {
        background-color: #fff;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        max-width: 950px;
        height: 500px;
        width: 100%;
        text-align: center;
    }

    .floor-selection h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .floor-buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .floor-btn {
        padding: 10px 20px;
        background: #717996;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .floor-btn:hover {
        background: #5a7de4;
    }


    footer {
        color: #fff;
        text-align: center;
        padding: 20px 0;
        margin-top: auto;
        /* This pushes the footer to the bottom */
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f0f0f0;
    }

    .wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
    }

    .container_1 {
        display: flex;
        max-width: 1175px;
        width: 100%;
        height: 550px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 17px;
    }

    .menu {
        width: 225px;
        background-color: #333;
        /* Button and Menu background color */
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        border-radius: 15px 15px 15px 15px;
        margin: 10px;
        overflow-y: auto;
        scrollbar-gutter: stable;
        scrollbar-color: #e0e0e0 rgba(0, 0, 0, 0);
        scrollbar-width: thin;
    }

    .menu-title {
        font-size: 24px;
        color: #fff;
        text-align: center;
        margin-bottom: 20px;
    }

    .menu ul {
        list-style: none;
        padding: 0;
    }

    .menu ul li {
        margin-bottom: 10px;
    }

    .menu ul li a {
        text-decoration: none;
        color: #fff;
        padding: 10px;
        display: flex;
        align-items: center;
        background-color: #333;
        /* Same color as menu bar */
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .menu ul li a:hover {
        background-color: #000;
        /* Darker blue on hover */
    }

    .menu-icon {
        margin-right: 10px;
    }

    .menu-icon-l {
        margin-left: 10px;
    }

    .content {
        flex-grow: 1;
        padding: 20px;
        /*background-color: #fff;*/
        border-radius: 0 17px 17px 0;
    }

    .content-section {
        display: none;
    }

    .content-section.active {
        display: block;
    }


    .container1 {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        /*box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);*/
    }


    .availability-table {
        width: 100%;
        margin-top: 0px;
        border-collapse: collapse;

    }

    .availability-table th,
    .availability-table td {
        padding: 15px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .availability-table th {
        background-color: #333;
        color: white;
    }

    .availability-table td.full {
        color: #FF5722;
        background-color: #fff;
        font-weight: bold;
    }

    .container_2 {
        max-width: 1200px;
        max-height: 900px;
        margin: 20px auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .cards {
        max-width: 800px;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 10px;
        padding: 20px;
        width: 300px;
        text-align: center;
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .header {
        font-size: 24px;
        color: #333;
        margin-bottom: 10px;
    }

    .stats {
        font-size: 18px;
        margin: 10px 0;
    }

    .progress {
        background-color: #f3f3f3;
        border-radius: 20px;
        height: 20px;
        margin: 10px 0;
    }

    .progress-bar {
        height: 100%;
        border-radius: 20px;
        transition: width 0.3s;
    }

    .available {
        background-color: #1da762;
    }

    .occupied {
        background-color: #c53c3c;
    }

    .filter-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 30px;
    }

    .filter-btn {
        text-decoration: none;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .filter-btn:hover {
        background-color: #000;
    }

    .filter-btn.active {
        background-color: #000;
        /* Active state */
    }

    .container-complaints {
        width: 890px;
        height: 510px;
        background-color: #fff;
        border-radius: 10px;
        /*box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);*/
        padding: 40px 30px;
        overflow-y: auto;
        /* Adds vertical scroll bar */
        scrollbar-gutter: stable;
        scrollbar-color: #333 #e0e0e0;
        scrollbar-width: thin;
    }

    .container-complaints h2 {
        font-size: 28px;
        margin-bottom: 30px;
        text-align: center;
        color: #333;
    }

    /* Card-Style Complaint Listing */
    .complaints-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .user-card,
    .car-card,
    .complaint-card {
        background-color: #FFF;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 350px;
        padding: 20px;
        transition: transform 0.3s ease;
    }

    .user-card:hover,
    .car-card:hover,
    .complaint-card:hover {
        transform: translateY(-5px);
    }


    .user-card h3,
    .car-card h3,
    .complaint-card h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #333;
    }

    .user-card p,
    .car-card p,
    .complaint-card p {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
    }

    .user-card p span,
    .car-card p span,
    .complaint-card p span {
        font-weight: bold;
        color: #333;
    }

    .user-card .action-btn,
    .car-card .action-btn,
    .complaint-card .action-btn {
        padding: 10px 15px;
        border-radius: 5px;
        border: none;
        background-color: #333;
        color: #fff;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
        display: flex;
        justify-self: center;
    }

    .user-card .action-btn:hover,
    .car-card .action-btn:hover,
    .complaint-card .action-btn:hover {
        background-color: #000;
    }

    .cardp-3 {
        width: auto;
    }

    .clock-container {
        height: 510px;
        text-align: center;
        padding: 20px 40px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
    }

    .time {
        font-size: 64px;
        font-weight: bold;
        margin-bottom: 10px;
        letter-spacing: 2px;
    }

    .date {
        font-size: 20px;
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    th button {
        margin-left: 5px;
        font-size: 12px;
    }

    .btn-primary.btn-sm {
        margin-bottom: 10px;
    }

    #vehicle-search-form,
    #user-search-form {
        margin-left: 20%;
        width: 550px;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
    }
</style>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img height="40px" width="40px" src="./logo.png" alt="" id="logoimg">
                <h1><strong>KFU Parking System</strong></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="./dashboard_admin.php"><i class="menu-icon fa-solid fa-house"></i>Home</a></li>
                    <li><a href="./Ad-Floors.php"><i class="menu-icon fa-solid fa-building"></i>Building 17</a></li>
                    <li><a href="./Ad-Floors2.php"><i class="menu-icon fa-solid fa-building"></i>Building 4</a></li>
                </ul>
            </nav>
            <div class="welcoming">
            </div>
            <div class="login-section">
                <a href="logout.php" class="login-btn">Logout<i class="menu-icon-l fa-solid fa-power-off"></i></a>
            </div>
        </div>
    </header>
    <div class="wrapper">
        <div class="container_1">
            <div class="menu">
                <h1 class="menu-title">Dashboard</h1>
                <ul>
                    <li><a href="#" class="menu-item" data-content="content9"><i class="menu-icon fa-solid fa-users"></i></i>Users List</a></li>
                    <li><a href="#" class="menu-item" data-content="content5"><i class="menu-icon fa-solid fa-rectangle-list"></i>Parking List</a></li>
                    <li><a href="#" class="menu-item" data-content="content2"><i class="menu-icon fa-solid fa-flag"></i>Complaints</a></li>
                    <li><a href="#" class="menu-item" data-content="content3"><i class="menu-icon fa-solid fa-chart-simple"></i></i>Parking Analysis</a></li>
                    <li><a href="#" class="menu-item" data-content="content4"><i class="menu-icon fa-solid fa-car"></i></i>Vehicles</a></li>
                    <li><a href="#" class="menu-item" data-content="content6"><i class="menu-icon fa-solid fa-money-bill-transfer"></i></i></i>Transactions</a></li>
                    <li><a href="#" class="menu-item" data-content="content7"><i class="menu-icon fa-solid fa-bars-progress"></i></i>Control Bookings</a></li>
                    <li><a href="#" class="menu-item" data-content="content8"><i class="menu-icon fa-solid fa-clipboard"></i>Booking Records</a></li>
                </ul>
            </div>
            <div class="content">
                <div id="content0" class="content-section active">
                    <div class="clock-container">
                        <div id="time" class="time"></div>
                        <div id="date" class="date"></div>
                    </div>
                </div>
                <div id="content5" class="content-section">
                    <div class="container-complaints">
                        <div class="cardp-3">
                            <h2><strong>Parkings List</strong></h2>
                            <h2>Building 17</h2>
                            <?php if (count($rooms) > 0): ?>
                                <!-- Add a Default Sort Button -->
                                <div class="mb-2">

                                </div>
                                <table class="table table-striped" id="parking-table">
                                    <button class="btn btn-primary btn-sm" onclick="defaultSort()">Default Sort <i class="fa-solid fa-sort"></i></button>
                                    <thead>
                                        <tr>
                                            <th onclick="sortTable(0)">
                                                Floor <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTable(1)">
                                                Parking Number <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTable(2)">
                                                Status <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTable(3)">
                                                Booked By <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTable(4)">
                                                Booking Time <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rooms as $room): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($room['floor']); ?></td>
                                                <td><?php echo htmlspecialchars($room['parking_number']); ?></td>
                                                <td>
                                                    <?php echo $room['status'] === 'available'
                                                        ? '<span class="badge bg-success">Available</span>'
                                                        : '<span class="badge bg-secondary">Booked</span>'; ?>
                                                </td>
                                                <td><?php echo $room['booked_by_username'] ?? '- Not booked'; ?></td>
                                                <td><?php echo $room['booking_time'] ? htmlspecialchars($room['booking_time']) . ' Hours' : 'N/A'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-warning">No parkings available at the moment.</div>
                            <?php endif; ?>
                        </div>
                        <script>
                            // Save the default order of rows
                            let defaultRows = Array.from(document.querySelectorAll('#parking-table tbody tr'));

                            // Sort table by column index
                            function sortTable(columnIndex) {
                                const table = document.getElementById('parking-table');
                                const rows = Array.from(table.tBodies[0].rows);
                                const isAscending = table.getAttribute('data-sort-dir') === 'asc';
                                table.setAttribute('data-sort-dir', isAscending ? 'desc' : 'asc');

                                rows.sort((a, b) => {
                                    const cellA = a.cells[columnIndex].innerText.trim();
                                    const cellB = b.cells[columnIndex].innerText.trim();

                                    if (!isNaN(cellA) && !isNaN(cellB)) {
                                        return isAscending ? cellA - cellB : cellB - cellA;
                                    }

                                    return isAscending ?
                                        cellA.localeCompare(cellB) :
                                        cellB.localeCompare(cellA);
                                });

                                rows.forEach(row => table.tBodies[0].appendChild(row));
                            }

                            // Restore the default order of rows
                            function defaultSort() {
                                const tableBody = document.querySelector('#parking-table tbody');
                                defaultRows.forEach(row => tableBody.appendChild(row));
                            }
                        </script>
                    </div>
                </div>
                <div id="content2" class="content-section">
                    <div class="container-complaints">
                        <h2><strong>Complaints List</strong></h2>

                        <div class="filter-buttons">
                            <button onclick="fetchComplaints('solved')" class="filter-btn">Solved</button>
                            <button onclick="fetchComplaints('unsolved')" class="filter-btn">Unsolved</button>
                            <button onclick="fetchComplaints('all')" class="filter-btn">All</button>
                        </div>

                        <div id="complaintsList" class="complaints-list">
                            <p>Loading complaints...</p>
                        </div>
                    </div>
                </div>
                <div id="content3" class="content-section">
                    <div class="container-complaints">
                        <div class="container_2">
                            <h1>Parking Status Analysis: Building 17</h1>
                            <!--<button onclick="location.reload()">Refresh Data</button>  Button to refresh data -->
                            <div class='cards'>
                                <?php
                                // Group parking spots by floor
                                $floors = [];
                                foreach ($parkings as $parking) {
                                    $floor = floor($parking['id_sen'] / 1000); // Calculate floor based on id_sen
                                    if (!isset($floors[$floor])) {
                                        $floors[$floor] = [];
                                    }
                                    $floors[$floor][] = $parking;
                                }

                                // Display parking status by floor using cards
                                foreach ($floors as $floor => $parkingsOnFloor) {
                                    $totalOnFloor = count($parkingsOnFloor);
                                    $availableOnFloor = count(array_filter($parkingsOnFloor, function ($p) {
                                        return $p['status_light'] == 0;
                                    }));
                                    $occupiedOnFloor = $totalOnFloor - $availableOnFloor;

                                    // Calculate percentages with two decimal places
                                    $availablePercentage = ($totalOnFloor > 0) ? number_format(($availableOnFloor / $totalOnFloor) * 100, 2) : number_format(0, 2);
                                    $occupiedPercentage = ($totalOnFloor > 0) ? number_format(($occupiedOnFloor / $totalOnFloor) * 100, 2) : number_format(0, 2);

                                    // Card for each floor
                                    echo "<div class='card'>";
                                    echo "<div class='header'>Floor " . $floor . "</div>";
                                    echo "<div class='stats'>";
                                    echo "Total Spots: $totalOnFloor<br>";
                                    echo "Available: $availableOnFloor ($availablePercentage%)<br>";
                                    echo "Occupied: $occupiedOnFloor ($occupiedPercentage%)";
                                    echo "</div>";

                                    // Progress bars
                                    echo "<div class='progress'>";
                                    echo "<div class='progress-bar available' style='width: $availablePercentage%;'></div>";
                                    echo "</div>";
                                    echo "<div class='progress'>";
                                    echo "<div class='progress-bar occupied' style='width: $occupiedPercentage%;'></div>";
                                    echo "</div>";

                                    echo "</div>"; // Close card
                                }
                                ?>
                            </div>
                        </div>
                        <table class="availability-table" id="availabilityTable">
                            <tr>
                                <th colspan="2">Available Spots</th>
                                <!--<th>Available Spots</th>-->
                            </tr>
                            <!-- Data will be inserted here by JavaScript -->
                        </table>
                    </div>
                </div>
                <div id="content4" class="content-section">
                    <div class="container-complaints">
                        <h2><strong>Vehicles List</strong></h2>
                        <!-- Search Bar -->
                        <div class="search-bar mb-3">
                            <form id="vehicle-search-form">
                                <select id="search-category" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                    <option value="owner">Owner</option>
                                    <option value="carname">Car Name</option>
                                    <option value="brand">Brand</option>
                                    <option value="plate">Plate</option>
                                    <option value="color">Color</option>
                                </select>
                                <input type="text" id="search-input" class="form-control form-control-sm d-inline-block" style="width: auto;" placeholder="Search...">
                                <button type="button" class="" onclick="filterVehicles()">Search</button>
                                <button type="button" class="" onclick="resetVehicles()">Reset</button>
                            </form>
                        </div>

                        <div class="complaints-list" id="vehicles-list">
                            <?php if (empty($result1)): ?>
                                <p>No cars.</p>
                            <?php else: ?>
                                <?php foreach ($result1 as $car): ?>
                                    <div class="car-card"
                                        data-owner="<?php echo htmlspecialchars($car['first_name']) . ' ' . htmlspecialchars($car['last_name']); ?>"
                                        data-carname="<?php echo htmlspecialchars($car['Carname']); ?>"
                                        data-brand="<?php echo htmlspecialchars($car['Brand']); ?>"
                                        data-plate="<?php echo htmlspecialchars($car['plate']); ?>"
                                        data-color="<?php echo htmlspecialchars($car['Color']); ?>">
                                        <p><span>Owner:</span> <?php echo htmlspecialchars($car['first_name']); ?> <?php echo htmlspecialchars($car['last_name']); ?></p>
                                        <p><span>Car Name:</span> <?php echo htmlspecialchars($car['Carname']); ?></p>
                                        <p><span>Brand:</span> <?php echo htmlspecialchars($car['Brand']); ?></p>
                                        <p><span>Plate:</span> <?php echo htmlspecialchars($car['plate']); ?></p>
                                        <p><span>Color:</span> <?php echo htmlspecialchars($car['Color']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <script>
                        function filterVehicles() {
                            // Get selected search category and input value
                            const category = document.getElementById('search-category').value;
                            const searchInput = document.getElementById('search-input').value.trim().toLowerCase();
                            const vehicles = document.querySelectorAll('.car-card'); // Get all cards

                            vehicles.forEach(vehicle => {
                                const value = vehicle.dataset[category]; // Get corresponding data attribute
                                if (value && value.toLowerCase().includes(searchInput)) {
                                    vehicle.style.display = 'block'; // Show matching card
                                } else {
                                    vehicle.style.display = 'none'; // Hide non-matching card
                                }
                            });
                        }

                        function resetVehicles() {
                            // Clear input field
                            document.getElementById('search-input').value = '';
                            const vehicles = document.querySelectorAll('.car-card');
                            vehicles.forEach(vehicle => {
                                vehicle.style.display = 'block'; // Show all cards
                            });
                        }
                    </script>

                </div>
                <div id="content6" class="content-section">
                    <div class="container-complaints">
                        <div class="cardp-3">
                            <h2><strong>Transactions</strong></h2>
                            <?php if (count($trans) > 0): ?>
                                <!-- Default Sort Button -->
                                <div class="mb-2">
                                    <button class="btn btn-primary btn-sm" onclick="defaultSortTransactions()">Default Sort<i class="fa-solid fa-sort"></i></button>
                                </div>
                                <table class="table table-striped" id="transactions-table">
                                    <thead>
                                        <tr>
                                            <th onclick="sortTransactions(0)">
                                                Username <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTransactions(1)">
                                                Full Name <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTransactions(2)">
                                                Amount <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTransactions(3)">
                                                Type <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                            <th onclick="sortTransactions(4)">
                                                Date <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-sort"></i></button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trans as $tran): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($tran['username']); ?></td>
                                                <td><?php echo htmlspecialchars($tran['first_name']); ?> <?php echo htmlspecialchars($tran['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($tran['amount']); ?></td>
                                                <td><?php echo htmlspecialchars($tran['transaction_type']); ?></td>
                                                <td><?php echo htmlspecialchars($tran['transaction_date']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-warning">No transactions available at the moment.</div>
                            <?php endif; ?>
                        </div>

                        <script>
                            // Save the default order of transaction rows
                            let defaultTransactionRows = Array.from(document.querySelectorAll('#transactions-table tbody tr'));

                            // Sort Transactions Table
                            function sortTransactions(columnIndex) {
                                const table = document.getElementById('transactions-table');
                                const rows = Array.from(table.tBodies[0].rows);
                                const isAscending = table.getAttribute('data-sort-dir') === 'asc';
                                table.setAttribute('data-sort-dir', isAscending ? 'desc' : 'asc');

                                rows.sort((a, b) => {
                                    const cellA = a.cells[columnIndex].innerText.trim();
                                    const cellB = b.cells[columnIndex].innerText.trim();

                                    // Check if the column contains numbers
                                    if (!isNaN(cellA) && !isNaN(cellB)) {
                                        return isAscending ? cellA - cellB : cellB - cellA;
                                    }

                                    // Default to string comparison
                                    return isAscending ?
                                        cellA.localeCompare(cellB) :
                                        cellB.localeCompare(cellA);
                                });

                                rows.forEach(row => table.tBodies[0].appendChild(row));
                            }

                            // Restore Default Transaction Rows
                            function defaultSortTransactions() {
                                const tableBody = document.querySelector('#transactions-table tbody');
                                defaultTransactionRows.forEach(row => tableBody.appendChild(row));
                            }
                        </script>

                    </div>
                </div>
                <div id="content7" class="content-section">
                    <div class="container-complaints">
                        <div class="cardp-3">
                            <h2><strong>Controller of Parkings</strong></h2>
                            <?php if (count($booked_rooms) > 0): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Booked By</th>
                                            <th>Floor</th>
                                            <th>Parking Number</th>
                                            <th>Booking Time (Hours)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($booked_rooms as $room): ?>
                                            <tr>
                                                <td><?php foreach ($users as $user) {
                                                        if ($user['user_id'] == $room['booked_by']) {
                                                            echo $user['first_name'] . " " . $user['last_name'];
                                                        }
                                                    } ?></td>
                                                <td><?php echo htmlspecialchars($room['floor']); ?></td>
                                                <td><?php echo htmlspecialchars($room['parking_number']); ?></td>
                                                <td><?php echo htmlspecialchars($room['booking_time']); ?> Hours</td>
                                                <td>
                                                    <form method="POST" action="ad_release_room.php" class="d-inline">
                                                        <input type="hidden" name="parking_id" value="<?php echo htmlspecialchars($room['parking_id']); ?>">
                                                        <button type="submit" class="btn btn-danger">Release</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-info">Thare is no booked parking at the moment.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div id="content8" class="content-section">
                    <div class="container-complaints">
                        <div class="cardp-3">
                            <h2><strong>Booking Records</strong></h2>
                            <?php if (count($records) > 0): ?>
                                <!-- Add a Default Sort Button -->
                                <div class="mb-2">
                                </div>
                                <table class="table table-striped1" id="parking-table1">
                                    <thead>
                                        <tr>
                                            <th>
                                                Operation Type
                                            </th>
                                            <th>
                                                Floor
                                            </th>
                                            <th>
                                                Parking Number
                                            </th>
                                            <th>
                                                Username
                                            </th>
                                            <th>Record Date </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recordd as $room): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($room['operation_type']); ?></td>
                                                <td><?php echo htmlspecialchars($room['floor']); ?></td>
                                                <td><?php echo htmlspecialchars($room['parking_number']); ?></td>
                                                <td><?php echo htmlspecialchars($room['username']); ?></td>
                                                <td><?php echo htmlspecialchars($room['operation_date']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-warning">No booking records at the moment.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div id="content9" class="content-section">
                    <div class="container-complaints">
                        <h2><strong>Users List</strong></h2>
                        <!-- Search Bar -->
                        <div class="search-bar mb-3">
                            <form id="user-search-form" onsubmit="return false;">
                                <select id="filter-category" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                    <option value="uusername">Username</option>
                                    <option value="ufirst_name">Full Name</option>
                                    <option value="uemail">Email</option>
                                    <option value="uphone">Phone Number</option>
                                </select>
                                <input type="text" id="filter-input" class="form-control form-control-sm d-inline-block" style="width: auto;" placeholder="Search...">
                                <button type="button" id="filter-search-btn" class="">Search</button>
                                <button type="button" id="filter-reset-btn" class="">Reset</button>
                            </form>
                        </div>
                        <div class="complaints-list" id="user-list">
                            <?php if (empty($users)): ?>
                                <p>No users found.</p>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <div class="user-card"
                                        data-ufirst_name="<?php echo htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']); ?>"
                                        data-uusername="<?php echo htmlspecialchars($user['username']); ?>"
                                        data-uemail="<?php echo htmlspecialchars($user['email']); ?>"
                                        data-uphone="<?php echo htmlspecialchars($user['phone']); ?>">
                                        <p><span>Username:</span> <?php echo htmlspecialchars($user['username']); ?></p>
                                        <p><span>Full Name:</span> <?php echo htmlspecialchars($user['first_name']); ?> <?php echo htmlspecialchars($user['last_name']); ?></p>
                                        <p><span>Email:</span> <?php echo htmlspecialchars($user['email']); ?></p>
                                        <p><span>Phone Number:</span> <?php echo htmlspecialchars($user['phone']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <script>
                        // Add event listeners for the Search and Reset buttons
                        document.getElementById('filter-search-btn').addEventListener('click', function() {
                            const category = document.getElementById('filter-category').value; // Get selected category
                            const query = document.getElementById('filter-input').value.toLowerCase().trim(); // Get search input
                            const userCards = document.querySelectorAll('.user-card'); // Select all user cards

                            // Loop through each card and check for a match
                            userCards.forEach(card => {
                                const attributeValue = card.getAttribute(`data-${category}`).toLowerCase(); // Get attribute value
                                if (attributeValue.includes(query)) {
                                    card.style.display = 'block'; // Show card if it matches
                                } else {
                                    card.style.display = 'none'; // Hide card if it doesn't match
                                }
                            });
                        });

                        // Reset the search to display all users
                        document.getElementById('filter-reset-btn').addEventListener('click', function() {
                            document.getElementById('filter-input').value = ''; // Clear search input
                            const userCards = document.querySelectorAll('.user-card'); // Select all user cards
                            userCards.forEach(card => {
                                card.style.display = 'block'; // Show all cards
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Parking System. All rights reserved.</p>
    </footer>
    <script>
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();

                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.remove('active');
                });

                const contentId = this.getAttribute('data-content');
                document.getElementById(contentId).classList.add('active');
            });
        });

        function fetchParkingData() {
            fetch('fetch_parking_status.php')
                .then(response => {
                    console.log(response);
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    const floors = {};

                    data.forEach(parking => {
                        const floor = Math.floor(parking.id_sen / 1000);
                        if (!floors[floor]) {
                            floors[floor] = [];
                        }
                        floors[floor].push(parking);
                    });

                    const tableBody = document.getElementById('availabilityTable');
                    tableBody.innerHTML = `
                <tr>
                    <th colspan="2">Available Spots</th>
                    
                </tr>
            `;
                    const floorNames = {

                        1: "Ground",
                        2: "1st",
                        3: "2nd"
                    };

                    // Populate the table
                    for (const floor in floors) {
                        const parkingsOnFloor = floors[floor];
                        const totalOnFloor = parkingsOnFloor.length;
                        const availableOnFloor = parkingsOnFloor.filter(p => p.status_light == 0).length;

                        const fullStatus = (availableOnFloor === 0) ? "Full" : availableOnFloor;

                        const row = `<tr>
                                <td>${floorNames[floor] || floor}</td>
                                <td class="${(availableOnFloor === 0) ? 'full' : ''}">${fullStatus}</td>
                             </tr>`;
                        tableBody.innerHTML += row;
                    }
                })
                .catch(error => console.error('Error fetching parking data:', error));
        }

        document.addEventListener('DOMContentLoaded', fetchParkingData);

        async function fetchComplaints(filter = 'all') {
            const response = await fetch(`fetch_complaints.php?filter=${filter}`);
            const complaints = await response.json();
            const complaintsList = document.getElementById('complaintsList');

            complaintsList.innerHTML = '';

            if (complaints.length === 0) {
                complaintsList.innerHTML = '<p>No complaints available.</p>';
                return;
            }

            complaints.forEach(complaint => {
                const complaintCard = document.createElement('div');
                complaintCard.classList.add('complaint-card');
                complaintCard.innerHTML = `
            <h3>${complaint.Fullname}</h3>
            <p><span>Email:</span> ${complaint.Emailaddress}</p>
            <p><span>Telephone:</span> ${complaint.Telephone}</p>
            <p><span>Date:</span> ${complaint.date_field}</p>
            <p><span>Status:</span> ${complaint.state}</p>
            <p><span>Complaint:</span> ${complaint.Complaint}</p>
            ${complaint.state === 'unsolved' ? `
                <button class="action-btn" onclick="markComplaintSolved(${complaint.Id})">Mark as Solved</button>
            ` : ''}
        `;
                complaintsList.appendChild(complaintCard);
            });
        }
        async function markComplaintSolved(complaintId) {
            try {
                const response = await fetch('mark_complaint_solved.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${encodeURIComponent(complaintId)}`
                });

                const result = await response.json();

                if (result.success) {
                    alert('Complaint marked as solved.');
                    fetchComplaints();
                } else {
                    alert(result.error || 'An error occurred.');
                }
            } catch (error) {
                console.error('Error marking complaint as solved:', error);
                alert('Failed to mark complaint as solved.');
            }
        }

        fetchComplaints();

        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const day = now.toLocaleString('default', {
                weekday: 'long'
            });
            const month = now.toLocaleString('default', {
                month: 'long'
            });
            const date = now.getDate();
            const year = now.getFullYear();

            const timeString = `${hours}:${minutes}:${seconds}`;
            const dateString = `${day}, ${month} ${date}, ${year}`;

            document.getElementById('time').textContent = timeString;
            document.getElementById('date').textContent = dateString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>

</html>