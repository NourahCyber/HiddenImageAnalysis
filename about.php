<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";
include 'assets/header.php';
?>

<main class="about-page">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-8 text-primary">About Hidden Insight</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="about-content textbox rounded-lg p-6 shadow-lg">
                <h2 class="text-2xl font-semibold mb-4 text-primary">Our Mission</h2>
                <p class="mb-6 text-dark-gray">
                    Hidden Insight is dedicated to protecting digital privacy by detecting sensitive information hidden in images before they are shared. 
                    Our AI-powered scanner helps individuals and businesses prevent accidental data leaks and maintain security.
                </p>
                <h2 class="text-2xl font-semibold mb-4 text-primary">Our Vision</h2>
                <p class="mb-6 text-dark-gray">
                    We envision a world where privacy is protected effortlessly, ensuring that no personal data is exposed through images. 
                    Our goal is to empower users with AI-driven insights for better control over their digital footprint.
                </p>
            </div>
            <div class="about-image relative overflow-hidden rounded-lg shadow-lg">
                <img src="assets/privacy_protection.jpg" alt="AI-powered image scanner" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-primary to-transparent opacity-75"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-2xl font-bold text-white mb-2">AI-Powered Privacy</h3>
                    <p class="text-white">Ensuring your sensitive data stays hidden.</p>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <h2 class="text-3xl font-bold mb-6 text-center text-primary">The Hidden Insight Advantage</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="textbox rounded-lg p-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2 text-primary">AI-Powered Detection</h3>
                    <p class="text-dark-gray">Detects hidden text, phone numbers, addresses, and sensitive metadata.</p>
                </div>
                <div class="textbox rounded-lg p-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2 text-primary">Privacy Assurance</h3>
                    <p class="text-dark-gray">Ensure your images are safe before sharing them on social media or messaging apps.</p>
                </div>
                <div class="textbox rounded-lg p-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2 text-primary">Metadata Removal</h3>
                    <p class="text-dark-gray">Automatically scans and removes hidden EXIF data that could reveal your location.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <h2 class="text-3xl font-bold mb-6 text-primary">Take Control of Your Privacy</h2>
            <a href="contact.php" class="inline-block bg-primary text-white font-semibold text-lg py-3 px-8 rounded-lg shadow-md hover:bg-hover-primary transition duration-300 ease-in-out">
                Get Started
            </a>
        </div>
    </div>
</main>


<?php include 'assets/footer.php'; ?>
