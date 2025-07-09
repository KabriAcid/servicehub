<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/auth.php';

// Ensure only admin can delete services
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$serviceId = $_GET['id'] ?? null;

if (!$serviceId || !is_numeric($serviceId)) {
    $_SESSION['error'] = "Invalid service ID.";
    header("Location: manage_app.php");
    exit;
}

try {
    // Optional: Check if service exists
    $stmt = $pdo->prepare("SELECT id FROM services WHERE id = ?");
    $stmt->execute([$serviceId]);

    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Service not found.";
        header("Location: manage_app.php");
        exit;
    }

    // Perform deletion
    $deleteStmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $deleteStmt->execute([$serviceId]);

    $_SESSION['success'] = "Service deleted successfully.";
} catch (PDOException $e) {
    error_log("Delete Service Error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while deleting the service.";
}

header("Location: manage_app.php");
exit;
