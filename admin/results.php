<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";
include 'assets/header.php';
?>

<?php
require '../assets/sidemenu.php'; // Include the sidebar
?>

<main class="dashboard">
<?php

// Check if scan results exist
if (!isset($_SESSION["scan_result"])) {
    $_SESSION["error"] = "No scan results found.";
    header("Location: upload.php");
    exit();
}

$result = $_SESSION["scan_result"];
?>


<?php if (!empty($_SESSION['scan_result']['image_path'])): ?>
    <div class="text-center my-6">
        <h2 class="text-xl font-semibold text-primary mb-4">Uploaded Image</h2>
        <img src="<?php echo htmlspecialchars($_SESSION['scan_result']['image_path']); ?>" 
             alt="Analyzed Image" 
             class="w-full max-w-md mx-auto rounded-lg shadow-md">
    </div>
<?php endif; ?>

<main class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-center text-primary mb-6">Analysis Results</h1>

  
    <?php
// Extract metadata safely
$metadata = is_array($result["metadata"]) ? $result["metadata"] : json_decode($result["metadata"], true) ?? [];
$camera = $metadata["Camera"] ?? "Unknown";
$timestamp = $metadata["Timestamp"] ?? "Unknown";
$software = $metadata["Software"] ?? "Unknown";
$gps = $metadata["GPS"] ?? "Not Available";

// Check if any metadata field is NOT "Unknown" or "Not Available"
$hasRealMetadata = ($camera !== "Unknown" || $timestamp !== "Unknown" || $software !== "Unknown" || $gps !== "Not Available");
?>

<div class="m-10 max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
    <h2 class="text-xl font-semibold mb-4 text-primary">Metadata Information</h2>
    
    <p class="text-lg text-gray-700 mb-4">
        <strong>Camera:</strong> <?php echo htmlspecialchars($camera); ?>
    </p>

    <p class="text-lg text-gray-700 mb-4">
        <strong>Timestamp:</strong> <?php echo htmlspecialchars($timestamp); ?>
    </p>

    <p class="text-lg text-gray-700 mb-4">
        <strong>Software:</strong> <?php echo htmlspecialchars($software); ?>
    </p>

    <p class="text-lg text-gray-700 mb-4">
        <strong>GPS Coordinates:</strong> <?php echo htmlspecialchars($gps); ?>
    </p>

    <!-- Show Button Only If At Least One Metadata Field is NOT "Unknown" -->
    <?php if ($hasRealMetadata): ?>
        <form action="remove-metadata.php" method="POST">
            <input type="hidden" name="image_path" value="<?php echo htmlspecialchars($_SESSION['scan_result']['image_path']); ?>">
            <button type="submit" class="mt-4 w-full bg-red-500 text-white py-3 px-6 rounded-md font-semibold hover:bg-red-600 transition duration-300">
                Remove Metadata
            </button>
        </form>
    <?php endif; ?>
</div>


    <div class="m-10 max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">

    <h2 class="text-xl font-semibold mb-4 text-primary">Detected Text and Objects:</h2>


    <strong>Detected Text:</strong> 
    <p class="text-lg text-gray-700 mb-4">            
            <?php 
            if (!empty($result["text"])) {
                echo nl2br(htmlspecialchars($result["text"]));
            } else {
                echo "<span class='text-gray-500'>No Text Detected.</span>";
            }
            ?>
        </p>

        
        <strong>Detected Objects:</strong> 
        <p class="text-lg text-gray-700 mb-4">
            <?php 
            if (!empty($result["labels"])) {
                echo implode(", ", $result["labels"]); 
            } else {
                echo "<span class='text-gray-500'>No Objects Detected.</span>";
            }
            ?>

            
        </p>

        
    </div>

    <div class="m-10 max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
    <h2 class="text-xl font-semibold mb-4 text-primary">Hidden Message:</h2>


  <p class="text-lg text-gray-700 mb-4">
            <?php 
            if (!empty($result["hidden_message"])) {
                echo nl2br(htmlspecialchars($result["hidden_message"])); 
            } else {
                echo "<span class='text-gray-500'>No hidden message found.</span>";
            }
            ?>
        </p>


    </div>

    <div class="m-10 max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
       

    <h2 class="text-xl font-semibold mb-4 text-primary">Overall Results:</h2>


        <p class="text-lg font-bold <?php echo ($result["status"] == "sensitive") ? 'text-red-500' : 'text-green-500'; ?>">
            <?php echo ($result["status"] == "sensitive") ? "⚠️ Sensitive Data Detected!" : "✅ Image is Safe!"; ?>
        </p>

    
    </div>

   

        <a href="analyze-image.php" class="mt-4 inline-block bg-primary text-white py-2 px-6 rounded-md hover:bg-primary-dark transition duration-300">
            Upload Another Image
        </a>


</main>

</main>

<?php include 'assets/footer.php'; ?>
