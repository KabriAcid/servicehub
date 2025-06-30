<?php
session_start();
require_once __DIR__ . '/database.php';

if (isset($_SESSION['user_id'])) {
    try {
        $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$_SESSION['user_id']]);
        return $user = $query->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching current user: " . $e->getMessage());
        return null;
    }
} else {
    header("Location: ../login.php");
}
