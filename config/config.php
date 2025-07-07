<?php
session_start();
require_once __DIR__ . '/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    switch ($user_role) {
        case 'client':
            $query = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            break;
        case 'provider':
            $query = $pdo->prepare("SELECT * FROM providers WHERE id = ?");
            break;
        case 'admin':
            $query = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
            break;
        default:
            session_destroy();
            header("Location: ../login.php");
            exit;
    }

    $query->execute([$user_id]);
    $user = $query->fetch();

    if (!$user) {
        session_destroy();
        header("Location: ../login.php");
        exit;
    }

    // User found and valid
    return $user;
} catch (PDOException $e) {
    error_log("Error fetching current user: " . $e->getMessage());
    session_destroy();
    header("Location: ../login.php");
    exit;
}
