<?php
require '../assets/db.php';

// Check if request is POST and ID is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $userId = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        if (!empty($password)) {
            if ($password === $confirm_password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, user_type = ?, password = ? WHERE id = ?");
                $stmt->execute([$name, $email, $user_type, $hashedPassword, $userId]);
            } else {
                echo "<script>alert('Passwords do not match.'); history.back();</script>";
                exit;
            }
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, user_type = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user_type, $userId]);
        }

        echo "<script>alert('User updated successfully'); window.location.href='users.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Update failed: " . $e->getMessage() . "'); history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request'); history.back();</script>";
}
?>
