<?php
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    $user_role = $_SESSION['user_role'] ?? null;

    // Select user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header("Location: ../login.php");
        exit;
    }
} else {
    $_SESSION['error'] = "You must be logged in to access the page.";
    header("Location: ../login.php");
    exit;
}
