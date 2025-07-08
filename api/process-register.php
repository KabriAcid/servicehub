<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Sanitize helper
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name             = clean_input($_POST['name'] ?? '');
    $email            = clean_input($_POST['email'] ?? '');
    $phone            = clean_input($_POST['phone'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role             = clean_input($_POST['role'] ?? '');
    $location         = clean_input($_POST['location'] ?? '');

    if (!$name || !$email || !$phone || !$password || !$confirm_password || !$role || !$location) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../register.php");
        exit;
    }

    if (!in_array($role, ['client', 'provider'])) {
        $_SESSION['error'] = "Invalid role selected.";
        header("Location: ../register.php");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../register.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check for duplicate email or phone
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
        $check->execute([$email, $phone]);
        if ($check->fetch()) {
            $_SESSION['error'] = "Email or phone already exists.";
            header("Location: ../register.php");
            exit;
        }

        // Insert into users table
        $insertUser = $pdo->prepare("INSERT INTO users (full_name, email, phone, password, role, address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $insertUser->execute([$name, $email, $phone, $hashed_password, $role, $location]);

        $user_id = $pdo->lastInsertId();

        // Create wallet
        $wallet = $pdo->prepare("INSERT INTO wallets (user_id, balance, last_updated) VALUES (?, 0.00, NOW())");
        $wallet->execute([$user_id]);

        // If provider, insert into providers table too
        if ($role === 'provider') {
            $insertProvider = $pdo->prepare("INSERT INTO providers (user_id, service_id, title, description, location, price, rating, status, created_at) VALUES (?, 0, '', '', ?, 0.00, 0.00, 'pending', NOW())");
            $insertProvider->execute([$user_id, $location]);
        }

        $_SESSION['success'] = "Registration successful. Please log in.";
        header("Location: ../login.php");
        exit;
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        $_SESSION['error'] = "A system error occurred. Please try again.";
        header("Location: ../register.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../register.php");
    exit;
}
