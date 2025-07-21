<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_user.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'p_system'); // Adjust as per your DB setup
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedInUsername = $_SESSION['username'];

$sql = "SELECT * FROM cars WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();

$cars = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carName = $_POST['car_name'];
    $brand = $_POST['brand'];
    $plate = $_POST['plate'];
    $color = $_POST['color'];

    $insertSql = "INSERT INTO cars (Username, Carname, Brand, plate, Color) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);

    if ($insertStmt === false) {
        die("Error preparing insert statement: " . $conn->error);
    }

    if (empty($carName) || empty($brand) || empty($color) || empty($plate)) {
        echo "<script>alert('Please fill all fields.');</script>";
    } else {
        $insertStmt->bind_param("sssss", $loggedInUsername, $carName, $brand,  $plate, $color);

        if ($insertStmt->execute()) {
            echo "<script>alert('Car added successfully!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Error adding car: " . $insertStmt->error . "');</script>";
        }
    }

    $insertStmt->close();
}

if (isset($_POST['delete']) && isset($_POST['car_id'])) {
    $carId = $_POST['car_id'];

    if (!empty($carId)) {
        echo "<script>console.log('Car ID to delete: " . $carId . "');</script>";

        $deleteSql = "DELETE FROM cars WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);

        if ($deleteStmt === false) {
            die("Error preparing delete statement: " . $conn->error);
        }

        $deleteStmt->bind_param("i", $carId);

        if ($deleteStmt->execute()) {
            echo "<script>alert('Car deleted successfully!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Error deleting car: " . $deleteStmt->error . "');</script>";
        }

        $deleteStmt->close();
    } else {
        echo "<script>alert('Car ID is missing. Cannot delete the car.');</script>";
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cars - KFU Parking System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./logo.png">
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
        color: #333;

        background-repeat: no-repeat;
        background-attachment: fixed;
        background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5)), url("./BKG_9.jpeg");
    }

    html {
        scrollbar-gutter: stable;
        scrollbar-color: #333 #e0e0e0;
        scrollbar-width: thin;
    }

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

    main {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 50px 20px;
    }

    .container {
        width: 100%;
        max-width: 800px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        padding: 40px 30px;
    }

    .container h2 {
        font-size: 28px;
        margin-bottom: 30px;
        text-align: center;
        color: #333;
    }

    .complaints-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .complaint-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 350px;
        padding: 20px;
        transition: transform 0.3s ease;
    }

    .complaint-card:hover {
        transform: translateY(-5px);
    }

    .complaint-card h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #333;
    }

    .complaint-card p {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
    }

    .complaint-card p span {
        font-weight: bold;
        color: #333;
    }

    .complaint-card .action-btn {
        padding: 10px 15px;
        border-radius: 5px;
        border: none;
        background-color: #3478BF;
        color: #fff;
        cursor: pointer;
        font-size: 14px;
    }

    .complaint-card .action-btn:hover {
        background-color: #1C4169;
    }

    footer {
        color: #fff;
        text-align: center;
        padding: 20px 0;
        margin-top: auto;
    }

    .add-car-form {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }

    .add-car-form input {
        height: 35px;
        width: 150px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .add-car-form button {
        background-color: #3478BF;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-car-form button:hover {
        background-color: #1C4169;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-btn {
        padding: 10px 25px;
        background-color: transparent;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        border: 2px solid #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dropdown-btn i {
        font-size: 20px;
    }

    .dropdown-btn:hover {
        background-color: #fff;
        color: #425699;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 180px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 8px;
        overflow: hidden;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .dropdown-content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 15px;
        transition: background-color 0.3s, color 0.3s;
    }

    .dropdown-content a:hover {
        background-color: #f4f4f4;
        color: #425699;
    }

    .dropdown:hover .dropdown-content {
        display: block;
        opacity: 1;
        transform: translateY(0);
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

    .menu-icon {
        margin-right: 10px;
    }

    .menu-icon1 {
        margin-right: 10px;
        margin-left: 10px;
    }
</style>

<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img height="40px" width="40px" src="./logo.png" alt="" id="logoimg">
                <h1><strong>KFU Parking System</strong></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="./Main.php"><i class="menu-icon1 fa-solid fa-house"></i>Home</a></li>
                    <li><a href="./Complaints.php"><i class="menu-icon1 fa-solid fa-flag"></i>Complaints</a></li>
                    <li><a href="./Contact.php"><i class="menu-icon1 fa-solid fa-circle-info"></i>Contact</a></li>
                </ul>
            </nav>
            <div class="welcoming">
            </div>

            <div class="login-section">
                <div class="dropdown">
                    <button class="dropdown-btn"><i class="menu-icon1 fa-solid fa-bars"></i></button>
                    <div class="dropdown-content">
                        <a href="./MyCars.php"><i class="menu-icon fa-solid fa-car-side"></i>My Cars</a>
                        <a href="./Wallet.php"><i class="menu-icon fa-solid fa-wallet"></i>Wallet</a>
                        <a href="./Reset-Password.php"><i class="menu-icon fa-solid fa-key"></i>Rest Password</a>
                        <!--<a href="#"><i class="menu-icon fa-solid fa-trash-can"></i> Delete Account</a>-->
                        <a href="update_building.php"><i class="menu-icon fa-solid fa-location-dot"></i>Change Building</a>
                        <a href="./Contact.php"><i class="menu-icon fa-solid fa-circle-info"></i>Help</a>
                        <a href="logout.php"><i class="menu-icon fa-solid fa-power-off"></i>Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <h2><strong>My Cars List</strong></h2>
            <div class="add-car-form">
                <h3>Add New Car</h3>
                <form method="POST" action="">
                    <input type="text" name="car_name" placeholder="Car Name" required>
                    <input type="text" name="brand" placeholder="Brand" required>
                    <input type="text" name="plate" placeholder="Plate (0000-ABC)" required>
                    <input type="text" name="color" placeholder="Color" required>
                    <button type="submit">Add Car</button>
                </form>
            </div>
            <br>
            <div class="complaints-list">
                <?php if (empty($cars)): ?>
                    <p>No cars.</p>
                <?php else: ?>
                    <?php foreach ($cars as $car): ?>
                        <div class="complaint-card">
                            <p><span>Car Name:</span> <?php echo htmlspecialchars($car['Carname']); ?></p>
                            <p><span>Brand:</span> <?php echo htmlspecialchars($car['Brand']); ?></p>
                            <p><span>Plate:</span> <?php echo htmlspecialchars($car['plate']); ?></p>
                            <p><span>Color:</span> <?php echo htmlspecialchars($car['Color']); ?></p>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['Id']); ?>">
                                <button type="submit" name="delete" class="action-btn" onclick="return confirm('Are you sure you want to delete this car?');">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Parking System. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>

</html>