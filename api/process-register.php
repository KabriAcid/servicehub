<?php
require_once __DIR__ . '/../config/database.php';

// Helper function to sanitize input
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $name     = clean_input($_POST['name'] ?? '');
    $email    = clean_input($_POST['email'] ?? '');
    $phone    = clean_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = clean_input($_POST['role'] ?? '');
    $location = clean_input($_POST['location'] ?? '');

    // Basic validation
    if (!$name || !$email || !$phone || !$password || !$role || !$location) {
        header("Location: ../register.php?error=Please+fill+all+fields");
        exit;
    }

    // Check if email or phone already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$email, $phone]);
    if ($stmt->fetch()) {
        header("Location: ../register.php?error=Email+or+phone+already+registered");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role, location, verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $result = $stmt->execute([$name, $email, $phone, $hashed_password, $role, $location]);

    if ($result) {
        header("Location: ../login.php?success=Account+created+successfully");
        exit;
    } else {
        header("Location: ../register.php?error=Registration+failed");
        exit;
    }
} else {
    header("Location: ../register.php");
    exit;
}
