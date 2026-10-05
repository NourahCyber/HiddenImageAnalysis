<?php
require 'assets/db.php'; // Include database connection
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate required fields
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION["error"] = "All fields are required.";
        header("Location: signup.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION["error"] = "Passwords do not match.";
        header("Location: signup.php");
        exit();
    }

    // Check if email is already registered
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION["error"] = "This email is already registered.";
        header("Location: signup.php");
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$name, $email, $hashed_password])) {
        $_SESSION["success"] = "Registration successful! You can now log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION["error"] = "An error occurred. Please try again.";
        header("Location: signup.php");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request.";
    header("Location: signup.php");
    exit();
}
?>
