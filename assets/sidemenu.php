<!-- Sidebar -->
<aside class="w-64 bg-primary text-white left-0 top-16 sidebbar shadow-lg flex flex-col">
    <div class="p-6 text-center border-b border-gray-600">
        <h2 class="text-xl font-bold">Dashboard</h2>
        <p class="text-gray-300">Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!</p>
    </div>
    <nav class="flex-grow mt-4">
        <ul class="sidebar-menu">
            <?php if ($userType === "admin"): ?>
                <li><a href="index.php" class="sidebar-link">Home</a></li>
                <li><a href="users.php" class="sidebar-link">Users</a></li>
                <li><a href="Analyzis-history.php" class="sidebar-link">Analyzis History</a></li>
            <?php else: ?>
                <li><a href="index.php" class="sidebar-link">Home</a></li>
                <li><a href="analyze-image.php" class="sidebar-link">Analyze New Image</a></li>
                <li><a href="Analyzis-history.php" class="sidebar-link">Analyzis History</a></li>
            <?php endif; ?>
            <li><a href="update-profile.php" class="sidebar-link">Update Profile</a></li>
        </ul>
    </nav>
    <div class="p-4 border-t border-gray-600">
        <a href="../logout.php" class="sidebar-link text-red-300 hover:bg-red-700">Logout</a>
    </div>
</aside>