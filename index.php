<?php
include('includes/functions.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KFU - Parking System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .input-box a {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .badgeg {
            border-style: solid;
            border-width: 1px;
            border-color: #2c2c2c;
            background-color: green;
            border-radius: 25%;
            width: 15px;
            height: 10px;
        }

        .badger {
            border-style: solid;
            border-width: 1px;
            border-color: #2c2c2c;
            background-color: red;
            border-radius: 25%;
            width: 15px;
            height: 10px;
        }

        .menu-icon {
            margin-right: 10px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img height="50px" width="50px" src="logo.png" alt="">
            <div class="logo">
                <h1><strong>KFU Parking System</strong></h1>
            </div>
            <div class="login-section">
                <a href="./login_admin.php" class="login-btn">Administrator</a>
            </div>
        </div>
    </header>

    <main>
        <div class="login-form-container">
            <div class="login-form">
                <!-- Step 1 -->
                <div class="form-step form-step-active">
                    <h2><strong>Introduction</strong></h2>
                    <div class="input-box">
                        <h4>Welcome to Parking System.</h4>
                        <p>This web application aims to provide efficient and user-friendly parking management solutions. Whether you're a driver looking for available parking spaces or an administrator managing the parking lot, our system has you covered.</p>
                    </div>
                    <div class="button-group">
                        <button type="button" class="prev-btn" hidden>Previous</button>
                        <button type="button" class="next-btn">Next<i class="menu-icon fa-solid fa-forward"></i></button>
                    </div>
                    <br>
                    <a href="login_user.php"><strong>Already have Account</strong></a>
                </div>

                <!-- Step 2 -->
                <div class="form-step">
                    <h2><strong>Instructions</strong></h2>
                    <div class="input-box">
                        <h4>Colors and Their Meanings</h4>
                        <p><span class="color-box green"></span> Green light means the parking is available.</p>
                        <p><span class="color-box red"></span> Red light means the parking is unavailable (Can't be book).</p>
                        <p><span class="color-box blue"></span> Blue light is handicapped parking.</p>
                        <p><span class="color-box"><a><span class="badger"></span></a></span>Red inside light means the parking is bookded.</p>
                        <p><span class="color-box"><a><span class="badgeg"></span></a></span>Green inside light means the parking is bookable.</p>
                    </div>
                    <div class="button-group">
                        <button type="button" class="prev-btn"><i class="menu-icon fa-solid fa-backward"></i>Previous</button>
                        <button type="button" class="next-btn">Next<i class="menu-icon fa-solid fa-forward"></i></button>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="form-step">
                    <h2><strong>Let's Start</strong></h2>
                    <div class="input-box">
                        <h4>Ready to Get Started?</h4>
                        <p>Click the button below to register and start using our Parking System.</p>
                    </div>
                    <div class="button-group">
                        <button type="button" class="prev-btn"><i class="menu-icon fa-solid fa-backward"></i>Previous</button>
                        <button type="button" class="action-btn" onclick="location.href='./register.php'">Register<i class="menu-icon fa-solid fa-user-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Parking System. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>