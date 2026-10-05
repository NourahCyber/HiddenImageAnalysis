<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";

include 'assets/header.php';


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    $_SESSION["error"] = "Invalid request.";
    header("Location: users.php");
    exit();
}else{
    $userId = $_GET["id"];
}

// Fetch all analysis results for a specific user via image -> user relation
$stmt = $pdo->prepare("
    SELECT sr.id, sr.image_id, sr.detected_data, sr.labels, sr.hidden_message, sr.metadata, sr.status, sr.created_at, 
           i.image_path, u.name AS user_name 
    FROM scan_results sr 
    JOIN images i ON sr.image_id = i.id 
    JOIN users u ON i.user_id = u.id 
    WHERE i.user_id = :userId 
    ORDER BY sr.created_at DESC
");
$stmt->execute(['userId' => $userId]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


require '../assets/sidemenu.php'; // Include the sidebar
?>

<main class="History">
<h1 class="text-3xl font-bold text-primary mb-6">All Analysis History For <?php if (!empty($results)) {
    echo "User: " . $results[0]['user_name'];
} ?> </h1>

<div class="bg-white shadow-lg rounded-lg p-4 overflow-x-auto">
    <table class="w-full users border-collapse border border-gray-200">
    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">User</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Image</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Hidden Messages</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Detected Data</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Labels</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Metadata</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($results)): ?>
                            <tr>
                                <td colspan="8" class="text-center p-4">No analysis history found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($results as $result): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($result['user_name']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <img src="<?php echo htmlspecialchars($result['image_path']); ?>" alt="Analyzed Image" class="w-16 h-16 object-cover rounded-md">
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <span class="<?php echo ($result['status'] == 'sensitive') ? 'text-red-500 font-bold' : 'text-green-500'; ?>">
                                            <?php echo ucfirst($result['status']); ?>
                                        </span>
                                    </td>

                                    <td class="border border-gray-300 px-4 py-2">
                                        <?php 
            if (!empty($result['hidden_message'])) {
                echo nl2br($result['hidden_message']); 
            } else {
                echo "<span class='text-gray-500'>No hidden message found.</span>";
            }
            ?>
                                    </td>
                                   
                                    <!-- Shortened Detected Data -->
                                    <td class="border border-gray-300 px-4 py-2 max-h-24 overflow-hidden text-sm">
                                        <?php
                                        $detectedText = json_decode($result['detected_data'], true)['text'] ?? 'No text detected';
                                        echo strlen($detectedText) > 50 ? substr($detectedText, 0, 50) . '...' : $detectedText;
                                        ?>
                                    </td>

                                    <!-- Shortened Labels -->
                                    <td class="border border-gray-300 px-4 py-2 max-h-24 overflow-hidden text-sm">
                                        <?php
                                        $labels = json_decode($result["labels"], true) ?? [];
                                        $labelText = empty($labels) ? "No labels detected" : implode(", ", $labels);
                                        echo strlen($labelText) > 50 ? substr($labelText, 0, 50) . '...' : $labelText;
                                        ?>
                                    </td>

                                    <!-- Shortened Metadata -->
                                    <td class="border border-gray-300 px-4 py-2 max-h-24 overflow-hidden text-sm">
                                        <?php
                                        $metadata = json_decode($result["metadata"], true) ?? [];
                                        $metadataPreview = isset($metadata["Camera"]) ? "📷 " . $metadata["Camera"] . ", " : "";
                                        $metadataPreview .= isset($metadata["GPS"]) && $metadata["GPS"] !== "Not Available" ? "📍 GPS Detected" : "📍 No GPS";
                                        echo $metadataPreview;
                                        ?>
                                    </td>

                                    <td class="border border-gray-300 px-4 py-2"><?php echo date("d M Y - H:i", strtotime($result["created_at"])); ?></td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a href="view.php?id=<?php echo $result['id']; ?>" class="bg-primary text-white py-1 px-3 rounded-md hover:bg-primary-dark transition duration-300">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
    </table>
</div>
</main>



<?php include 'assets/footer.php'; ?>
