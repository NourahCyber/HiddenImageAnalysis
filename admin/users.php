<?php
$pageTitle = "Hidden Insight - Image Privacy Scanner";
include 'assets/header.php';

// Fetch all users
$stmt = $pdo->prepare("SELECT * FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
require '../assets/sidemenu.php'; // Include the sidebar
?>

<main class="dashboard">
<h1 class="text-3xl font-bold text-primary mb-6">All Users</h1>

<div class="bg-white shadow-lg rounded-lg p-4 overflow-x-auto">
    <table class="users w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Role</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Registered On</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="4" class="text-center p-4">No users found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-4 py-2"><?php echo $user['id']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['user_type']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo date("d M Y - H:i", strtotime($user['created_at'])); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><a href="user-analyzis.php?id=<?php echo $user['id']; ?>" class="bg-primary text-white py-1 px-3 rounded-md hover:bg-primary-dark transition duration-300">View User Analyzis</a>
                        <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="bg-primary text-white py-1 px-3 rounded-md hover:bg-primary-dark transition duration-300">Edit User</a> 
                    </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</main>


<?php include 'assets/footer.php'; ?>
