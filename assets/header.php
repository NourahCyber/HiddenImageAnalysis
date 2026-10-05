<?php
require 'db.php';
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION["user_id"]);
$userId = $_SESSION["user_id"] ?? null;
$userName = $_SESSION["user_name"] ?? "Guest";
$userType = $_SESSION["user_type"] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="bg-background text-text-color">
    <header class="shadow-md py-4">
        <nav class="container mx-auto px-4 flex justify-between items-center">
            <!-- Left Side Navigation -->
            <div class="flex space-x-6">
                <a href="index.php" class="text-2xl font-bold text-primary">Hidden Insight</a>
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="text-dark-gray hover:text-primary transition duration-300">Home</a></li>
                    <li><a href="about.php" class="text-dark-gray hover:text-primary transition duration-300">About</a></li>
                    <li><a href="contact.php" class="text-dark-gray hover:text-primary transition duration-300">Contact</a></li>
                </ul>
            </div>

            <!-- Right Side User Section -->
            <ul class="flex space-x-4 ml-auto">
                <?php if ($isLoggedIn): ?>
                    <?php if ($userType === 'admin'): ?>
                        <li><a href="admin/index.php" class="text-primary font-bold hover:text-hover-primary transition duration-300">Admin Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="user/index.php" class="text-primary font-bold hover:text-hover-primary transition duration-300">Dashboard</a></li>
                    <?php endif; ?>

                    <li class="font-bold text-red-500 hover:text-red-700">Welcome, <?php echo htmlspecialchars($userName); ?>!</li>
                    <li><a href="logout.php" class="text-red-500 font-semibold hover:text-red-700 transition duration-300">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-hover-primary transition duration-300">Login</a></li>
                    <li><a href="signup.php" class="bg-secondary text-white px-4 py-2 rounded-md hover:bg-dark-gray transition duration-300">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>
