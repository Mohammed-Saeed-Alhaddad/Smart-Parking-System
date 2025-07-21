<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$message = ''; // To store success or error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'p_system'); // Adjust as per your DB setup

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    //$userID = $_SESSION['userID']; // Assuming you store the user ID in session
    $fullname = $_POST['name'];
    $email = $_POST['_replyto'];
    $telephone = $_POST['telephone'] ?? NULL; // Optional field
    $complaint = $_POST['complaint'];

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO Complaints (Fullname, Emailaddress, Telephone, Complaint) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $email, $telephone, $complaint);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        $message = "Complaint submitted successfully!";
    } else {
        $message = "Error submitting complaint: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Form</title>
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
        background-image: linear-gradient(rgba(0, 0, 0, 0.3),rgba(0, 0, 0, 0.5)) , url("./BKG_9.jpeg");/*
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
        min-height: 450px;
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
    input[type="submit"],
    button[type="submit"] {
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

    input[type="submit"]:hover,button[type="submit"]:hover {
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
            font-size: 20px; /* Icon size */
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
                <h2><strong>Complaint Form</strong></h2>

                <!-- Display the success or error message -->
                <?php if (!empty($message)): ?>
                    <div class="message">
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>

                <form id="fs-frm" name="complaint-form" action="Complaints.php" method="post">
                    <div class="input-box">
                        <label for="full-name">Full Name</label>
                        <input type="text" name="name" id="full-name" placeholder="First and Last" required="">
                    </div>
                    <div class="input-box">
                        <label for="email-address">Email Address</label>
                        <input type="email" name="_replyto" id="email-address" placeholder="email@domain.tld" required="">
                    </div>
                    <div class="input-box">
                        <label for="telephone">Telephone Number (Optional)</label>
                        <input type="tel" name="telephone" id="telephone" placeholder="(555) 555-5555">
                    </div>
                    <div class="input-box">
                        <label for="complaint">Complaint</label>
                        <textarea name="complaint" id="complaint" placeholder="Describe your issue..." required=""></textarea>
                    </div>
                    <input type="hidden" name="_subject" id="email-subject" value="Complaint Form Submission">
                    <input type="hidden" value="File Complaint">
                    <button type="submit">Send Complaint<i class="menu-icon1 fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Parking System. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
