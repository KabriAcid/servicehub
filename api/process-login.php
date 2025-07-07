<?php
session_start();
require_once __DIR__ . '/../config/database.php';

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

    // Check clients
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    $role = $user ? 'client' : null;

    // Check providers
    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM providers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        $role = $user ? 'provider' : null;
    }

    // Check admin
    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        $role = $user ? 'admin' : null;
    }

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        header("Location: ../public/backend/dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid email or password.';
        header("Location: ../login.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = 'Invalid request method.';
    header("Location: ../login.php");
    exit;
}
