<?php
session_start();
require '../assets/db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "../uploads/";

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imagePath = ""; // Store the final image path

    /***  STEP 1: HANDLE IMAGE UPLOAD OR CAPTURED IMAGE ***/
    
    // **CASE 1: Image Uploaded via File Input**
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $image = $_FILES["image"];
        $allowedTypes = ["image/jpeg", "image/png"];

        if (!in_array($image["type"], $allowedTypes)) {
            $_SESSION["error"] = "Only JPG and PNG images are allowed.";
            header("Location: analyze-image.php");
            exit();
        }

        // Secure file name and move to uploads directory
        $fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($image["name"]));
        $imagePath = $uploadDir . basename($image["name"]);

        if (!move_uploaded_file($image["tmp_name"], $imagePath)) {
            $_SESSION["error"] = "Failed to save the uploaded file.";
            header("Location: analyze-image.php");
            exit();
        }
    } 
    // **CASE 2: Image Captured via Webcam (Base64)**
    elseif (!empty($_POST["captured_image"])) {
        $imageData = $_POST["captured_image"];
        $imageData = str_replace("data:image/png;base64,", "", $imageData);
        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            $_SESSION["error"] = "Invalid image data.";
            header("Location: analyze-image.php");
            exit();
        }

        // Generate unique filename for captured image
        $imagePath = $uploadDir . "captured_" . time() . ".png";
        file_put_contents($imagePath, $imageData);
    } 
    else {
        $_SESSION["error"] = "No image received.";
        header("Location: analyze-image.php");
        exit();
    }

    /***  STEP 2: INSERT IMAGE INTO DATABASE ***/

    $stmt = $pdo->prepare("INSERT INTO images (user_id, image_path) VALUES (?, ?)");
    $stmt->execute([$_SESSION["user_id"],  $imagePath]);
    $imageId = $pdo->lastInsertId();

    /***  STEP 3: EXTRACT EXIF METADATA ***/
    
    $exifData = @exif_read_data($imagePath);
    $metadata = [
        "Camera" => $exifData["Model"] ?? "Unknown",
        "Timestamp" => $exifData["DateTime"] ?? "Unknown",
        "Software" => $exifData["Software"] ?? "Unknown",
        "GPS" => "Not Available"
    ];

    if (!empty($exifData["GPSLatitude"]) && !empty($exifData["GPSLongitude"])) {
        function getGps($coordinate, $hemisphere) {
            $degrees = count($coordinate) > 0 ? $coordinate[0] : 0;
            $minutes = count($coordinate) > 1 ? $coordinate[1] / 60 : 0;
            $seconds = count($coordinate) > 2 ? $coordinate[2] / 3600 : 0;
            $decimal = $degrees + $minutes + $seconds;
            return ($hemisphere == 'S' || $hemisphere == 'W') ? -$decimal : $decimal;
        }

        $latHemisphere = $exifData["GPSLatitudeRef"] ?? "N";
        $lonHemisphere = $exifData["GPSLongitudeRef"] ?? "E";
        $metadata["GPS"] = getGps($exifData["GPSLatitude"], $latHemisphere) . ", " . getGps($exifData["GPSLongitude"], $lonHemisphere);
    }

    // If GPS data is found, flag image as sensitive
    $status = ($metadata["GPS"] !== "Not Available") ? "sensitive" : "safe";

    /***  STEP 4: SEND IMAGE TO GOOGLE VISION API FOR ANALYSIS ***/

    $imageData = @file_get_contents($imagePath);
    $base64Image = base64_encode($imageData);
    $apiKey = "AIzaSyCDr6iyVOj2DE5fMIbZhKJJpEM_ngUjLJI"; // Replace with actual API key
    $visionApiUrl = "https://vision.googleapis.com/v1/images:annotate?key=$apiKey";

    $requestData = [
        "requests" => [
            [
                "image" => ["content" => $base64Image],
                "features" => [
                    ["type" => "TEXT_DETECTION"],
                    ["type" => "LABEL_DETECTION"]
                ]
            ]
        ]
    ];

    $options = [
        "http" => [
            "header" => "Content-Type: application/json",
            "method" => "POST",
            "content" => json_encode($requestData)
        ]
    ];

    $response = @file_get_contents($visionApiUrl, false, stream_context_create($options));
    $result = json_decode($response, true);

    // Extract detected text
    $detectedText = "";
    if (isset($result["responses"][0]["textAnnotations"][0]["description"])) {
        $detectedText = $result["responses"][0]["textAnnotations"][0]["description"];
    }

    // Extract detected labels
    $detectedLabels = [];
    if (isset($result["responses"][0]["labelAnnotations"])) {
        foreach ($result["responses"][0]["labelAnnotations"] as $label) {
            $detectedLabels[] = $label["description"];
        }
    }

     
    // Step 3: Check for Sensitive Words (English & Arabic)
    $sensitiveWords = [
        "Access", "Account", "Address", "Alias", "Balance", "Bank", "Bank Account", "Birth Certificate",
        "CVV", "Card Number", "Company", "Confidential", "Court Document", "Credentials", "Credit Card",
        "Criminal Record", "Debit Card", "Doctor", "Driver License", "Driving License", "Email", "Employer",
        "Employment ID", "Fax", "First Name", "Full Name", "Health Insurance", "Home Address", "IBAN", "ID",
        "Identification", "Identity", "Insurance Number", "Job", "Key", "Last Name", "License", "Login",
        "Medical Record", "Mobile", "NID", "NIN", "Name", "National ID", "PIN", "PIN Code", "Passport",
        "Password", "Phone", "Phone Number", "Police Report", "Position", "Postal Code", "Prescription",
        "Restricted", "Routing Number", "SSN", "SWIFT", "Salary", "Secret", "Secret Code", "Security Code",
        "Security Question", "Social Security", "Sort Code", "Tax ID", "Telephone", "Token", "Transaction",
        "Two-Factor", "Username", "Visa", "Work Address", "Zip Code",
        
        // Arabic sensitive words
        "آيبان", "اسم العائلة", "اسم المستخدم", "الاسم", "الاسم الأول", "الاسم الكامل", "البريد الإلكتروني",
        "التأمين الصحي", "الراتب", "الرقم السري", "الرقم الضريبي", "الرمز البريدي", "السجل الجنائي", "السجل الطبي",
        "السر", "الشركة", "الضمان الاجتماعي", "الطبيب", "العنوان", "المصادقة الثنائية", "المنصب", "الهاتف",
        "الوصفة الطبية", "الوصول", "الوظيفة", "بريد إلكتروني", "بطاقة", "بطاقة ائتمان", "بطاقة الأحوال",
        "بطاقة الائتمان", "بطاقة الصراف", "بطاقة العمل", "بطاقة الهوية", "بطاقة خصم", "بنك", "بيانات اعتماد",
        "تأشيرة", "تسجيل الدخول", "تقرير الشرطة", "جواز", "جواز السفر", "جوال", "حساب", "حساب بنكي",
        "حساب مصرفي", "رخصة", "رخصة القيادة", "رخصة قيادة", "رصيد", "رقم البطاقة", "رقم التأمين",
        "رقم التأمين الوطني", "رقم التوجيه", "رقم الجوال", "رقم الحساب", "رقم الهاتف", "رقم الهوية",
        "رمز الأمان", "رمز التحقق", "رمز الدخول", "رمز السويفت", "رمز المرور", "رمز سري", "سؤال الأمان",
        "سري", "سويفت", "شهادة الميلاد", "صاحب العمل", "عنوان العمل", "عنوان المنزل", "فاكس", "كشف حساب",
        "كلمة المرور", "كنية", "كود البريد", "محظور", "مصرف", "مفتاح", "هاتف", "هوية", "هوية وطنية",
        "وثيقة المحكمة"
    ];
    foreach ($sensitiveWords as $word) {
        if (stripos($detectedText, $word) !== false) {
            $status = "sensitive";
            break;
        }
    }

    $hiddenMessage = "No hidden message found."; // Default value

    /***  STEP 6: EXTRACT LSB STEGANOGRAPHY MESSAGE ***/
