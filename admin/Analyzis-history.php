<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";
include 'assets/header.php';
require '../assets/sidemenu.php';
require '../assets/db.php';

// Get filters from GET
$usernameFilter = $_GET['username'] ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

// Build dynamic SQL query with filters
$query = "
    SELECT sr.id, sr.image_id, sr.detected_data, sr.labels, sr.hidden_message, sr.metadata, sr.status, sr.created_at, 
           i.image_path, u.name AS user_name 
    FROM scan_results sr 
    JOIN images i ON sr.image_id = i.id 
    JOIN users u ON i.user_id = u.id 
    WHERE 1
";

$params = [];

// Apply filters
if (!empty($usernameFilter)) {
    $query .= " AND u.name LIKE ?";
    $params[] = '%' . $usernameFilter . '%';
}
if (!empty($fromDate)) {
    $query .= " AND sr.created_at >= ?";
    $params[] = $fromDate . " 00:00:00";
}
if (!empty($toDate)) {
    $query .= " AND sr.created_at <= ?";
    $params[] = $toDate . " 23:59:59";
}

$query .= " ORDER BY sr.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="History">
    <h1 class="text-3xl font-bold text-primary mb-6">All Analysis History</h1>

    <!-- Filter Form -->
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end bg-white p-4 rounded shadow-md">
        <div>
            <label for="username" class="block font-semibold text-sm text-gray-700 mb-1">Search by Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($usernameFilter) ?>"
                   class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        <div>
            <label for="from_date" class="block font-semibold text-sm text-gray-700 mb-1">From Date</label>
            <input type="date" id="from_date" name="from_date" value="<?= htmlspecialchars($fromDate) ?>"
                   class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        <div>
            <label for="to_date" class="block font-semibold text-sm text-gray-700 mb-1">To Date</label>
            <input type="date" id="to_date" name="to_date" value="<?= htmlspecialchars($toDate) ?>"
                   class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        <div>
            <button type="submit"
                    class="bg-primary text-white px-4 py-2 rounded hover:bg-hover-primary transition duration-300">
                Apply Filters
            </button>
        </div>
    </form>

    <!-- Analysis Table -->
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
                        <td colspan="9" class="text-center p-4">No analysis history found.</td>
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
                            <td class="border border-gray-300 px-4 py-2 max-h-24 overflow-hidden text-sm">
                                <?php
                                    $detectedText = json_decode($result['detected_data'], true)['text'] ?? 'No text detected';
                                    echo strlen($detectedText) > 50 ? substr($detectedText, 0, 50) . '...' : $detectedText;
                                ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 max-h-24 overflow-hidden text-sm">
                                <?php
                                    $labels = json_decode($result["labels"], true) ?? [];
                                    $labelText = empty($labels) ? "No labels detected" : implode(", ", $labels);
                                    echo strlen($labelText) > 50 ? substr($labelText, 0, 50) . '...' : $labelText;
                                ?>
                            </td>
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
    </div>
</main>

<?php include 'assets/footer.php'; ?>
