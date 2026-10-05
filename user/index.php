<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";
include 'assets/header.php';

$userId = $_SESSION["user_id"];

// Get total analyses
$stmt = $pdo->prepare("SELECT COUNT(*) FROM scan_results WHERE image_id IN (SELECT id FROM images WHERE user_id = ?)");
$stmt->execute([$userId]);
$totalAnalyses = $stmt->fetchColumn();

// Get total sensitive analyses
$stmt = $pdo->prepare("SELECT COUNT(*) FROM scan_results WHERE status = 'sensitive' AND image_id IN (SELECT id FROM images WHERE user_id = ?)");
$stmt->execute([$userId]);
$totalSensitive = $stmt->fetchColumn();

// Get total safe analyses
$stmt = $pdo->prepare("SELECT COUNT(*) FROM scan_results WHERE status = 'safe' AND image_id IN (SELECT id FROM images WHERE user_id = ?)");
$stmt->execute([$userId]);
$totalSafe = $stmt->fetchColumn();

require '../assets/sidemenu.php'; // Include the sidebar
?>

<main class="dashboard">
<h1 class="text-3xl font-bold text-primary mb-6">Dashboard Overview</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
