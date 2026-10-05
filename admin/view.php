<?php
$pageTitle = "Analysis Result - Image Privacy Scanner";
include 'assets/header.php';
require '../assets/db.php'; // Include database connection

// Check if an ID is provided in the URL
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    $_SESSION["error"] = "Invalid request.";
    header("Location: analysis-history.php");
    exit();
}

$analysisId = $_GET["id"];

// Fetch analysis result from the database
$stmt = $pdo->prepare("
    SELECT sr.*, i.image_path 
    FROM scan_results sr 
    JOIN images i ON sr.image_id = i.id 
    WHERE sr.id = ?
");
$stmt->execute([$analysisId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// If no record found, redirect with an error message
if (!$result) {
    $_SESSION["error"] = "Analysis result not found.";
    header("Location: analysis-history.php");
    exit();
}

// Decode JSON fields
$detectedData = json_decode($result["detected_data"], true);
$detectedText = $detectedData["text"] ?? "No text detected";
$labels = json_decode($result["labels"], true) ?? [];
$metadata = json_decode($result["metadata"], true) ?? [
    "Camera" => "Unknown",
    "Timestamp" => "Unknown",
    "Software" => "Unknown",
    "GPS" => "Not Available"
];

// Fetch Hidden Message
$hiddenMessage = !empty($result["hidden_message"]) ? $result["hidden_message"] : "No hidden message found";
?>

<!-- Sidebar -->
<?php require '../assets/sidemenu.php'; ?>

<main class="dashboard">
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-center text-primary mb-6">Analysis Results</h1>

        <!-- Display Image -->
        <div class="text-center mb-6">
                <img src="<?php echo htmlspecialchars($result["image_path"]); ?>" alt="Analyzed Image" class="w-full max-w-md mx-auto rounded-md shadow-md">
            </div>
            
        <div class="max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
            <h2 class="text-xl font-semibold mb-4 text-primary">Metadata Information</h2>

            

            <p class="text-lg text-gray-700 mb-4">
                <strong>Camera:</strong> <?php echo htmlspecialchars($metadata["Camera"]); ?>
            </p>

            <p class="text-lg text-gray-700 mb-4">
                <strong>Timestamp:</strong> <?php echo htmlspecialchars($metadata["Timestamp"]); ?>
            </p>

            <p class="text-lg text-gray-700 mb-4">
                <strong>Software:</strong> <?php echo htmlspecialchars($metadata["Software"]); ?>
            </p>

            <p class="text-lg text-red-500 font-bold mb-4">
                <strong>GPS Coordinates:</strong> 
                <?php echo ($metadata["GPS"] !== "Not Available") ? htmlspecialchars($metadata["GPS"]) : "No GPS Data"; ?>
            </p>
        </div>

        <!-- Detected Text and Objects -->
        <div class="m-10 max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
            <h2 class="text-xl font-semibold mb-4 text-primary">Detected Text and Objects:</h2>

            <p class="text-lg text-gray-700 mb-4">
                <strong>Detected Text:</strong> 
                <?php echo !empty($detectedText) ? nl2br(htmlspecialchars($detectedText)) : "<span class='text-gray-500'>No Text Detected.</span>"; ?>
            </p>

            <p class="text-lg text-gray-700 mb-4">
                <strong>Detected Objects:</strong> 
                <?php echo !empty($labels) ? implode(", ", $labels) : "<span class='text-gray-500'>No Objects Detected.</span>"; ?>
            </p>
        </div>

        <!-- Hidden Message Section -->
        <div class="m-10 max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
            <h2 class="text-xl font-semibold mb-4 text-primary">Hidden Message:</h2>
            <p class="text-lg text-gray-700 mb-4">
                <?php echo nl2br(htmlspecialchars($hiddenMessage)); ?>
            </p>
        </div>

        <!-- Overall Results -->
        <div class="m-10 max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
            <h2 class="text-xl font-semibold mb-4 text-primary">Overall Results:</h2>
            <p class="text-lg font-bold <?php echo ($result["status"] == "sensitive") ? 'text-red-500' : 'text-green-500'; ?>">
                <?php echo ($result["status"] == "sensitive") ? "⚠️ Sensitive Data Detected!" : "✅ Image is Safe!"; ?>
            </p>
        </div>

        <a href="analyze-image.php" class="mt-4 inline-block bg-primary text-white py-2 px-6 rounded-md hover:bg-primary-dark transition duration-300">
            Upload Another Image
        </a>
    </div>
</main>

<?php include 'assets/footer.php'; ?>
