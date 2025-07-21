<?php
include('includes/db.php');
include('includes/functions.php');
startSession();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    redirectWithMessage('login_user.php', 'Please login first');
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$userid = $stmt->fetch();

$user_id = $userid['user_id'];
$parking_id = $_GET['parking_id']; // Get the room ID from the URL

// Fetch the room details based on the room_id
$stmt = $conn->prepare("SELECT * FROM parkings WHERE parking_id = :parking_id");
$stmt->execute(['parking_id' => $parking_id]);
$parking = $stmt->fetch();

// If the room does not exist, redirect the user
if (!$parking) {
    redirectWithMessage('dashboard_user.php', 'Parking not found.');
}

// Fetch the user details (for balance)
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();






$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$userid = $stmt->fetch();

$user_id = $userid['user_id'];




if ($_SERVER["REQUEST_METHOD"] == "POST" && $parking['status'] == 'available' && $parking['status_light'] == '0') {
    $booking_time = $_POST['booking_time'];
    $total_cost = 10 * $booking_time; // Price calculation


    // Set booking and expiry time
    $cuurent_ti = date('Y-m-d H:i:s');

    if($booking_time == 1){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+1 hours'));
    } elseif($booking_time == 5){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+5 hours'));
    } elseif($booking_time == 10){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+10 hours'));
    } elseif($booking_time == 0.5){
        $expiry_time =  date('Y-m-d H:i:s', strtotime('+1 minutes'));
    }


    // Check if user has enough balance
    if ($user['balance'] >= $total_cost) {
        // Deduct balance from user
        $new_balance = $user['balance'] - $total_cost;
        $stmt = $conn->prepare("UPDATE users SET balance = :balance WHERE user_id = :user_id");
        $stmt->execute(['balance' => $new_balance, 'user_id' => $user_id]);


        // Update the room's status to booked and assign to the user
        $stmt = $conn->prepare("UPDATE parkings SET status = 'booked', booking_time = :booking_time, booked_by = :user_id, expiry_time = :expiry_time WHERE parking_id = :parking_id");
        $stmt->execute(['booking_time' => $booking_time, 'user_id' => $user_id, 'parking_id' => $parking_id, 'expiry_time' => $expiry_time]);
        
        // Log the transaction
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, transaction_type) VALUES (:user_id, :amount, 'booking')");
        $stmt->execute(['user_id' => $user_id, 'amount' => $total_cost]);

        $stmt = $conn->prepare("INSERT INTO operation_records (user_id, amount, operation_type, parking_id) VALUES (:user_id, :amount, 'booking', :parking_id)");
        $stmt->execute(['user_id' => $user_id, 'amount' => $total_cost, 'parking_id' => $parking_id]);

        // Redirect the user with a success message
        redirectWithMessage('main.php', 'Parking booked successfully!');
    } else {
        // Insufficient balance
        redirectWithMessage('book_room.php?parking_id=' . $parking_id, 'Insufficient balance to book this parking.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet</title>
    <link rel="icon" href="./logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styles */
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

            background-repeat: no-repeat;
            background-attachment: fixed;
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5)), url("./BKG_9.jpeg");
            /*
        background-image: linear-gradient(rgba(0, 0, 0, 0.7),rgba(0, 0, 0, 0.7)) , url("https://cdn.vectorstock.com/i/500p/95/82/underground-car-parking-basement-garage-vector-29179582.jpg");
    */
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

        /* Form Container */
        .login-form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .login-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 40px 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            width: 100%;
            text-align: left;
            min-height: auto;
        }

        .login-form h4{
            margin-top: 30px;
            color: #333;
            text-align: center;
        }
        .login-form h2 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #333;
            text-align: center;
        }

        /* Input Styles */
        .input-box {
            margin-bottom: 20px;
        }

        .input-box1 {
            max-width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row;
            justify-content: space-between;
        }

        .input-box label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .input-box input,
        .input-box textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-box input:focus,
        .input-box textarea:focus {
            border-color: #425699;
            box-shadow: 0 0 5px rgba(66, 86, 153, 0.5);
        }

        .input-box textarea {
            resize: none;
            height: 150px;
        }

        /* Submit Button Styles */
        .TP-btn {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #3478BF;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #3478BF;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
        }

        .TP-btn:hover,
        input[type="submit"]:hover {
            background-color: #1C4169;
            transform: translateY(-2px);
        }

        /* Footer Styles */
        footer {
            color: #fff;
            text-align: center;
            padding: 20px 0;
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
            /* Icon size */
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
                    <li><a href="./Main.php"><i class="menu-icon1 fa-solid fa-house"></i>Home</a></li>
                    <li><a href="./Complaints.php"><i class="menu-icon1 fa-solid fa-flag"></i>Complaints</a></li>
                    <li><a href="./Contact.php"><i class="menu-icon1 fa-solid fa-circle-info"></i>Contact</a></li>
                </ul>
            </nav>
            <div class="welcoming"> 
            </div>

            <div class="login-section">
                <!-- <a href="logout.php" class="login-btn">Logout</a> -->
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
        <div class="login-form-container">
            <div id="multi-step-form" class="login-form">
      
                <!--<h2>Book Parking</h2>
                 Display room details -->
                <h2><strong>Parking Details</strong></h2>
                <p><strong>Parking Number:</strong> <?php echo $parking['parking_number']; ?></p><br>
                <p><strong>Floor:</strong> <?php echo $parking['floor']; ?></p><br>
                <p><strong>Status:</strong> <?php echo $parking['status'] == 'available' ? 'Available' : 'Booked'; ?></p><br>
                <!-- If the room is available, show booking options -->
                <?php if ($parking['status'] == 'available'): ?>
                    <p><strong>Price:</strong> 10 SAR per Hour</p>
                    <br>
                    <form method="POST" action="book_room.php?parking_id=<?php echo $parking['parking_id']; ?>"><div class="input-box1">
                        <label for="booking_time">Booking Time (in Hours):</label>
                        <select class="TP-btn" name="booking_time" required>
                            <option value="0.5">1 Minutes</option>
                            <option value="1">1 Hours</option>
                            <option value="5">5 Hours</option>
                            <option value="10">10 Hours</option>
                        </select>
                        <br><br>
                        <button type="submit" class="TP-btn"><strong>Book Parking</strong></button>
                    </div></form>
                    
                <?php else: ?>
                    <h4>This parking is currently booked and cannot be booked again.</h4>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Parking System. All rights reserved.</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if JavaScript is loading correctly
            console.log('JavaScript loaded successfully.');

            // JavaScript to toggle the display of the Top-Up form
            document.getElementById('show-top-up-btn').addEventListener('click', function() {
                const topUpForm = document.getElementById('top-up-form');
                console.log('Button clicked.'); // Check if the button click is detected
                topUpForm.style.display = (topUpForm.style.display === 'none' || topUpForm.style.display === '') ? 'block' : 'none';
                console.log('Top-Up form display:', topUpForm.style.display); // Log the display status
            });
        });
    </script>
</body>

</html>