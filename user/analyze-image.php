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
    <div class="mb-6 text-center">
        <p class="text-lg font-semibold text-primary">Choose an Option:</p>
        <div class="flex justify-center gap-4 mt-4">
            <button type="button" onclick="showUpload()" class="bg-secondary text-white py-2 px-4 rounded-md hover:bg-dark-gray transition duration-300">
                📤 Upload Image
            </button>
            <button type="button" onclick="showCamera()" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition duration-300">
                📷 Take Photo
            </button>
        </div>
    </div>

    <!-- Image Upload Input (Initially Hidden) -->
    <div id="upload-container" class="hidden mb-6">
        <label for="image-upload" class="block text-primary-foreground text-lg mb-2">Upload an Image</label>
        <input type="file" id="image-upload" name="image" accept="image/*"
               class="w-full bg-white p-3 border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
    </div>

    <!-- Camera Container (Initially Hidden) -->
    <div id="camera-container" class="hidden text-center">
        <video id="camera-feed" autoplay class="w-full max-w-md mx-auto rounded-lg shadow-md"></video>
        <canvas id="captured-image" class="hidden"></canvas>
        <button type="button" onclick="capturePhoto()" class="mt-4 bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition duration-300">
            Capture Photo
        </button>
        <br><br>
    </div>

    <!-- Captured Image Preview -->
    <div id="preview-container" class="hidden text-center">
        <img id="captured-photo" class="w-full max-w-md mx-auto rounded-lg shadow-md" alt="Captured Image">
        <p class="text-gray-500 text-sm mt-2">Captured image will be submitted.</p>
        <br>
    </div>

    <!-- Hidden Input for Captured Image -->
    <input type="hidden" id="captured-image-data" name="captured_image">

    <?php if (isset($_SESSION["user_id"])): ?>
        <button type="submit" id="scan-button" class="w-full bg-primary text-primary-foreground py-3 px-6 rounded-md text-lg font-semibold hover:bg-primary-dark transition duration-300 hidden">
            Scan for Sensitive Data
        </button>
    <?php else: ?>
        <p class="text-red-500 font-semibold text-center mt-4">
            You must <a href="login.php" class="text-primary hover:underline">log in</a> to upload an image.
        </p>
        <a href="login.php" class="w-full text-white bg-primary text-primary-foreground py-3 px-6 rounded-md text-lg font-semibold hover:bg-primary-dark transition duration-300">
            Log In
        </a>
    <?php endif; ?>
</form>


            </div>
        </section>


</main>



<script>

    // Show Upload Input and Hide Camera
function showUpload() {
    document.getElementById("upload-container").classList.remove("hidden");
    document.getElementById("camera-container").classList.add("hidden");
    document.getElementById("preview-container").classList.add("hidden");
    document.getElementById("scan-button").classList.remove("hidden"); // Show scan button
}

// Show Camera and Hide Upload Input
function showCamera() {
    document.getElementById("upload-container").classList.add("hidden");
    document.getElementById("camera-container").classList.remove("hidden");
    document.getElementById("preview-container").classList.add("hidden");
    document.getElementById("scan-button").classList.remove("hidden"); // Show scan button

    // Start Camera
    let video = document.getElementById("camera-feed");
    navigator.mediaDevices.getUserMedia({ video: true })
        .then((stream) => {
            video.srcObject = stream;
        })
        .catch((error) => {
            console.error("Error accessing camera: ", error);
            alert("Could not access camera.");
        });
}

// Capture Photo
function capturePhoto() {
    let video = document.getElementById("camera-feed");
    let canvas = document.getElementById("captured-image");
    let context = canvas.getContext("2d");

    // Set canvas size and draw the image from the video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert to Base64
    let imageData = canvas.toDataURL("image/png");
    document.getElementById("captured-image-data").value = imageData;

    // Show preview
    document.getElementById("preview-container").classList.remove("hidden");
    document.getElementById("captured-photo").src = imageData;
}

let videoStream = null;

function openCamera() {
    const cameraContainer = document.getElementById("camera-container");
    const previewContainer = document.getElementById("preview-container");
    const cameraFeed = document.getElementById("camera-feed");

    // Hide the preview container and show the camera feed
    previewContainer.classList.add("hidden");
    cameraContainer.classList.remove("hidden");

    // Access user's webcam
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            videoStream = stream;
            cameraFeed.srcObject = stream;
        })
        .catch(error => {
            console.error("Error accessing webcam:", error);
        });
}

function capturePhoto() {
    const cameraFeed = document.getElementById("camera-feed");
    const canvas = document.getElementById("captured-image");
    const capturedImageInput = document.getElementById("captured-image-data");
    const previewContainer = document.getElementById("preview-container");
    const capturedPhoto = document.getElementById("captured-photo");
    const cameraContainer = document.getElementById("camera-container");

    // Set canvas size to match video feed
    canvas.width = cameraFeed.videoWidth;
    canvas.height = cameraFeed.videoHeight;

    // Draw video frame to canvas
    const context = canvas.getContext("2d");
    context.drawImage(cameraFeed, 0, 0, canvas.width, canvas.height);

    // Convert canvas to Base64 image
    const imageData = canvas.toDataURL("image/png");
    capturedImageInput.value = imageData;

    // Stop the camera stream
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
    }

    // Hide the camera and show preview
    cameraContainer.classList.add("hidden");
    previewContainer.classList.remove("hidden");
    capturedPhoto.src = imageData;
}
</script>

<?php include 'assets/footer.php'; ?>
