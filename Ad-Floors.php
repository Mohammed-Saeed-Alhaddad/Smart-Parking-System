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
        display: flex;
        flex-direction: column;
    }

    .P-S1-empty {
        background-color: rgb(11, 180, 62);
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        display: flex;
        flex-direction: column;

    }

    .BP-S1 {
        /*background-color: rgb(31, 106, 204);*/
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        display: flex;
        flex-direction: column;
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
        display: flex;
        flex-direction: column;
    }

    .BP-S1-empty {
        background-color: rgb(31, 106, 204);
        border-radius: 5px;
        height: 50px;
        width: 26px;
        margin-right: 7px;
        display: flex;
        flex-direction: column;
    }

    .BV-S1 {
        /*background-color: rgb(31, 106, 204);*/
        border-radius: 5px;
        width: 50px;
        height: 26px;
        margin-top: 7px;
        display: flex;
        flex-direction: row;
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
        display: flex;
        flex-direction: row;
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
            <h1><i class="menu-icon fa-solid fa-building"></i>Parking Building 17</h1>

            <br>
            <p><span style="font-size:10.0pt">(Click On The Floor to Find Available Parking)</span></p>
            <div class="floor-buttons">
                <button type="button" class="floor-btn" data-step="0"><i class="menu-icon fa-solid fa-layer-group"></i>Ground</button>
                <button type="button" class="floor-btn" data-step="1"><i class="menu-icon fa-solid fa-layer-group"></i>1st</button>
                <button type="button" class="floor-btn" data-step="2"><i class="menu-icon fa-solid fa-layer-group"></i>2nd</button>
            </div>
            <div class="form-step">
                <h2>Ground Floor</h2><br>
                <div class="slots">
                    <div class="Card4">
                        <div class="A1">
                            <div class="B1"></div>
                            <div class="L1">
                                <div class="PL1">
                                    <a class="BP-S1" id="1020" href="book_room.php?parking_id=20">20
                                        <?php $parking = $rooms[19];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="BP-S1" id="1019" href="book_room.php?parking_id=19">19
                                        <?php $parking = $rooms[18];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="BP-S1" id="1018" href="book_room.php?parking_id=18">18
                                        <?php $parking = $rooms[17];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1017" href="book_room.php?parking_id=17">17
                                        <?php $parking = $rooms[16];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1016" href="book_room.php?parking_id=16">16
                                        <?php $parking = $rooms[15];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1015" href="book_room.php?parking_id=15">15
                                        <?php $parking = $rooms[14];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1014" href="book_room.php?parking_id=14">14
                                        <?php $parking = $rooms[13];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1013" href="book_room.php?parking_id=13">13
                                        <?php $parking = $rooms[12];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1012" href="book_room.php?parking_id=12">12
                                        <?php $parking = $rooms[11];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1011" href="book_room.php?parking_id=11">11<?php $parking = $rooms[10];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1-hid" id=""></a>
                                    <a class="P-S1" id="1010" href="book_room.php?parking_id=10">10<?php $parking = $rooms[9];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="1009" href="book_room.php?parking_id=9">9<?php $parking = $rooms[8];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="1008" href="book_room.php?parking_id=8">8<?php $parking = $rooms[7];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="1007" href="book_room.php?parking_id=7">7
                                        <?php $parking = $rooms[6];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="P-S1" id="1006" href="book_room.php?parking_id=6">6
                                        <?php $parking = $rooms[5];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="BP-S1" id="1005" href="book_room.php?parking_id=5">5
                                        <?php $parking = $rooms[4];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="BP-S1" id="1004" href="book_room.php?parking_id=4">4
                                        <?php $parking = $rooms[3];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="BP-S1" id="1003" href="book_room.php?parking_id=3">3
                                        <?php $parking = $rooms[2];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="BP-S1" id="1002" href="book_room.php?parking_id=2">2
                                        <?php $parking = $rooms[1];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="BP-S1" id="1001" href="book_room.php?parking_id=1">1
                                        <?php $parking = $rooms[0];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                </div>
                                <div class="WL1"></div>
                                <div class="EBLOCK"></div>
                                <div class="PL1-1">
                                    <a class="P-S1" id="1085" href="book_room.php?parking_id=85">
                                        <?php $parking = $rooms[84];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>85
                                    </a>
                                    <a class="P-S1" id="1084" href="book_room.php?parking_id=84">
                                        <?php $parking = $rooms[83];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>84
                                    </a>
                                    <a class="P-S1" id="1083" href="book_room.php?parking_id=83">
                                        <?php $parking = $rooms[82];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>83
                                    </a>
                                    <a class="P-S1" id="1082" href="book_room.php?parking_id=82">
                                        <?php $parking = $rooms[81];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>82
                                    </a>
                                    <a class="P-S1" id="1081" href="book_room.php?parking_id=81">
                                        <?php $parking = $rooms[80];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>81
                                    </a>
                                    <a class="P-S1" id="1080" href="book_room.php?parking_id=80">
                                        <?php $parking = $rooms[79];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>80
                                    </a>
                                    <a class="P-S1" id="1079" href="book_room.php?parking_id=79">
                                        <?php $parking = $rooms[78];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>79
                                    </a>
                                    <a class="P-S1" id="1078" href="book_room.php?parking_id=78">
                                        <?php $parking = $rooms[77];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>78
                                    </a>
                                    <a class="P-S1" id="1077" href="book_room.php?parking_id=77">
                                        <?php $parking = $rooms[76];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>77
                                    </a>
                                    <a class="P-S1" id="1076" href="book_room.php?parking_id=76">
                                        <?php $parking = $rooms[75];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>76
                                    </a>
                                    <a class="P-S1" id="1075" href="book_room.php?parking_id=75">
                                        <?php $parking = $rooms[74];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>75
                                    </a>
                                    <a class="P-S1" id="1074" href="book_room.php?parking_id=74">
                                        <?php $parking = $rooms[73];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>74
                                    </a>
                                    <a class="P-S1" id="1073" href="book_room.php?parking_id=73">
                                        <?php $parking = $rooms[72];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>73
                                    </a>
                                    <a class="P-S1" id="1072" href="book_room.php?parking_id=72">
                                        <?php $parking = $rooms[71];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>72
                                    </a>
                                    <a class="P-S1" id="1071" href="book_room.php?parking_id=71">
                                        <?php $parking = $rooms[70];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>71
                                    </a>
                                    <a class="P-S1" id="1070" href="book_room.php?parking_id=70">
                                        <?php $parking = $rooms[69];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>70
                                    </a>
                                    <a class="P-S1" id="1069" href="book_room.php?parking_id=69">
                                        <?php $parking = $rooms[68];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>69
                                    </a>
                                    <a class="P-S1" id="1068" href="book_room.php?parking_id=68">
                                        <?php $parking = $rooms[67];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>68
                                    </a>
                                    <a class="P-S1" id="1067" href="book_room.php?parking_id=67">
                                        <?php $parking = $rooms[66];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>67
                                    </a>
                                    <a class="P-S1" id="1066" href="book_room.php?parking_id=66">
                                        <?php $parking = $rooms[65];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>66
                                    </a>
                                    <a class="P-S1" id="1065" href="book_room.php?parking_id=65">
                                        <?php $parking = $rooms[64];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>65
                                    </a>
                                    <a class="P-S1" id="1064" href="book_room.php?parking_id=64">
                                        <?php $parking = $rooms[63];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>64
                                    </a>
                                    <a class="P-S1" id="1063" href="book_room.php?parking_id=63">
                                        <?php $parking = $rooms[62];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>63
                                    </a>
                                    <a class="P-S1" id="1062" href="book_room.php?parking_id=62">
                                        <?php $parking = $rooms[61];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>62
                                    </a>
                                    <a class="P-S1" id="1061" href="book_room.php?parking_id=61">
                                        <?php $parking = $rooms[60];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>61
                                    </a>
                                </div>
                                <div class="EBLOCK"></div>
                            </div>
                            <div class="B1">
                                <!--<div class="BB22-1s">
                        <button class="V-S1">59</button>
                        <button class="V-S1">58</button>
                        <button class="V-S1">57</button>
                    </div>-->
                            </div>

                            <div class="LB2">
                                <div class="LB2-1">
                                    <a class="V-S1" id="1021" href="book_room.php?parking_id=21">21
                                        <?php $parking = $rooms[20];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="V-S1" id="1022" href="book_room.php?parking_id=22">22
                                        <?php $parking = $rooms[21];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="V-S1" id="1023" href="book_room.php?parking_id=23">23
                                        <?php $parking = $rooms[22];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="V-S1" id="1024" href="book_room.php?parking_id=24">24
                                        <?php $parking = $rooms[23];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="V-S1" id="1025" href="book_room.php?parking_id=25">25
                                        <?php $parking = $rooms[24];
                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                    </a>
                                    <a class="V-S1" id="1026" href="book_room.php?parking_id=26">26<?php $parking = $rooms[25];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1027" href="book_room.php?parking_id=27">27<?php $parking = $rooms[26];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1028" href="book_room.php?parking_id=28">28<?php $parking = $rooms[27];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1029" href="book_room.php?parking_id=29">29<?php $parking = $rooms[28];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1030" href="book_room.php?parking_id=30">30<?php $parking = $rooms[29];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1031" href="book_room.php?parking_id=31">31<?php $parking = $rooms[30];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1032" href="book_room.php?parking_id=32">32<?php $parking = $rooms[31];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1033" href="book_room.php?parking_id=33">33<?php $parking = $rooms[32];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1034" href="book_room.php?parking_id=34">34<?php $parking = $rooms[33];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1035" href="book_room.php?parking_id=35">35<?php $parking = $rooms[34];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1036" href="book_room.php?parking_id=36">36<?php $parking = $rooms[35];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1037" href="book_room.php?parking_id=37">37<?php $parking = $rooms[36];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1038" href="book_room.php?parking_id=38">38<?php $parking = $rooms[37];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1039" href="book_room.php?parking_id=39">39<?php $parking = $rooms[38];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1040" href="book_room.php?parking_id=40">40<?php $parking = $rooms[39];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1041" href="book_room.php?parking_id=41">41<?php $parking = $rooms[40];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1042" href="book_room.php?parking_id=42">42<?php $parking = $rooms[41];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="1043" href="book_room.php?parking_id=43">43<?php $parking = $rooms[42];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                </div>
                            </div>
                            <div class="L2">
                                <div class="I1">
                                    <div class="PL2">
                                        <a class="P-S1" id="1086" href="book_room.php?parking_id=86">86<?php $parking = $rooms[85];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1087" href="book_room.php?parking_id=87">87<?php $parking = $rooms[86];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1088" href="book_room.php?parking_id=88">88<?php $parking = $rooms[87];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1089" href="book_room.php?parking_id=89">89<?php $parking = $rooms[88];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1090" href="book_room.php?parking_id=90">90<?php $parking = $rooms[89];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1091" href="book_room.php?parking_id=91">91<?php $parking = $rooms[90];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1092" href="book_room.php?parking_id=92">92<?php $parking = $rooms[91];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1093" href="book_room.php?parking_id=93">93<?php $parking = $rooms[92];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1094" href="book_room.php?parking_id=94">94<?php $parking = $rooms[93];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1095" href="book_room.php?parking_id=95">95<?php $parking = $rooms[94];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1096" href="book_room.php?parking_id=96">96<?php $parking = $rooms[95];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1097" href="book_room.php?parking_id=97">97<?php $parking = $rooms[96];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1098" href="book_room.php?parking_id=98">98<?php $parking = $rooms[97];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1099" href="book_room.php?parking_id=99">99<?php $parking = $rooms[98];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1100" href="book_room.php?parking_id=100">100<?php $parking = $rooms[99];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1101" href="book_room.php?parking_id=101">101<?php $parking = $rooms[100];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1102" href="book_room.php?parking_id=102">102<?php $parking = $rooms[101];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1103" href="book_room.php?parking_id=103">103<?php $parking = $rooms[102];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1104" href="book_room.php?parking_id=104">104<?php $parking = $rooms[103];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1105" href="book_room.php?parking_id=105">105<?php $parking = $rooms[104];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1106" href="book_room.php?parking_id=106">106<?php $parking = $rooms[105];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="1107" href="book_room.php?parking_id=107">107<?php $parking = $rooms[106];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>

                                    </div>
                                    <div class="WL2"></div>
                                    <div class="EBLOCK"></div>
                                    <div class="EBLOCK"></div>
                                    <div class="PL2-1">
                                        <a class="P-S1" id="1121" href="book_room.php?parking_id=121"><?php $parking = $rooms[120];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>121</a>
                                        <a class="P-S1" id="1120" href="book_room.php?parking_id=120"><?php $parking = $rooms[119];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>120</a>
                                        <a class="P-S1" id="1119" href="book_room.php?parking_id=119"><?php $parking = $rooms[118];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>119</a>
                                        <a class="P-S1" id="1118" href="book_room.php?parking_id=118"><?php $parking = $rooms[117];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>118</a>
                                        <a class="P-S1" id="1117" href="book_room.php?parking_id=117"><?php $parking = $rooms[116];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>117</a>
                                        <a class="P-S1" id="1116" href="book_room.php?parking_id=116"><?php $parking = $rooms[115];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>116</a>
                                        <a class="P-S1" id="1115" href="book_room.php?parking_id=115"><?php $parking = $rooms[114];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>115</a>
                                        <a class="P-S1" id="1114" href="book_room.php?parking_id=114"><?php $parking = $rooms[113];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>114</a>
                                        <a class="P-S1" id="1113" href="book_room.php?parking_id=113"><?php $parking = $rooms[112];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>113</a>
                                        <a class="P-S1" id="1112" href="book_room.php?parking_id=112"><?php $parking = $rooms[111];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>112</a>
                                        <a class="P-S1" id="1111" href="book_room.php?parking_id=111"><?php $parking = $rooms[110];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>111</a>
                                        <a class="P-S1" id="1110" href="book_room.php?parking_id=110"><?php $parking = $rooms[109];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>110</a>
                                        <a class="P-S1" id="1109" href="book_room.php?parking_id=109"><?php $parking = $rooms[108];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>109</a>
                                        <a class="P-S1" id="1108" href="book_room.php?parking_id=108"><?php $parking = $rooms[107];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>108</a>
                                    </div>
                                    <div class="EBLOCK"></div>
                                </div>
                                <div class="I2">
                                    <div class="IB1"></div>
                                    <div class="II1">
                                        <div class="PL3">
                                            <a class="P-S1" id="1130" href="book_room.php?parking_id=130">130
                                                <?php $parking = $rooms[129];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1129" href="book_room.php?parking_id=129">129
                                                <?php $parking = $rooms[128];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1128" href="book_room.php?parking_id=128">128
                                                <?php $parking = $rooms[127];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1127" href="book_room.php?parking_id=127">127
                                                <?php $parking = $rooms[126];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1126" href="book_room.php?parking_id=126">126
                                                <?php $parking = $rooms[125];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1125" href="book_room.php?parking_id=125">125
                                                <?php $parking = $rooms[124];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1124" href="book_room.php?parking_id=124">124
                                                <?php $parking = $rooms[123];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1123" href="book_room.php?parking_id=123">123
                                                <?php $parking = $rooms[122];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                            <a class="P-S1" id="1122" href="book_room.php?parking_id=122">122
                                                <?php $parking = $rooms[121];
                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            </a>
                                        </div>
                                        <div class="WL3"></div>
                                    </div>
                                    <div class="IB1"></div>
                                </div>
                            </div>
                            <div class="B2">
                                <div class="BB2-1">
                                    <div class="BB22-1">
                                        <a class="BV-S1" id="1060" href="book_room.php?parking_id=60">
                                            <?php $parking = $rooms[59];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>
                                            60</a>
                                        <a class="BV-S1" id="1059" href="book_room.php?parking_id=59">
                                            <?php $parking = $rooms[58];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>59
                                        </a>
                                        <a class="V-S1" id="1058" href="book_room.php?parking_id=58">
                                            <?php $parking = $rooms[57];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>58
                                        </a>
                                        <a class="V-S1" id="1057" href="book_room.php?parking_id=57">
                                            <?php $parking = $rooms[56];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>57
                                        </a>
                                        <a class="V-S1" id="1056" href="book_room.php?parking_id=56">
                                            <?php $parking = $rooms[55];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>56
                                        </a>
                                        <a class="V-S1" id="1055" href="book_room.php?parking_id=55">
                                            <?php $parking = $rooms[54];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>55
                                        </a>
                                        <a class="V-S1" id="1054" href="book_room.php?parking_id=54">
                                            <?php $parking = $rooms[53];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>54
                                        </a>
                                        <a class="V-S1" id="1053" href="book_room.php?parking_id=53">
                                            <?php $parking = $rooms[52];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>53
                                        </a>
                                    </div>
                                </div>
                                <div class="BB2-2">
                                    <div class="BB22-2">
                                        <a class="V-S1" id="1052" href="book_room.php?parking_id=52">
                                            <?php $parking = $rooms[51];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>52
                                        </a>
                                        <a class="V-S1" id="1051" href="book_room.php?parking_id=51">
                                            <?php $parking = $rooms[50];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>51
                                        </a>
                                        <a class="V-S1" id="1050" href="book_room.php?parking_id=50">
                                            <?php $parking = $rooms[49];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>50
                                        </a>
                                        <a class="V-S1" id="1049" href="book_room.php?parking_id=49">
                                            <?php $parking = $rooms[48];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>49
                                        </a>
                                        <a class="V-S1" id="1048" href="book_room.php?parking_id=48">
                                            <?php $parking = $rooms[47];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>48
                                        </a>
                                        <a class="V-S1" id="1047" href="book_room.php?parking_id=47">
                                            <?php $parking = $rooms[46];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>47
                                        </a>
                                        <a class="V-S1" id="1046" href="book_room.php?parking_id=46">
                                            <?php $parking = $rooms[45];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>46
                                        </a>
                                        <a class="V-S1" id="1045" href="book_room.php?parking_id=45">
                                            <?php $parking = $rooms[44];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>45
                                        </a>
                                        <a class="V-S1" id="1044" href="book_room.php?parking_id=44">
                                            <?php $parking = $rooms[43];
                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>44
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-step">
                <h2>1st Floor</h2><br>
                <div class="slots">
                    <div class="Card5">
                        <div class="A1">
                            <div class="B1"></div>
                            <div class="L1">
                                <div class="PL1">
                                    <a class="BP-S1" id="2028" href="book_room.php?parking_id=160">28<?php $parking = $rooms[157];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="2027" href="book_room.php?parking_id=159">27<?php $parking = $rooms[156];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="2026" href="book_room.php?parking_id=158">26<?php $parking = $rooms[155];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2025" href="book_room.php?parking_id=157">25<?php $parking = $rooms[154];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2024" href="book_room.php?parking_id=156">24<?php $parking = $rooms[153];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2023" href="book_room.php?parking_id=155">23<?php $parking = $rooms[152];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2022" href="book_room.php?parking_id=154">22<?php $parking = $rooms[151];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2021" href="book_room.php?parking_id=153">21<?php $parking = $rooms[150];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2020" href="book_room.php?parking_id=152">20<?php $parking = $rooms[149];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2019" href="book_room.php?parking_id=151">19<?php $parking = $rooms[148];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2018" href="book_room.php?parking_id=150">18<?php $parking = $rooms[147];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2017" href="book_room.php?parking_id=149">17<?php $parking = $rooms[146];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2016" href="book_room.php?parking_id=148">16<?php $parking = $rooms[145];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2015" href="book_room.php?parking_id=147">15<?php $parking = $rooms[144];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2014" href="book_room.php?parking_id=146">14<?php $parking = $rooms[143];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2013" href="book_room.php?parking_id=145">13<?php $parking = $rooms[142];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2012" href="book_room.php?parking_id=144">12<?php $parking = $rooms[141];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2011" href="book_room.php?parking_id=143">11<?php $parking = $rooms[140];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2010" href="book_room.php?parking_id=142">10<?php $parking = $rooms[139];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2009" href="book_room.php?parking_id=141">9<?php $parking = $rooms[138];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2008" href="book_room.php?parking_id=140">8<?php $parking = $rooms[137];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2007" href="book_room.php?parking_id=139">7<?php $parking = $rooms[136];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2006" href="book_room.php?parking_id=138">6<?php $parking = $rooms[135];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2005" href="book_room.php?parking_id=137">5<?php $parking = $rooms[134];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="2004" href="book_room.php?parking_id=136">4<?php $parking = $rooms[133];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="2003" href="book_room.php?parking_id=135">3<?php $parking = $rooms[132];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="2002" href="book_room.php?parking_id=134">2<?php $parking = $rooms[131];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="2001" href="book_room.php?parking_id=133">1<?php $parking = $rooms[130];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                </div>
                                <div class="WL1"></div>
                                <div class="EBLOCK"></div>
                                <div class="PL1-1">
                                    <a class="P-S1" id="2093" href="book_room.php?parking_id=225"><?php $parking = $rooms[222];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>93</a>
                                    <a class="P-S1" id="2092" href="book_room.php?parking_id=224"><?php $parking = $rooms[221];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>92</a>
                                    <a class="P-S1" id="2091" href="book_room.php?parking_id=223"><?php $parking = $rooms[220];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>91</a>
                                    <a class="P-S1" id="2090" href="book_room.php?parking_id=222"><?php $parking = $rooms[219];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>90</a>
                                    <a class="P-S1" id="2089" href="book_room.php?parking_id=221"><?php $parking = $rooms[218];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>89</a>
                                    <a class="P-S1" id="2088" href="book_room.php?parking_id=220"><?php $parking = $rooms[217];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>88</a>
                                    <a class="P-S1" id="2087" href="book_room.php?parking_id=219"><?php $parking = $rooms[216];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>87</a>
                                    <a class="P-S1" id="2086" href="book_room.php?parking_id=218"><?php $parking = $rooms[215];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>86</a>
                                    <a class="P-S1" id="2085" href="book_room.php?parking_id=217"><?php $parking = $rooms[214];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>85</a>
                                    <a class="P-S1" id="2084" href="book_room.php?parking_id=216"><?php $parking = $rooms[213];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>84</a>
                                    <a class="P-S1" id="2083" href="book_room.php?parking_id=215"><?php $parking = $rooms[212];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>83</a>
                                    <a class="P-S1" id="2082" href="book_room.php?parking_id=214"><?php $parking = $rooms[211];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>82</a>
                                    <a class="P-S1" id="2081" href="book_room.php?parking_id=213"><?php $parking = $rooms[210];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>81</a>
                                    <a class="P-S1" id="2080" href="book_room.php?parking_id=212"><?php $parking = $rooms[209];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>80</a>
                                    <a class="P-S1" id="2079" href="book_room.php?parking_id=211"><?php $parking = $rooms[208];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>79</a>
                                    <a class="P-S1" id="2078" href="book_room.php?parking_id=210"><?php $parking = $rooms[207];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>78</a>
                                    <a class="P-S1" id="2077" href="book_room.php?parking_id=209"><?php $parking = $rooms[206];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>77</a>
                                    <a class="P-S1" id="2076" href="book_room.php?parking_id=208"><?php $parking = $rooms[205];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>76</a>
                                    <a class="P-S1" id="2075" href="book_room.php?parking_id=207"><?php $parking = $rooms[204];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>75</a>
                                    <a class="P-S1" id="2074" href="book_room.php?parking_id=206"><?php $parking = $rooms[203];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>74</a>
                                    <a class="P-S1" id="2073" href="book_room.php?parking_id=205"><?php $parking = $rooms[202];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>73</a>
                                    <a class="P-S1" id="2072" href="book_room.php?parking_id=204"><?php $parking = $rooms[201];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>72</a>
                                    <a class="P-S1" id="2071" href="book_room.php?parking_id=203"><?php $parking = $rooms[200];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>71</a>
                                    <a class="P-S1" id="2070" href="book_room.php?parking_id=202"><?php $parking = $rooms[199];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>70</a>
                                    <a class="P-S1" id="2069" href="book_room.php?parking_id=201"><?php $parking = $rooms[198];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>69</a>
                                </div>
                                <div class="EBLOCK"></div>
                            </div>
                            <div class="B1"></div>
                            <div class="LB2">
                                <div class="LB2-1">
                                    <a class="V-S1" id="2029" href="book_room.php?parking_id=161">29<?php $parking = $rooms[158];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2030" href="book_room.php?parking_id=162">30<?php $parking = $rooms[159];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2031" href="book_room.php?parking_id=163">31<?php $parking = $rooms[160];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2032" href="book_room.php?parking_id=164">32<?php $parking = $rooms[161];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2033" href="book_room.php?parking_id=165">33<?php $parking = $rooms[162];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2034" href="book_room.php?parking_id=166">34<?php $parking = $rooms[163];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2035" href="book_room.php?parking_id=167">35<?php $parking = $rooms[164];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2036" href="book_room.php?parking_id=168">36<?php $parking = $rooms[165];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2037" href="book_room.php?parking_id=169">37<?php $parking = $rooms[166];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2038" href="book_room.php?parking_id=170">38<?php $parking = $rooms[167];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2039" href="book_room.php?parking_id=171">39<?php $parking = $rooms[168];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2040" href="book_room.php?parking_id=172">40<?php $parking = $rooms[169];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2041" href="book_room.php?parking_id=173">41<?php $parking = $rooms[170];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2042" href="book_room.php?parking_id=174">42<?php $parking = $rooms[171];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2043" href="book_room.php?parking_id=175">43<?php $parking = $rooms[172];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2044" href="book_room.php?parking_id=176">44<?php $parking = $rooms[173];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2045" href="book_room.php?parking_id=177">45<?php $parking = $rooms[174];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2046" href="book_room.php?parking_id=178">46<?php $parking = $rooms[175];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2047" href="book_room.php?parking_id=179">47<?php $parking = $rooms[176];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2048" href="book_room.php?parking_id=180">48<?php $parking = $rooms[177];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2049" href="book_room.php?parking_id=181">49<?php $parking = $rooms[178];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2050" href="book_room.php?parking_id=182">50<?php $parking = $rooms[179];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="2051" href="book_room.php?parking_id=183">51<?php $parking = $rooms[180];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                </div>
                            </div>
                            <div class="L2">
                                <div class="I1">
                                    <div class="PL2">
                                        <a class="P-S1" id="2094" href="book_room.php?parking_id=226">94<?php $parking = $rooms[223];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2095" href="book_room.php?parking_id=227">95<?php $parking = $rooms[224];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2096" href="book_room.php?parking_id=228">96<?php $parking = $rooms[225];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2097" href="book_room.php?parking_id=229">97<?php $parking = $rooms[226];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2098" href="book_room.php?parking_id=230">98<?php $parking = $rooms[227];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2099" href="book_room.php?parking_id=231">99<?php $parking = $rooms[228];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2100" href="book_room.php?parking_id=232">100<?php $parking = $rooms[229];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2101" href="book_room.php?parking_id=233">101<?php $parking = $rooms[230];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2102" href="book_room.php?parking_id=234">102<?php $parking = $rooms[231];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2103" href="book_room.php?parking_id=235">103<?php $parking = $rooms[232];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2104" href="book_room.php?parking_id=236">104<?php $parking = $rooms[233];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2105" href="book_room.php?parking_id=237">105<?php $parking = $rooms[234];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2106" href="book_room.php?parking_id=238">106<?php $parking = $rooms[235];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2107" href="book_room.php?parking_id=239">107<?php $parking = $rooms[236];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2108" href="book_room.php?parking_id=240">108<?php $parking = $rooms[237];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2109" href="book_room.php?parking_id=241">109<?php $parking = $rooms[238];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2110" href="book_room.php?parking_id=242">110<?php $parking = $rooms[239];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2111" href="book_room.php?parking_id=243">111<?php $parking = $rooms[240];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2112" href="book_room.php?parking_id=244">112<?php $parking = $rooms[241];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2113" href="book_room.php?parking_id=245">113<?php $parking = $rooms[242];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2114" href="book_room.php?parking_id=246">114<?php $parking = $rooms[243];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="2115" href="book_room.php?parking_id=247">115<?php $parking = $rooms[244];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    </div>
                                    <div class="WL2"></div>
                                    <div class="EBLOCK"></div>
                                    <div class="EBLOCK"></div>
                                    <div class="PL2-1">
                                        <a class="P-S1" id="2129" href="book_room.php?parking_id=261"><?php $parking = $rooms[258];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>129</a>
                                        <a class="P-S1" id="2128" href="book_room.php?parking_id=260"><?php $parking = $rooms[257];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>128</a>
                                        <a class="P-S1" id="2127" href="book_room.php?parking_id=259"><?php $parking = $rooms[256];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>127</a>
                                        <a class="P-S1" id="2126" href="book_room.php?parking_id=258"><?php $parking = $rooms[255];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>126</a>
                                        <a class="P-S1" id="2125" href="book_room.php?parking_id=257"><?php $parking = $rooms[254];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>125</a>
                                        <a class="P-S1" id="2124" href="book_room.php?parking_id=256"><?php $parking = $rooms[253];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>124</a>
                                        <a class="P-S1" id="2123" href="book_room.php?parking_id=255"><?php $parking = $rooms[252];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>123</a>
                                        <a class="P-S1" id="2122" href="book_room.php?parking_id=254"><?php $parking = $rooms[251];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>122</a>
                                        <a class="P-S1" id="2121" href="book_room.php?parking_id=253"><?php $parking = $rooms[250];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>121</a>
                                        <a class="P-S1" id="2120" href="book_room.php?parking_id=252"><?php $parking = $rooms[249];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>120</a>
                                        <a class="P-S1" id="2119" href="book_room.php?parking_id=251"><?php $parking = $rooms[248];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>119</a>
                                        <a class="P-S1" id="2118" href="book_room.php?parking_id=250"><?php $parking = $rooms[247];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>118</a>
                                        <a class="P-S1" id="2117" href="book_room.php?parking_id=249"><?php $parking = $rooms[246];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>117</a>
                                        <a class="P-S1" id="2116" href="book_room.php?parking_id=248"><?php $parking = $rooms[245];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>116</a>
                                    </div>
                                    <div class="EBLOCK"></div>
                                </div>
                                <div class="I2">
                                    <div class="IB1"></div>
                                    <div class="II1">
                                        <div class="PL3">
                                            <a class="P-S1" id="2130" href="book_room.php?parking_id=262">130<?php $parking = $rooms[259];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2131" href="book_room.php?parking_id=263">131<?php $parking = $rooms[260];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2132" href="book_room.php?parking_id=264">132<?php $parking = $rooms[261];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2133" href="book_room.php?parking_id=265">133<?php $parking = $rooms[262];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2134" href="book_room.php?parking_id=266">134<?php $parking = $rooms[263];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2135" href="book_room.php?parking_id=267">135<?php $parking = $rooms[264];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2136" href="book_room.php?parking_id=268">136<?php $parking = $rooms[265];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2137" href="book_room.php?parking_id=269">137<?php $parking = $rooms[266];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="2138" href="book_room.php?parking_id=270">138<?php $parking = $rooms[267];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        </div>
                                        <div class="WL3"></div>
                                    </div>
                                    <div class="IB1"></div>
                                </div>
                            </div>
                            <div class="B2">
                                <div class="BB2-1">
                                    <div class="BB22-1">
                                        <a class="BV-S1" id="2068" href="book_room.php?parking_id=200"><?php $parking = $rooms[197];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>68</a>
                                        <a class="BV-S1" id="2067" href="book_room.php?parking_id=199"><?php $parking = $rooms[196];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>67</a>
                                        <a class="V-S1" id="2066" href="book_room.php?parking_id=198"><?php $parking = $rooms[195];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>66</a>
                                        <a class="V-S1" id="2065" href="book_room.php?parking_id=197"><?php $parking = $rooms[194];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>65</a>
                                        <a class="V-S1" id="2064" href="book_room.php?parking_id=196"><?php $parking = $rooms[193];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>64</a>
                                        <a class="V-S1" id="2063" href="book_room.php?parking_id=195"><?php $parking = $rooms[192];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>63</a>
                                        <a class="V-S1" id="2062" href="book_room.php?parking_id=194"><?php $parking = $rooms[191];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>62</a>
                                        <a class="V-S1" id="2061" href="book_room.php?parking_id=193"><?php $parking = $rooms[190];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>61</a>
                                    </div>
                                </div>
                                <div class="BB2-2">
                                    <div class="BB22-2">
                                        <a class="V-S1" id="2060" href="book_room.php?parking_id=192"><?php $parking = $rooms[189];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>60</a>
                                        <a class="V-S1" id="2059" href="book_room.php?parking_id=191"><?php $parking = $rooms[188];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>59</a>
                                        <a class="V-S1" id="2058" href="book_room.php?parking_id=190"><?php $parking = $rooms[187];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>58</a>
                                        <a class="V-S1" id="2057" href="book_room.php?parking_id=189"><?php $parking = $rooms[186];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>57</a>
                                        <a class="V-S1" id="2056" href="book_room.php?parking_id=188"><?php $parking = $rooms[185];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>56</a>
                                        <a class="V-S1" id="2055" href="book_room.php?parking_id=187"><?php $parking = $rooms[184];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>55</a>
                                        <a class="V-S1" id="2054" href="book_room.php?parking_id=186"><?php $parking = $rooms[183];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>54</a>
                                        <a class="V-S1" id="2053" href="book_room.php?parking_id=185"><?php $parking = $rooms[182];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>53</a>
                                        <a class="V-S1" id="2052" href="book_room.php?parking_id=184"><?php $parking = $rooms[181];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>52</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-step">
                <h2>2nd Floor</h2><br>
                <div class="slots">
                    <div class="Card5">
                        <div class="A1">
                            <div class="B1"></div>
                            <div class="L1">
                                <div class="PL1">
                                    <a class="BP-S1" id="3028" href="book_room.php?parking_id=300">28<?php $parking = $rooms[295];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="3027" href="book_room.php?parking_id=299">27<?php $parking = $rooms[294];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="3026" href="book_room.php?parking_id=298">26<?php $parking = $rooms[293];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3025" href="book_room.php?parking_id=297">25<?php $parking = $rooms[292];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3024" href="book_room.php?parking_id=296">24<?php $parking = $rooms[291];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3023" href="book_room.php?parking_id=295">23<?php $parking = $rooms[290];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3022" href="book_room.php?parking_id=294">22<?php $parking = $rooms[289];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3021" href="book_room.php?parking_id=293">21<?php $parking = $rooms[288];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3020" href="book_room.php?parking_id=292">20<?php $parking = $rooms[287];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3019" href="book_room.php?parking_id=291">19<?php $parking = $rooms[286];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3018" href="book_room.php?parking_id=290">18<?php $parking = $rooms[285];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3017" href="book_room.php?parking_id=289">17<?php $parking = $rooms[284];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3016" href="book_room.php?parking_id=288">16<?php $parking = $rooms[283];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3015" href="book_room.php?parking_id=287">15<?php $parking = $rooms[282];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3014" href="book_room.php?parking_id=286">14<?php $parking = $rooms[281];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3013" href="book_room.php?parking_id=285">13<?php $parking = $rooms[280];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3012" href="book_room.php?parking_id=284">12<?php $parking = $rooms[279];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3011" href="book_room.php?parking_id=283">11<?php $parking = $rooms[278];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3010" href="book_room.php?parking_id=282">10<?php $parking = $rooms[277];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3009" href="book_room.php?parking_id=281">9<?php $parking = $rooms[276];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3008" href="book_room.php?parking_id=280">8<?php $parking = $rooms[275];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3007" href="book_room.php?parking_id=279">7<?php $parking = $rooms[274];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3006" href="book_room.php?parking_id=278">6<?php $parking = $rooms[273];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3005" href="book_room.php?parking_id=277">5<?php $parking = $rooms[272];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="P-S1" id="3004" href="book_room.php?parking_id=276">4<?php $parking = $rooms[271];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="3003" href="book_room.php?parking_id=275">3<?php $parking = $rooms[270];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="3002" href="book_room.php?parking_id=274">2<?php $parking = $rooms[269];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="BP-S1" id="3001" href="book_room.php?parking_id=273">1<?php $parking = $rooms[268];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                </div>
                                <div class="WL1"></div>
                                <div class="EBLOCK"></div>
                                <div class="PL1-1">
                                    <a class="P-S1" id="3093" href="book_room.php?parking_id=365"><?php $parking = $rooms[360];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>93</a>
                                    <a class="P-S1" id="3092" href="book_room.php?parking_id=364"><?php $parking = $rooms[359];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>92</a>
                                    <a class="P-S1" id="3091" href="book_room.php?parking_id=363"><?php $parking = $rooms[358];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>91</a>
                                    <a class="P-S1" id="3090" href="book_room.php?parking_id=362"><?php $parking = $rooms[357];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>90</a>
                                    <a class="P-S1" id="3089" href="book_room.php?parking_id=361"><?php $parking = $rooms[356];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>89</a>
                                    <a class="P-S1" id="3088" href="book_room.php?parking_id=360"><?php $parking = $rooms[355];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>88</a>
                                    <a class="P-S1" id="3087" href="book_room.php?parking_id=359"><?php $parking = $rooms[354];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>87</a>
                                    <a class="P-S1" id="3086" href="book_room.php?parking_id=358"><?php $parking = $rooms[353];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>86</a>
                                    <a class="P-S1" id="3085" href="book_room.php?parking_id=357"><?php $parking = $rooms[352];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>85</a>
                                    <a class="P-S1" id="3084" href="book_room.php?parking_id=356"><?php $parking = $rooms[351];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>84</a>
                                    <a class="P-S1" id="3083" href="book_room.php?parking_id=355"><?php $parking = $rooms[350];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>83</a>
                                    <a class="P-S1" id="3082" href="book_room.php?parking_id=354"><?php $parking = $rooms[349];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>82</a>
                                    <a class="P-S1" id="3081" href="book_room.php?parking_id=353"><?php $parking = $rooms[348];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>81</a>
                                    <a class="P-S1" id="3080" href="book_room.php?parking_id=352"><?php $parking = $rooms[347];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>80</a>
                                    <a class="P-S1" id="3079" href="book_room.php?parking_id=351"><?php $parking = $rooms[346];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>79</a>
                                    <a class="P-S1" id="3078" href="book_room.php?parking_id=350"><?php $parking = $rooms[345];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>78</a>
                                    <a class="P-S1" id="3077" href="book_room.php?parking_id=349"><?php $parking = $rooms[344];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>77</a>
                                    <a class="P-S1" id="3076" href="book_room.php?parking_id=348"><?php $parking = $rooms[343];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>76</a>
                                    <a class="P-S1" id="3075" href="book_room.php?parking_id=347"><?php $parking = $rooms[342];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>75</a>
                                    <a class="P-S1" id="3074" href="book_room.php?parking_id=346"><?php $parking = $rooms[341];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>74</a>
                                    <a class="P-S1" id="3073" href="book_room.php?parking_id=345"><?php $parking = $rooms[340];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>73</a>
                                    <a class="P-S1" id="3072" href="book_room.php?parking_id=344"><?php $parking = $rooms[339];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>72</a>
                                    <a class="P-S1" id="3071" href="book_room.php?parking_id=343"><?php $parking = $rooms[338];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>71</a>
                                    <a class="P-S1" id="3070" href="book_room.php?parking_id=342"><?php $parking = $rooms[337];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>70</a>
                                    <a class="P-S1" id="3069" href="book_room.php?parking_id=341"><?php $parking = $rooms[336];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>69</a>
                                </div>
                                <div class="EBLOCK"></div>
                            </div>
                            <div class="B1"></div>
                            <div class="LB2">
                                <div class="LB2-1">
                                    <a class="V-S1" id="3029" href="book_room.php?parking_id=301">29<?php $parking = $rooms[296];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3030" href="book_room.php?parking_id=302">30<?php $parking = $rooms[297];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3031" href="book_room.php?parking_id=303">31<?php $parking = $rooms[298];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3032" href="book_room.php?parking_id=304">32<?php $parking = $rooms[299];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3033" href="book_room.php?parking_id=305">33<?php $parking = $rooms[300];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3034" href="book_room.php?parking_id=306">34<?php $parking = $rooms[301];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3035" href="book_room.php?parking_id=307">35<?php $parking = $rooms[302];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3036" href="book_room.php?parking_id=308">36<?php $parking = $rooms[303];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3037" href="book_room.php?parking_id=309">37<?php $parking = $rooms[304];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3038" href="book_room.php?parking_id=310">38<?php $parking = $rooms[305];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3039" href="book_room.php?parking_id=311">39<?php $parking = $rooms[306];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3040" href="book_room.php?parking_id=312">40<?php $parking = $rooms[307];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3041" href="book_room.php?parking_id=313">41<?php $parking = $rooms[308];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3042" href="book_room.php?parking_id=314">42<?php $parking = $rooms[309];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3043" href="book_room.php?parking_id=315">43<?php $parking = $rooms[310];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3044" href="book_room.php?parking_id=316">44<?php $parking = $rooms[311];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3045" href="book_room.php?parking_id=317">45<?php $parking = $rooms[312];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3046" href="book_room.php?parking_id=318">46<?php $parking = $rooms[313];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3047" href="book_room.php?parking_id=319">47<?php $parking = $rooms[314];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3048" href="book_room.php?parking_id=320">48<?php $parking = $rooms[315];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3049" href="book_room.php?parking_id=321">49<?php $parking = $rooms[316];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3050" href="book_room.php?parking_id=322">50<?php $parking = $rooms[317];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    <a class="V-S1" id="3051" href="book_room.php?parking_id=323">51<?php $parking = $rooms[318];
                                                                                                    echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                </div>
                            </div>
                            <div class="L2">
                                <div class="I1">
                                    <div class="PL2">
                                        <a class="P-S1" id="3094" href="book_room.php?parking_id=366">94<?php $parking = $rooms[361];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3095" href="book_room.php?parking_id=367">95<?php $parking = $rooms[362];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3096" href="book_room.php?parking_id=368">96<?php $parking = $rooms[363];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3097" href="book_room.php?parking_id=369">97<?php $parking = $rooms[364];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3098" href="book_room.php?parking_id=370">98<?php $parking = $rooms[365];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3099" href="book_room.php?parking_id=371">99<?php $parking = $rooms[366];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3100" href="book_room.php?parking_id=372">100<?php $parking = $rooms[367];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3101" href="book_room.php?parking_id=373">101<?php $parking = $rooms[368];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3102" href="book_room.php?parking_id=374">102<?php $parking = $rooms[369];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3103" href="book_room.php?parking_id=375">103<?php $parking = $rooms[370];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3104" href="book_room.php?parking_id=376">104<?php $parking = $rooms[371];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3105" href="book_room.php?parking_id=377">105<?php $parking = $rooms[372];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3106" href="book_room.php?parking_id=378">106<?php $parking = $rooms[373];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3107" href="book_room.php?parking_id=379">107<?php $parking = $rooms[374];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3108" href="book_room.php?parking_id=380">108<?php $parking = $rooms[375];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3109" href="book_room.php?parking_id=381">109<?php $parking = $rooms[376];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3110" href="book_room.php?parking_id=382">110<?php $parking = $rooms[377];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3111" href="book_room.php?parking_id=383">111<?php $parking = $rooms[378];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3112" href="book_room.php?parking_id=384">112<?php $parking = $rooms[379];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3113" href="book_room.php?parking_id=385">113<?php $parking = $rooms[380];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3114" href="book_room.php?parking_id=386">114<?php $parking = $rooms[381];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        <a class="P-S1" id="3115" href="book_room.php?parking_id=387">115<?php $parking = $rooms[382];
                                                                                                            echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                    </div>
                                    <div class="WL2"></div>
                                    <div class="EBLOCK"></div>
                                    <div class="EBLOCK"></div>
                                    <div class="PL2-1">
                                        <a class="P-S1" id="3129" href="book_room.php?parking_id=401"><?php $parking = $rooms[396];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>129</a>
                                        <a class="P-S1" id="3128" href="book_room.php?parking_id=400"><?php $parking = $rooms[395];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>128</a>
                                        <a class="P-S1" id="3127" href="book_room.php?parking_id=399"><?php $parking = $rooms[394];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>127</a>
                                        <a class="P-S1" id="3126" href="book_room.php?parking_id=398"><?php $parking = $rooms[393];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>126</a>
                                        <a class="P-S1" id="3125" href="book_room.php?parking_id=397"><?php $parking = $rooms[392];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>125</a>
                                        <a class="P-S1" id="3124" href="book_room.php?parking_id=396"><?php $parking = $rooms[391];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>124</a>
                                        <a class="P-S1" id="3123" href="book_room.php?parking_id=395"><?php $parking = $rooms[390];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>123</a>
                                        <a class="P-S1" id="3122" href="book_room.php?parking_id=394"><?php $parking = $rooms[389];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>122</a>
                                        <a class="P-S1" id="3121" href="book_room.php?parking_id=393"><?php $parking = $rooms[388];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>121</a>
                                        <a class="P-S1" id="3120" href="book_room.php?parking_id=392"><?php $parking = $rooms[387];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>120</a>
                                        <a class="P-S1" id="3119" href="book_room.php?parking_id=391"><?php $parking = $rooms[386];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>119</a>
                                        <a class="P-S1" id="3118" href="book_room.php?parking_id=390"><?php $parking = $rooms[385];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>118</a>
                                        <a class="P-S1" id="3117" href="book_room.php?parking_id=389"><?php $parking = $rooms[384];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>117</a>
                                        <a class="P-S1" id="3116" href="book_room.php?parking_id=388"><?php $parking = $rooms[383];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>116</a>
                                    </div>
                                    <div class="EBLOCK"></div>
                                </div>
                                <div class="I2">
                                    <div class="IB1"></div>
                                    <div class="II1">
                                        <div class="PL3">
                                            <a class="P-S1" id="3130" href="book_room.php?parking_id=402">130<?php $parking = $rooms[397];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3131" href="book_room.php?parking_id=403">131<?php $parking = $rooms[398];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3132" href="book_room.php?parking_id=404">132<?php $parking = $rooms[399];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3133" href="book_room.php?parking_id=405">133<?php $parking = $rooms[400];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3134" href="book_room.php?parking_id=406">134<?php $parking = $rooms[401];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3135" href="book_room.php?parking_id=407">135<?php $parking = $rooms[402];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3136" href="book_room.php?parking_id=408">136<?php $parking = $rooms[403];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3137" href="book_room.php?parking_id=409">137<?php $parking = $rooms[404];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                            <a class="P-S1" id="3138" href="book_room.php?parking_id=410">138<?php $parking = $rooms[405];
                                                                                                                echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?></a>
                                        </div>
                                        <div class="WL3"></div>
                                    </div>
                                    <div class="IB1"></div>
                                </div>
                            </div>
                            <div class="B2">
                                <div class="BB2-1">
                                    <div class="BB22-1">
                                        <a class="BV-S1" id="3068" href="book_room.php?parking_id=340"><?php $parking = $rooms[335];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>68</a>
                                        <a class="BV-S1" id="3067" href="book_room.php?parking_id=339"><?php $parking = $rooms[334];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>67</a>
                                        <a class="V-S1" id="3066" href="book_room.php?parking_id=338"><?php $parking = $rooms[333];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>66</a>
                                        <a class="V-S1" id="3065" href="book_room.php?parking_id=337"><?php $parking = $rooms[332];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>65</a>
                                        <a class="V-S1" id="3064" href="book_room.php?parking_id=336"><?php $parking = $rooms[331];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>64</a>
                                        <a class="V-S1" id="3063" href="book_room.php?parking_id=335"><?php $parking = $rooms[330];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>63</a>
                                        <a class="V-S1" id="3062" href="book_room.php?parking_id=334"><?php $parking = $rooms[329];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>62</a>
                                        <a class="V-S1" id="3061" href="book_room.php?parking_id=333"><?php $parking = $rooms[328];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>61</a>
                                    </div>
                                </div>
                                <div class="BB2-2">
                                    <div class="BB22-2">
                                        <a class="V-S1" id="3060" href="book_room.php?parking_id=332"><?php $parking = $rooms[327];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>60</a>
                                        <a class="V-S1" id="3059" href="book_room.php?parking_id=331"><?php $parking = $rooms[326];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>59</a>
                                        <a class="V-S1" id="3058" href="book_room.php?parking_id=330"><?php $parking = $rooms[325];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>58</a>
                                        <a class="V-S1" id="3057" href="book_room.php?parking_id=329"><?php $parking = $rooms[324];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>57</a>
                                        <a class="V-S1" id="3056" href="book_room.php?parking_id=328"><?php $parking = $rooms[323];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>56</a>
                                        <a class="V-S1" id="3055" href="book_room.php?parking_id=327"><?php $parking = $rooms[322];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>55</a>
                                        <a class="V-S1" id="3054" href="book_room.php?parking_id=326"><?php $parking = $rooms[321];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>54</a>
                                        <a class="V-S1" id="3053" href="book_room.php?parking_id=325"><?php $parking = $rooms[320];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>53</a>
                                        <a class="V-S1" id="3052" href="book_room.php?parking_id=324"><?php $parking = $rooms[319];
                                                                                                        echo $parking['status'] === 'available' ? '<span class="badgeg"></span>' : '<span class="badger"></span>'; ?>52</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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