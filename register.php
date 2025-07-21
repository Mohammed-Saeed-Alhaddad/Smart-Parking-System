<?php
session_start();

$conn = new mysqli("localhost", "root", "", "p_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $email = $_POST['eml'];
    $phone = $_POST['tel'];
    $building = $_POST['building_num'];
    $error_message = "";

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match. Please try again.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Username already exists.";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone, building) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                die("SQL Error: " . $conn->error);
            }
            $stmt->bind_param("sssssss", $username, $hashed_password, $first_name, $last_name, $email, $phone, $building);

            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                header("Location: main.php");
                exit;
            } else {
                $error_message = "An error occurred during registration. Please try again.";
            }
        }
        $stmt->close();
    }
}
$conn->close();
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
        .login-form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 800px;
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

        .form-step {
            position: absolute;
            top: 80px;
            left: 30px;
            width: calc(100% - 60px);
            transition: opacity 0.5s ease;
        }

        .form-step-active {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }

        .form-step:not(.form-step-active) {
            opacity: 0;
            pointer-events: none;
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
            <img height="50px" width="50px" src="./logo.png" alt="">
            <div class="logo">
                <h1>KFU Parking System</h1>
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
                    <form method="POST" action="register.php">
                        <h2><strong>Register</strong></h2>
                        <div class="input-box">
                            <label for="first-name">First Name</label>
                            <input type="text" name="first-name" required placeholder="First Name">
                        </div>
                        <div class="input-box">
                            <label for="last-name">Last Name</label>
                            <input type="text" name="last-name" required placeholder="Last Name">
                        </div>
                        <div class="input-box">
                            <label for="tel">Phone Number</label>
                            <input type="tel" name="tel" required placeholder="Phone Number">
                        </div>
                        <div class="input-box">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                        </div>
                        <div class="input-box">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="eml" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="input-box">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <div class="input-box">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                        </div>
                        <div class="input-box">
                            <label for="building_num">Choice which parking building you park in:</label>
                            <select class="action-btn" name="building_num" required>
                                <option value="17">Pakring Building 17</option>
                                <option value="4">Pakring Building 4</option>
                            </select>
                        </div>
                        <button type="submit" class="action-btn"><strong>Register<i class="menu-icon1 fa-solid fa-user-plus"></i></strong></button>
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