<?php
session_start();
require '../assets/db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_path"])) {
    $imagePath = $_POST["image_path"];

    function removeMetadata($imagePath) {
        if (!file_exists($imagePath)) {
            return "Error: File not found.";
        }

        // Determine image type
        $imageType = exif_imagetype($imagePath);

        if ($imageType === IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($imagePath);
            $newImagePath = str_replace(".jpg", "_clean.jpg", $imagePath);
        } elseif ($imageType === IMAGETYPE_PNG) {
            $image = imagecreatefrompng($imagePath);
            $newImagePath = str_replace(".png", "_clean.png", $imagePath);
        } else {
            return "Error: Unsupported image type.";
        }

        if (!$image) {
            return "Error: Unable to process the image.";
        }

        // Save the new image without metadata
        if ($imageType === IMAGETYPE_JPEG) {
            imagejpeg($image, $newImagePath, 100);
        } elseif ($imageType === IMAGETYPE_PNG) {
            imagepng($image, $newImagePath);
        }

        imagedestroy($image); // Free memory

        return $newImagePath;
    }

    $newImagePath = removeMetadata($imagePath);

    if (strpos($newImagePath, "Error") === false) {
        $_SESSION["clean_image"] = $newImagePath;
        header("Location: view-clean-image.php");
        exit();
    } else {
        $_SESSION["error"] = $newImagePath;
        header("Location: results.php");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request.";
    header("Location: results.php");
    exit();
}
