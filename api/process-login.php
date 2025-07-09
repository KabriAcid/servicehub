<?php
session_start();
require_once __DIR__ . '/../config/database.php';

function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['login_error'] = "Invalid request method.";
    header("Location: ../login.php");
    exit;
}

$email = clean_input($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    $_SESSION['login_error'] = "Both email and password are required.";
    header("Location: ../login.php");
    exit;
}

try {
    $user = null;
    $role = null;

    // Check users table
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['role'] == ADMIN) {
            $role = 'admin';
        } elseif ($user['role'] == PROVIDER) {
            $role = 'provider';
        } else {
            $role = 'client';
        }
    }



    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $role;

        header("Location: ../public/backend/dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: ../login.php");
        exit;
    }
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    $_SESSION['login_error'] = "An error occurred. Please try again.";
    header("Location: ../login.php");
    exit;
}
