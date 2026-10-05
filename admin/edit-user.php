<?php
$pageTitle = "Edit User - Hidden Insight";
include 'assets/header.php';
require '../assets/sidemenu.php';
require '../assets/db.php';

// Fetch user by ID from URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        echo "<p>User not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid user ID.</p>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // If password is not empty and matches confirmation, update it
    if (!empty($password)) {
        if ($password === $confirm_password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, user_type = ?, password = ? WHERE id = ?");
            $updateStmt->execute([$name, $email, $user_type, $hashedPassword, $userId]);
        } else {
            echo "<script>alert('Passwords do not match');</script>";
        }
    } else {
        // Update without password
        $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, user_type = ? WHERE id = ?");
        $updateStmt->execute([$name, $email, $user_type, $userId]);
    }

    echo "<script>alert('User updated successfully'); window.location.href='users.php';</script>";
}
?>

<main class="about-page">
    <div class="container max-w-xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold text-dark-gray mb-6">Edit User</h2>
        <form action="edit-user-process.php" method="POST">
            <!-- Name Field -->
            <div class="mb-4">
                <label for="name" class="block text-dark-gray font-semibold mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-dark-gray font-semibold mb-2">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- User Type -->
            <div class="mb-4">
                <label for="user_type" class="block text-dark-gray font-semibold mb-2">User Type</label>
                <select id="user_type" name="user_type" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="user" <?= $user['user_type'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $user['user_type'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-dark-gray font-semibold mb-2">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-6">
                <label for="confirm_password" class="block text-dark-gray font-semibold mb-2">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white text-dark-gray focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full bg-primary text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-hover-primary transition duration-300">
                Update User
            </button>
        </form>
    </div>
</main>

<?php include 'assets/footer.php'; ?>
