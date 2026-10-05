<?php
$pageTitle = "Cleaned Image - Hidden Insight";
include 'assets/header.php';

if (!isset($_SESSION["clean_image"])) {
    $_SESSION["error"] = "No cleaned image found.";
    header("Location: results.php");
    exit();
}
?>

<main class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-center text-primary mb-6">Cleaned Image</h1>

    <div class="max-w-2xl mx-auto bg-gray-200 rounded-lg p-8 shadow-lg text-center">
        <h2 class="text-xl font-semibold mb-4 text-primary">Metadata Removed Successfully</h2>
        <img src="<?php echo htmlspecialchars($_SESSION["clean_image"]); ?>" alt="Cleaned Image"
             class="w-full max-w-md mx-auto rounded-lg shadow-md">
    </div>

    <div class="text-center mt-6">
        <a href="results.php" class="bg-primary text-white py-3 px-6 rounded-md font-semibold hover:bg-primary-dark transition duration-300">
            Back to Results
        </a>
    </div>
</main>

<?php include 'assets/footer.php'; ?>
