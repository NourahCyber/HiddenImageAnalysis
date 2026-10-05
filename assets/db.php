<?php
// Database Credentials
$host = "localhost"; // Change if using a remote database
$dbname = "hidden_insight";
$username = "root"; // Change if using a different MySQL user
$password = ""; // Change if your database has a password

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if connection fails
    die("Database connection failed: " . $e->getMessage());
}
?>
