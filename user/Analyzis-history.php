<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";

include 'assets/header.php';

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "Please log in to view your history.";
    header("Location: login.php");
    exit();
}

require '../assets/sidemenu.php'; // Include the sidebar
require '../assets/db.php';

$userId = $_SESSION["user_id"];

// ✅ Handle filters
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

$query = "
    SELECT sr.id, sr.image_id, sr.detected_data, sr.hidden_message, sr.labels, sr.metadata, sr.status, sr.created_at, i.image_path 
    FROM scan_results sr 
    JOIN images i ON sr.image_id = i.id 
    WHERE i.user_id = ?
";

$params = [$userId];

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

<main class="History flex">
    <div class="w-full max-w-6xl ml-20">
        <h1 class="text-3xl font-bold text-primary mb-6 text-center">Analysis History</h1>

        <!-- ✅ Date Range Filter Form -->
        <form method="GET" class="mb-6 flex flex-wrap gap-4 justify-center bg-white p-4 rounded shadow">
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

            <div class="pt-5">
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-hover-primary transition duration-300">
                    Apply Filter
                </button>
            </div>
        </form>

        <?php if (empty($results)): ?>
            <p class="text-gray-500 text-center">No analysis history found.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-center">
                <?php foreach ($results as $result): ?>
                    <div class="bg-gray-100 p-4 rounded-lg shadow-lg mx-auto w-full max-w-md">
                        <img src="<?php echo htmlspecialchars($result['image_path']); ?>" alt="Analyzed Image" class="w-full h-40 object-cover rounded-md mb-4">
                        <p><strong>Status:</strong> 
                            <span class="<?php echo ($result['status'] == 'sensitive') ? 'text-red-500 font-bold' : 'text-green-500'; ?>">
                                <?php echo ucfirst($result['status']); ?>
                            </span>
                        </p>
                        
                        <p class="text-lg text-gray-700 mb-4">
                            <strong>Detected Objects:</strong> 
                            <?php
                            $labels = json_decode($result["labels"], true) ?? [];
                            echo empty($labels) ? "No labels detected" : implode(", ", $labels);
                            ?>
                        </p>
                        
                        <div class="mt-2">
                            <p><strong>Detected Data:</strong></p>
                            <div class="max-h-32 overflow-y-auto bg-white p-2 border border-gray-300 rounded-md text-sm">
                                <?php
                                $metadata = json_decode($result["metadata"], true) ?? [];

                                $camera = htmlspecialchars($metadata["Camera"] ?? "Unknown");
                                $timestamp = htmlspecialchars($metadata["Timestamp"] ?? "Unknown");
                                $software = htmlspecialchars($metadata["Software"] ?? "Unknown");
                                $gps = isset($metadata["GPS"]) && is_string($metadata["GPS"]) ? $metadata["GPS"] : "No GPS Data";
                                ?>

                                <p class="text-lg text-gray-700 mb-4"><strong>Camera:</strong> <?= $camera; ?></p>
                                <p class="text-lg text-gray-700 mb-4"><strong>Timestamp:</strong> <?= $timestamp; ?></p>
                                <p class="text-lg text-gray-700 mb-4"><strong>Software:</strong> <?= $software; ?></p>
                                <p class="text-lg text-red-500 font-bold mb-4"><strong>GPS Coordinates:</strong> <?= $gps; ?></p>
                                <p class="text-lg text-red-500 font-bold mb-4"><strong>Detected Texts:</strong> 
                                    <?= nl2br(htmlspecialchars(json_decode($result['detected_data'], true)['text'] ?? 'No text detected')); ?>
                                </p>
                                <p class="text-lg text-red-500 font-bold mb-4"><strong>Hidden Message:</strong> 
                                    <?= !empty($result['hidden_message']) ? nl2br(htmlspecialchars($result['hidden_message'])) : 'No hidden message detected'; ?>
                                </p>
                            </div>
                        </div>

                        <p class="mt-2"><strong>Date:</strong> <?= date("d M Y - H:i", strtotime($result["created_at"])); ?></p>
                        <a href="view.php?id=<?= $result['id']; ?>" class="mt-2 inline-block bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark transition duration-300">
                            View Details
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'assets/footer.php'; ?>
