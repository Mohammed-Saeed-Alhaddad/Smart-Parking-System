<?php
include('includes/db.php');
include('includes/functions.php');
startSession();
// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    redirectWithMessage('login_admin.php', 'Please login first');
}
// Fetch all 
$stmt = $conn->prepare("SELECT * FROM parkings");
$stmt->execute();
$rooms = $stmt->fetchAll();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KFU Parking System: Main Page</title>
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
        /*background-image: linear-gradient(rgba(0, 0, 0, 0.7),rgba(0, 0, 0, 0.7)) , url("https://cdn.vectorstock.com/i/500p/95/82/underground-car-parking-basement-garage-vector-29179582.jpg");

background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)) , url("https://png.pngtree.com/background/20230611/original/pngtree-an-empty-parking-garage-at-a-night-time-picture-image_3160050.jpg");
*/
        background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5)), url("./BKG_10.jpeg");
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

    html {
        scrollbar-gutter: stable;
        scrollbar-color: #333 #e0e0e0;
        scrollbar-width: thin;
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
        justify-self: center;
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
        background: #3478BF;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    button[type="button"]:hover,
    button[type="submit"]:hover {
        background-color: #1C4169;
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
        max-width: 1500px;
        padding: 50px 0;
    }

    .floor-selection {
        background-color: #fff;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        max-width: 1700px;
        width: 100%;
        text-align: center;
    }

    .floor-selection h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .floor-buttons {

        margin-bottom: 30px;
    }

    .floor-btn {
        padding: 10px 20px;
        /*background: #717996;*/
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        margin-left: 50px;
        margin-right: 50px;
    }

    .floor-btn:hover {
        background: #5a7de4;
    }

    /* Parking */
    /*.parking-lot1 {
width: 550px;
height: 400px;
background-image: url("Parking-sample.png");
background-size: cover;
background-color: #525252;
padding: 20px;
border-radius: 0px;
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
position: relative;
}*/

    .parking-lot {
        padding: 20px;
        border-radius: 5px;
        position: relative;
        width: 550px;
        height: 400px;
        background-color: #333;
        background-image: url("Parking-sample.png");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center
    }

    .parking-row {
        display: flex;
        position: absolute;
    }

    .up {
        top: 10px;
        left: 0;
        right: 0;
        justify-content: center;
    }

    .down {
        bottom: 10px;
        left: 0;
        right: 0;
        justify-content: center;
    }

    .right,
    .left {
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100px;
    }

    .right {
        right: 0px;
    }

    .left {
        left: 15px;
    }

    .parking-slot-v,
    .bparking-slot-v,
    .bparking-slot-h,
    .parking-slot-h {
        background: rgb(240, 240, 240);
        border: 2px solid #c2c2c2;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #2c2c2c;
        font-size: 12px;
        font-weight: bold;
    }

    .parking-slot-v-empty,
    .parking-slot-h-empty {
        background: linear-gradient(45deg, rgba(0, 0, 0, 0), #1da762, rgba(0, 0, 0, 0));
        border: 2px solid #c2c2c2;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #2c2c2c;
        font-size: 12px;
        font-weight: bold;
    }

    /* blues parking */
    .bparking-slot-v-empty,
    .bparking-slot-h-empty {
        background: linear-gradient(45deg, rgba(0, 0, 0, 0), #3498db, rgba(0, 0, 0, 0));
        border: 2px solid #c2c2c2;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #2c2c2c;
        font-size: 12px;
        font-weight: bold;
    }

    .bparking-slot-v-full,
    .bparking-slot-h-full,
    .parking-slot-v-full,
    .parking-slot-h-full {
        background: linear-gradient(45deg, rgba(0, 0, 0, 0), #c53c3c, rgba(0, 0, 0, 0));
        border: 2px solid #c2c2c2;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #2c2c2c;
        font-size: 12px;
        font-weight: bold;
    }


    .bparking-slot-v,
    .bparking-slot-v-empty,
    .bparking-slot-v-full,
    .parking-slot-v,
    .parking-slot-v-empty,
    .parking-slot-v-full {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 70px;
        height: 40px;
        margin: 5px;
    }

    .bparking-slot-h,
    .bparking-slot-h-empty,
    .bparking-slot-h-full,
    .parking-slot-h,
    .parking-slot-h-empty,
    .parking-slot-h-full {
        width: 40px;
        height: 70px;
        margin: 5px;
    }

    .entry {
        width: 40px;
        height: 70px;
        background-color: transparent;
        border: 2px solid transparent;
        border-radius: 10px;
        margin: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #34495e;
        font-size: 12px;
        font-weight: bold;
    }

    /* Slots Container */
    .slots {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 750px;
        width: auto;
    }

    footer {
        color: #fff;
        text-align: center;
        padding: 20px 0;
        margin-top: auto;
        /* This pushes the footer to the bottom */
    }

    .color-box {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: 10px;
        vertical-align: middle;
        border-radius: 3px;
    }

    .color-box.green {
        background-color: #2ecc71;
        /* Green */
    }

    .color-box.red {
        background-color: #e74c3c;
        /* Red */
    }

    .color-box.blue {
        background-color: #3498db;
        /* Blue */
    }

    .welcoming {
        color: #fff;
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
        color: #3478BF;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 160px;
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
        color: #3478BF;
    }

    .dropdown:hover .dropdown-content {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .container1 {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        background-color: #3478BF;
        color: white;
    }

    .availability-table td.full {
        color: #FF5722;
        background-color: #fff;
        font-weight: bold;
    }

    #logoimg {
        margin-right: 20px;
    }








    .cd {
        height: 550px;
        width: 850px;
        /*background-color: aqua;  */

    }

    .cd1 {
        background-image: url("Parking-sample-.png");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        height: 600px;
        width: 900px;
        background-color: #999;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }


    .a1 {
        /*background-color: rgb(150, 150, 150);*/
        height: 650px;
        max-width: 1200px;
        width: 1200px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        /* transform: rotate(180deg);*/
    }

    .B1,
    .B3 {
        /* background-color: rgb(128, 3, 3);*/
        height: 230px;
        width: 125px;
        display: flex;
        justify-content: right;
        align-items: end;
    }

    .L1,
    .L3 {
        /*  background-color: rgb(100, 100, 100);*/
        height: 230px;
        width: 950px;
        display: flex;
        flex-wrap: wrap;
    }

    .LB2 {
        /*  background-color: rgb(50, 196, 45);*/
        height: 500px;
        width: 250px;

    }

    .LB2-1 {
        /*  background-color: rgb(216, 194, 0);*/
        height: 775px;
        width: 65px;
        transform: rotate(-45deg);
        margin-left: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: -130px;
    }

    .B2 {
        /* background-color: rgb(175, 0, 0);*/
        height: 500px;
        width: 150px;
    }

    .L2 {
        /*   background-color: rgb(190, 190, 190);*/
        height: 500px;
        width: 800px;
    }

    .I1 {
        /* background-color: rgb(20, 184, 148);*/
        height: 230px;
        width: 800px;
        display: flex;
        flex-wrap: wrap;
    }

    .I2 {
        /* background-color: rgb(13, 110, 89);*/
        height: 325px;
        width: 800px;
        display: flex;
        flex-direction: row;
    }

    .II1 {
        /* background-color: rgb(9, 65, 52);*/
        height: 150px;
        width: 600px;
    }

    .IB1 {
        /*background-color: rgb(42, 75, 67);*/
        height: 150px;
        width: 100px;
    }

    .BB2-1 {
        /* background-color: blueviolet;*/
        height: 200px;
        width: 150px;
        display: flex;
        justify-content: right;
    }

    .BB2-2 {
        /* background-color: rgb(166, 99, 230);*/
        height: 300px;
        width: 150px;
        display: flex;
    }

    .BB22-2 {
        /* background-color: rgb(216, 194, 0);*/
        height: 305px;
        width: 65px;
        transform: rotate(40deg);
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: -25px;
        margin-top: 15px;
    }

    .BB22-1 {
        /* background-color: rgb(216, 194, 0);*/
        height: 275px;
        width: 65px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: -110px;
    }

    .BB22-1s {
        background-color: rgb(216, 194, 0);
        height: 100px;
        /***********************************/
        width: 65px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .PL1 {
        /* background-color: rgb(216, 194, 0);*/
        height: 65px;
        min-height: 65px;
        width: 950px;
        max-width: 950px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: right;
    }

    .PL1-1 {
        /* background-color: rgb(216, 194, 0);*/
        height: 65px;
        width: 900px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: right;
        margin-left: -35px;
    }

    .EBLOCK {
        /* background-color: #fff;*/
        height: 65px;
        width: 25px;
    }

    .WL1 {
        /* background-color: rgb(255, 255, 255);*/
        height: 100px;
        width: 950px;
    }

    .PL2 {
        /* background-color: rgb(216, 194, 0);*/
        height: 65px;
        width: 800px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: right;
        margin-left: -35px;
    }

    .PL2-1 {
        /* background-color: rgb(216, 194, 0);*/
        height: 65px;
        width: 725px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: right;
        margin-left: -78px;
    }

    .WL2 {
        /*   background-color: rgb(255, 255, 255);*/
        height: 100px;
        width: 800px;
    }

    .PL3 {
        /*background-color: rgb(216, 194, 0);*/
        height: 60px;
        width: 600px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: right;
        margin-left: -69px;
    }

    .WL3 {
        /*background-color: rgb(255, 255, 255);*/
        height: 20px;
        width: 600px;
    }

    .form-step a {
        display: flex;
        flex-direction: column;
        color: #333;
        text-decoration: none;
        font-weight: bold;
        cursor: not-allowed;
        pointer-events: none;
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

    .P-S1-hid {
        background-color: azure;
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0%;

    }

    .P-S1 {
        background-color: azure;
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .P-S1-full {
        background-color: rgb(201, 12, 12);
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        cursor: not-allowed;
        pointer-events: none;
    }

    .P-S1-empty {
        background-color: rgb(11, 180, 62);
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;

    }

    .BP-S1 {
        /*background-color: rgb(31, 106, 204);*/
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .BP-S1-full {
        background-color: rgb(201, 12, 12);
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        cursor: not-allowed;
        pointer-events: none;
    }

    .BP-S1-empty {
        background-color: rgb(31, 106, 204);
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
    }

    .BV-S1 {
        /*background-color: rgb(31, 106, 204);*/
        border-radius: 5px;
        width: 50px;
        height: 26px;
        margin-top: 7px;
    }

    .BV-S1-empty {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        justify-content: space-around;
        background-color: rgb(31, 106, 204);
        border-radius: 5px;
        width: 50px;
        height: 26px;
        margin-top: 7px;
    }

    .V-S1 {
        background-color: azure;
        border-radius: 5px;
        width: 50px;
        height: 26px;
        margin-top: 7px;
    }

    .BV-S1-full,
    .V-S1-full {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        justify-content: space-around;
        background-color: rgb(201, 12, 12);
        border-radius: 5px;
        width: 50px;
        height: 26px;
        margin-top: 7px;
        cursor: not-allowed;
        pointer-events: none;
    }

    .V-S1-empty {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        justify-content: space-around;
        background-color: rgb(11, 180, 62);
        border-radius: 5px;
        width: 50px;
        height: 26px;
        margin-top: 7px;
    }

    .Card4 {
        display: flex;
        justify-content: right;
        flex-wrap: wrap;
        background-color: rgb(41, 41, 41);
        height: 800px;
        width: 1300px;
        background-image: url("Parking_base_.png");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .Card5 {
        display: flex;
        justify-content: right;
        flex-wrap: wrap;
        background-color: rgb(41, 41, 41);
        height: 800px;
        width: 1300px;
        background-image: url("Parking-sm.png");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .menu-icon {
        margin-right: 10px;
    }

    .menu-icon-l {
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

    <div class="container-floor-select">
        <div id="floor-selection-form" class="floor-selection">
            <h1><i class="menu-icon fa-solid fa-building"></i>Parking Building 4</h1>
            <br>
            <p><span style="font-size:10.0pt">(Click On The Floor to Find Available Parking)</span></p>
            <br>
            <div class="floor-buttons">
                <button type="button" class="floor-btn" data-step="0"><i class="menu-icon fa-solid fa-layer-group"></i>Ground</button>
                <button type="button" class="floor-btn" data-step="1"><i class="menu-icon fa-solid fa-layer-group"></i>1st</button>
                <button type="button" class="floor-btn" data-step="2"><i class="menu-icon fa-solid fa-layer-group"></i>2nd</button>
            </div>
            <div class="form-step">
                <h2>Ground Floor</h2><br>
                <div class="slots">
                    <h2>Soon</h2>
                </div>
            </div>
            <div class="form-step">
                <h2>Ground Floor</h2><br>
                <div class="slots">
                    <h2>Soon</h2>
                </div>
            </div>
            <div class="form-step">
                <h2>Ground Floor</h2><br>
                <div class="slots">
                    <h2>Soon</h2>
                </div>
            </div>
            <br><br>
            <!--<div class="input-box">
                    <h4>Colors and Their Meanings</h4>
                    <p><span class="color-box green"></span> Green Light Means The Parking Is Available.</p>
                    <p><span class="color-box red"></span> Red Light Means The Parking is Unavailable.</p>
                    <p><span class="color-box blue"></span> Blue Light Is Handicapped Parking.</p>
                </div>
            </div>-->
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Parking System. All rights reserved.</p>
    </footer>
    <script>
        const floorButtons = document.querySelectorAll('.floor-btn');
        const formSteps = document.querySelectorAll('.form-step');

        floorButtons.forEach(button => {
            button.addEventListener('click', () => {
                const step = button.getAttribute('data-step');
                showStep(step);
            });
        });

        function showStep(step) {
            formSteps.forEach((formStep, index) => {
                formStep.classList.toggle('form-step-active', index == step);
            });
        }

        function fetchParkingStatus() {
            fetch('fetch_parking_status.php')
                .then(response => response.json())
                .then(data => {
                    data.forEach(parking => {
                        // Find the div matching the id_sen
                        let parkingDiv = document.getElementById(parking.id_sen);
                        if (parkingDiv) {
                            // Update the class based on status
                            if (parking.status_light == 0) {
                                if (parkingDiv.classList.contains('P-S1')) {
                                    parkingDiv.classList.add('P-S1-empty');
                                    parkingDiv.classList.remove('P-S1-full');
                                } else if (parkingDiv.classList.contains('V-S1')) {
                                    parkingDiv.classList.add('V-S1-empty');
                                    parkingDiv.classList.remove('V-S1-full');
                                } else if (parkingDiv.classList.contains('BP-S1')) {
                                    parkingDiv.classList.add('BP-S1-empty');
                                    parkingDiv.classList.remove('BP-S1-full');
                                } else if (parkingDiv.classList.contains('BV-S1')) {
                                    parkingDiv.classList.add('BV-S1-empty');
                                    parkingDiv.classList.remove('BV-S1-full');
                                }

                            } else {
                                if (parkingDiv.classList.contains('P-S1')) {
                                    parkingDiv.classList.add('P-S1-full');
                                    parkingDiv.classList.remove('P-S1-empty');
                                } else if (parkingDiv.classList.contains('V-S1')) {
                                    parkingDiv.classList.add('V-S1-full');
                                    parkingDiv.classList.remove('V-S1-empty');
                                } else if (parkingDiv.classList.contains('BP-S1')) {
                                    parkingDiv.classList.add('BP-S1-full');
                                    parkingDiv.classList.remove('BP-S1-empty');
                                } else if (parkingDiv.classList.contains('BV-S1')) {
                                    parkingDiv.classList.add('BV-S1-full');
                                    parkingDiv.classList.remove('BV-S1-empty');
                                }

                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching parking status:', error));
        }

        function fetchParkingData() {
            fetch('fetch_parking_status.php')
                .then(response => {
                    console.log(response); // Check the response
                    return response.json();
                })
                .then(data => {
                    console.log(data); // Log the data received
                    const floors = {};

                    // Group parking spots by floor
                    data.forEach(parking => {
                        const floor = Math.floor(parking.id_sen / 1000); // Calculate floor based on id_sen
                        if (!floors[floor]) {
                            floors[floor] = [];
                        }
                        floors[floor].push(parking);
                    });

                    const tableBody = document.getElementById('availabilityTable');
                    // Clear existing table rows (except for headers)
                    tableBody.innerHTML = `
                    <tr>
                        <th colspan="2">Available Spots</th>
                        
                    </tr>
                `;

                    // Map floor numbers to names
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

        // Fetch parking data on page load
        document.addEventListener('DOMContentLoaded', fetchParkingData);

        function autoSelectFloor() {
            fetch('fetch_parking_status.php')
                .then(response => response.json())
                .then(data => {
                    const floors = {};

                    // Group parking spots by floor
                    data.forEach(parking => {
                        const floor = Math.floor(parking.id_sen / 1000); // Determine the floor based on ID
                        if (!floors[floor]) {
                            floors[floor] = [];
                        }
                        floors[floor].push(parking);
                    });

                    // Check availability starting from the ground floor
                    const sortedFloors = Object.keys(floors).sort((a, b) => a - b); // Sort floors by ascending order
                    for (const floor of sortedFloors) {
                        const availableSpots = floors[floor].filter(p => p.status_light == 0); // Check available spots
                        if (availableSpots.length > 0) {
                            // Simulate a click on the corresponding floor button
                            const button = document.querySelector(`.floor-btn[data-step="${floor - 1}"]`);
                            if (button) {
                                button.click();
                                return; // Stop checking after finding the first available floor
                            }
                        }
                    }
                })
                .catch(error => console.error('Error fetching parking data for auto-select:', error));
        }

        // Call autoSelectFloor on page load
        document.addEventListener('DOMContentLoaded', autoSelectFloor);



        // Fetch parking status on page load
        window.onload = fetchParkingStatus;
    </script>
</body>

</html>