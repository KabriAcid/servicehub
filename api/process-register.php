<?php
require_once __DIR__ . '/../config/config.php';

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
        $_SESSION['error'] = "Please fill all fields.";
        header("Location: ../register.php");
        exit;
    }

    // Check if email or phone already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$email, $phone]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email or phone already registered.";
        header("Location: ../register.php");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role, location, verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $result = $stmt->execute([$name, $email, $phone, $hashed_password, $role, $location]);

    if ($result) {
        // Get the newly created user's ID
        $user_id = $pdo->lastInsertId();

        // Create a wallet row for the user
        $stmt = $pdo->prepare("INSERT INTO wallets (user_id, balance, last_updated) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, 0.00]);

        $_SESSION['success'] = "Account created successfully.";
        header("Location: ../login.php");
        exit;
    } else {
        $_SESSION['error'] = "Registration failed.";
        header("Location: ../register.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../register.php");
    exit;
}
