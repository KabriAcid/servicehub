<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Helper function to sanitize input
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!$email || !$password) {
        header("Location: ../login.php?error=Please+fill+all+fields");
        exit;
    }

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT id, name, email, password, role, verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];

        // Redirect to dashboard or homepage
        header("Location: ../public/backend/dashboard.php?success=Login+successful");
        exit;
    } else {
        header("Location: ../login.php?error=Invalid+email+or+password");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
