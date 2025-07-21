<?php
include('includes/db.php');
include('includes/functions.php');
startSession();

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        redirectWithMessage('main.php', 'Login successful!');
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
    <title>KFU Parking System: Login/Register</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
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
            <img height="50px" width="50px" src="./logo.png" alt="">
            <div class="logo">
                <h1><strong>KFU Parking System</strong></h1>
            </div>
            <a href="Index.php" class="login-btn" id="auth-btn">Starting Page</a>
        </div>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <main>
        <span class="error-message"></span>
        <div class="login-form-container">
            <div id="multi-step-form" class="login-form">
                <div class="form-step form-step-active" id="login-step">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="login_user.php">
                        <h2><strong>User Login</strong></h2>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nextBtns = document.querySelectorAll('.next-btn');
            const prevBtns = document.querySelectorAll('.prev-btn');
            const formSteps = document.querySelectorAll('.form-step');
            const loginForm = document.querySelector('.login-form');
            const errormessage = document.querySelector('.error-message');
            let currentStep = 0;

            const updateFormSteps = () => {
                formSteps.forEach((step, index) => {
                    step.classList.toggle('form-step-active', index === currentStep);
                });

                const activeStep = formSteps[currentStep];
                const activeStepHeight = activeStep.scrollHeight + 160;
                loginForm.style.minHeight = `${activeStepHeight}px`;
            };

            const isStepValid = () => {
                const inputs = formSteps[currentStep].querySelectorAll('input[required]');
                let allFilled = true;
                inputs.forEach(input => {
                    if (!input.value) {
                        input.classList.add('input-error');
                        allFilled = false;
                    } else {
                        input.classList.remove('input-error');
                    }
                });
                return allFilled;
            };
            updateFormSteps();

            nextBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (isStepValid() && currentStep < formSteps.length - 1) {
                        currentStep++;
                        updateFormSteps();
                    } else {

                    }
                });
            });

            prevBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        updateFormSteps();
                    }
                });
            });

            window.goToSignUp = () => {
                currentStep = 1;
                updateFormSteps();
            };
        });
    </script>
</body>
</html>