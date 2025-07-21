<?php
// Function to verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Function to start session securely
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Function to redirect with message
function redirectWithMessage($url, $message) {
    $_SESSION['message'] = $message;
    header("Location: $url");
    exit;
}

// Function to display messages
function displayMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="message">'.$_SESSION['message'].'</div>';
        unset($_SESSION['message']);
    }
}
?>
