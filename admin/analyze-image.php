<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";
include 'assets/header.php';
?>

<?php
require '../assets/sidemenu.php'; // Include the sidebar
?>

<main class="dashboard">
  
<section class="text-center mb-16">
            <h1 class="text-5xl font-bold mb-4 text-primary">Hidden Insight</h1>

             <!-- Display Success or Error Messages -->
             <?php if (isset($_SESSION["success"])): ?>
                <div class="bg-green-200 text-green-800 p-3 rounded-md text-center mb-4">
                    <?php 
                        echo $_SESSION["success"];
                        unset($_SESSION["success"]); // Clear message after displaying
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION["error"])): ?>
                <div class="bg-red-200 text-red-800 p-3 rounded-md text-center mb-4">
                    <?php 
                        echo $_SESSION["error"];
                        unset($_SESSION["error"]); // Clear message after displaying
                    ?>
                </div>
            <?php endif; ?>


            <p class="text-xl mb-8 text-primary-foreground">Protect Your Privacy by Detecting Sensitive Information in Images</p>
            <div class="textbox bg-opacity-60 rounded-lg p-8 shadow-lg max-w-2xl mx-auto">
              <form id="upload-form" enctype="multipart/form-data" action="analyze-image-process.php" method="POST">
    <div class="mb-6">
        <label for="image-upload" class="block text-primary-foreground text-lg mb-2">Upload an Image</label>
        <input type="file" id="image-upload" name="image" accept="image/*" required
               class="w-full bg-white p-3 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
    </div>

    <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-md text-lg font-semibold hover:bg-primary-dark transition duration-300">
        Scan for Sensitive Data
    </button>
</form>

            </div>
        </section>


</main>


<?php include 'assets/footer.php'; ?>
