<?php
require_once __DIR__ . '/../config/config.php';

function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $_SESSION['login_error'] = 'All fields are required.';
        header("Location: ../login.php");
        exit;
    }

    $user = null;
    $role = null;

    try {
        // First: Check in users table (clients & providers)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $role = $user['role'];
        } else {
            // Second: Check in admin table
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
            $stmt->execute([$email]);
            $adminUser = $stmt->fetch();

            if ($adminUser) {
                $user = $adminUser;
                $role = 'admin';
            }
        }

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $role;

            error_log("Login success: ID {$user['id']} | Role: {$role}");
            header("Location: ../public/backend/dashboard.php");
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid email or password.';
            error_log("Login error: Invalid credentials for email {$email}");
            header("Location: ../login.php");
            exit;
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $_SESSION['login_error'] = 'Something went wrong. Please try again.';
        header("Location: ../login.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = 'Invalid request method.';
    header("Location: ../login.php");
    exit;
}
