<?php

$pageTitle = "Edit My Profile - Hidden Insight";
include 'assets/header.php';
require '../assets/sidemenu.php';
require '../assets/db.php';

// تأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='../login.php';</script>";
    exit;
}

$userId = $_SESSION['user_id'];

// استعلام جلب بيانات المستخدم
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p>User not found.</p>";
    exit;
}

// التعامل مع إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($password)) {
        if ($password === $confirm_password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $updateStmt->execute([$name, $email, $hashedPassword, $userId]);
        } else {
            echo "<script>alert('Passwords do not match');</script>";
        }
    } else {
        $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $updateStmt->execute([$name, $email, $userId]);
    }

    echo "<script>alert('Profile updated successfully'); window.location.href='update-profile.php';</script>";
}
?>

<main class="about-page">
    <div class="container max-w-xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold text-dark-gray mb-6">Edit My Profile</h2>
        <form method="POST">
            <!-- Full Name -->
            <div class="mb-4">
                <label for="name" class="block text-dark-gray font-semibold mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-dark-gray font-semibold mb-2">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- New Password -->
            <div class="mb-4">
                <label for="password" class="block text-dark-gray font-semibold mb-2">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="confirm_password" class="block text-dark-gray font-semibold mb-2">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- Submit -->
            <button type="submit" 
                class="w-full bg-primary text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-hover-primary transition duration-300">
                Update Profile
            </button>
        </form>
    </div>
</main>

<?php include 'assets/footer.php'; ?>