if (file_exists($imagePath)) {
    $hiddenMessage = extractLSBMessage($imagePath);
}

/***  STEP 7: SAVE RESULTS TO DATABASE ***/
$stmt = $pdo->prepare("INSERT INTO scan_results (image_id, detected_data, labels, metadata, hidden_message, status) 
                      VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $imageId,
    json_encode(["text" => $detectedText], JSON_UNESCAPED_UNICODE),
    json_encode($detectedLabels, JSON_UNESCAPED_UNICODE),
    json_encode($metadata, JSON_UNESCAPED_UNICODE),
    $hiddenMessage, // Now stores actual extracted message
    $status
]);


// Store scan results in session for results page
$_SESSION["scan_result"] = [
    "text" => $detectedText,
    "labels" => $detectedLabels,
    "metadata" => $metadata,
    "hidden_message" => $hiddenMessage, // Include hidden message
    "image_path"=> $imagePath,
    "status" => $status
];


    header("Location: results.php");
    exit();
}

/*** FUNCTION TO EXTRACT LSB HIDDEN MESSAGE ***/
function extractLSBMessage($imagePath) {
    // Load the image
    $img = imagecreatefrompng($imagePath);
    if (!$img) {
        return "Error: Unable to open image or extract hidden messages.";
    }

    $width = imagesx($img);
    $height = imagesy($img);
    
    $binaryMessage = "";

    // Loop through each pixel to extract LSBs
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF; // Red
            $g = ($rgb >> 8) & 0xFF;  // Green
            $b = $rgb & 0xFF;         // Blue

            // Get LSB from each color channel
            $binaryMessage .= ($r & 1);
            $binaryMessage .= ($g & 1);
            $binaryMessage .= ($b & 1);
        }
    }

    // Convert binary message to text
    $textMessage = "";
    for ($i = 0; $i < strlen($binaryMessage); $i += 8) {
        $byte = substr($binaryMessage, $i, 8);
        $char = chr(bindec($byte));
        
        // Stop if null character is encountered (message end)
        if ($char === "\0") break;
        
        $textMessage .= $char;
    }

    imagedestroy($img); // Free memory

    return (!empty(trim($textMessage))) ? $textMessage : "No hidden message found.";
}

?>
