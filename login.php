<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";
include 'assets/header.php';
?>
<main class="login-page">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-lg mx-auto bg-gray-200 rounded-lg p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-center text-primary mb-6">Login to Your Account</h2>

            <?php
if (isset($_GET["logout"]) && $_GET["logout"] == "success") {
    echo '<div class="bg-green-200 text-green-800 p-3 rounded-md text-center mb-4">You have successfully logged out.</div>';
}
?>


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

            <form action="login-process.php" method="POST">
                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-dark-gray font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-dark-gray font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-hover-primary transition duration-300">
                    Login
                </button>
            </form>

            <!-- Signup Redirect -->
            <p class="text-center text-dark-gray mt-4">
                Don't have an account? 
                <a href="signup.php" class="text-primary font-semibold hover:underline">Sign Up</a>
            </p>
        </div>
    </div>
</main>


<?php include 'assets/footer.php'; ?>
