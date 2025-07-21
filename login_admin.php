<?php
include('includes/db.php');
include('includes/functions.php');
startSession();

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        redirectWithMessage('dashboard_admin.php', 'Login successful!');
    } else {
        $error_message = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking System: Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./logo.png">
    <script src="script.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .action-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 40px;
            padding: 10px 20px;
            background-color: #3478BF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .action-btn:hover {
            background: #1C4169;
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
            <a href="Index.php" class="login-btn">Staring Page</a>
        </div>
    </header>
    <main>
        <div class="login-form-container">
            <div id="multi-step-form" class="login-form">
                <div class="form-step form-step-active" id="login-step">
                    <h2 class="text-center"><strong>Admin Login</strong></h2>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="login_admin.php">
                        <span id="login-error" style="color: red;"></span>
                        <div class="input-box">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                        </div>
                        <div class="input-box">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="action-btn"><strong>Login</strong><i class="menu-icon fa-solid fa-right-from-bracket"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Parking System. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>