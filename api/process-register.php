<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Helper function
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $phone = clean_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = clean_input($_POST['role'] ?? '');
    $location = clean_input($_POST['location'] ?? '');

    if (!$name || !$email || !$phone || !$password || !$confirm_password || !$role || !$location) {
        $_SESSION['error'] = "All fields are required.";
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
        // Check uniqueness based on selected role
        if ($role === 'client') {
            $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ? OR phone = ?");
        } elseif ($role === 'provider') {
            $stmt = $pdo->prepare("SELECT id FROM providers WHERE email = ? OR phone = ?");
        } else {
            $_SESSION['error'] = "Invalid role selected.";
            header("Location: ../register.php");
            exit;
        }

        $stmt->execute([$email, $phone]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Email or phone already registered.";
            header("Location: ../register.php");
            exit;
        }

        // Insert into respective table
        if ($role === 'client') {
            $stmt = $pdo->prepare("INSERT INTO clients (full_name, email, phone, password, address, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $phone, $hashed_password, $location]);

            $user_id = $pdo->lastInsertId();
            $wallet = $pdo->prepare("INSERT INTO wallets (user_id, balance, last_updated) VALUES (?, 0.00, NOW())");
            $wallet->execute([$user_id]);
        } elseif ($role === 'provider') {
            $stmt = $pdo->prepare("INSERT INTO providers (full_name, email, phone, password, address, service_id, title, description, location, price, rating, status, created_at) VALUES (?, ?, ?, ?, ?, 0, '', '', ?, 0.00, 0.00, 'pending', NOW())");
            $stmt->execute([$name, $email, $phone, $hashed_password, $location, $location]); // location used as placeholder for service location
        }

        $_SESSION['success'] = "Registration successful. Please log in.";
        header("Location: ../login.php");
        exit;
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: ../register.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../register.php");
    exit;
}
