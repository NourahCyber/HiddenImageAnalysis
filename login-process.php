<?php
session_start();
require 'assets/db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Validate required fields
    if (empty($email) || empty($password)) {
        $_SESSION["error"] = "Email and password are required.";
        header("Location: login.php");
        exit();
    }

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT id, name, email, password, user_type FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        // Store user info in session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_type"] = $user["user_type"];

        // Redirect based on user type
        if ($user["user_type"] === "admin") {
            header("Location: admin/index.php");
        } else {
            header("Location: user/index.php");
        }
        exit();
    } else {
        $_SESSION["error"] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION["error"] = "Unauthorized request.";
    header("Location: login.php");
    exit();
}
?>
