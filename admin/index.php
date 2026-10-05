<?php
$pageTitle = "Admin Dashboard - Hidden Insight";
include 'assets/header.php';

$userId = $_SESSION["user_id"];

// Get total analyses for all users
$stmt = $pdo->prepare("SELECT COUNT(*) FROM scan_results");
$stmt->execute();
$totalAnalyses = $stmt->fetchColumn();

// Get total sensitive analyses for all users
$stmt = $pdo->prepare("SELECT COUNT(*) FROM scan_results WHERE status = 'sensitive'");
$stmt->execute();
$totalSensitive = $stmt->fetchColumn();

// Get total safe analyses for all users
$stmt = $pdo->prepare("SELECT COUNT(*) FROM scan_results WHERE status = 'safe'");
$stmt->execute();
$totalSafe = $stmt->fetchColumn();

// Get total number of registered users
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
$stmt->execute();
$totalUsers = $stmt->fetchColumn();

require '../assets/sidemenu.php'; // Include the sidebar
?>

<main class="dashboard min-h-screen ml-120 px-4 py-12"> 
    <h1 class="text-3xl font-bold text-primary mb-6 text-center">Admin Dashboard Overview</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-blue-100 p-6 rounded-lg shadow-lg text-center">
            <h2 class="text-xl font-semibold text-blue-700">Total Users</h2>
            <p class="text-4xl font-bold text-blue-500 mt-2"><?php echo $totalUsers; ?></p>
        </div>

        <!-- Total Analyses -->
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <h2 class="text-xl font-semibold text-gray-700">Total Analyses</h2>
            <p class="text-4xl font-bold text-primary mt-2"><?php echo $totalAnalyses; ?></p>
        </div>

        <!-- Sensitive Analyses -->
        <div class="bg-red-100 p-6 rounded-lg shadow-lg text-center">
            <h2 class="text-xl font-semibold text-red-700">Sensitive Analyses</h2>
            <p class="text-4xl font-bold text-red-500 mt-2"><?php echo $totalSensitive; ?></p>
        </div>

        <!-- Safe Analyses -->
        <div class="bg-green-100 p-6 rounded-lg shadow-lg text-center">
            <h2 class="text-xl font-semibold text-green-700">Safe Analyses</h2>
            <p class="text-4xl font-bold text-green-500 mt-2"><?php echo $totalSafe; ?></p>
        </div>
    </div>
</main>

<?php include 'assets/footer.php'; ?>
