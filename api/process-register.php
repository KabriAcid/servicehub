<?php
session_start();
require_once __DIR__ . '/../config/database.php';  // Only database connection, no auth checks

function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../register.php");
    exit;
}

$name = clean_input($_POST['name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$phone = clean_input($_POST['phone'] ?? '');
$address = clean_input($_POST['address'] ?? '');
$city = clean_input($_POST['city'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = clean_input($_POST['role'] ?? '');  // Expected values: 'user' or 'provider'

if (!$name || !$email || !$phone || !$password || !$confirm_password || !$role) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: ../register.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email address.";
    header("Location: ../register.php");
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: ../register.php");
    exit;
}

try {
    // Check for duplicates
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$email, $phone]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email or phone already registered.";
        header("Location: ../register.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = ($role == 1) ? 1 : 2;

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password, address, city, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $phone, $hashed_password, $address, $city, $role]);

    $user_id = $pdo->lastInsertId();

    // Create wallet
    $wallet_stmt = $pdo->prepare("INSERT INTO wallets (user_id, balance, last_updated) VALUES (?, 0.00, NOW())");
    $wallet_stmt->execute([$user_id]);

    $_SESSION['success'] = "Account created successfully. Please login.";
    header("Location: ../login.php");
    exit;
} catch (PDOException $e) {
    error_log("Registration Error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred. Please try again.";
    header("Location: ../register.php");
    exit;
}
