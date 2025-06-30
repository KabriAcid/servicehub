<!-- filepath: c:\xampp\htdocs\servicehub\api\process-profile.php -->
<?php
require_once __DIR__ . '/../config/config.php';

// Helper function to sanitize input
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $name             = clean_input($_POST['name'] ?? '');
    $email            = clean_input($_POST['email'] ?? '');
    $phone            = clean_input($_POST['phone'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate required fields
    if (!$name || !$email || !$phone) {
        $_SESSION['error'] = "Please fill all required fields.";
        header("Location: ../public/backend/profile.php");
        exit;
    }

    // Validate password match if provided
    if ($password || $confirm_password) {
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: ../public/backend/profile.php");
            exit;
        }
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    try {
        // Check if email or phone already exists for another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE (email = ? OR phone = ?) AND id != ?");
        $stmt->execute([$email, $phone, $current_user['id']]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Email or phone already in use.";
            header("Location: ../public/backend/profile.php");
            exit;
        }

        // Update user profile
        if ($password) {
            // Update profile with password
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, password = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $hashed_password, $current_user['id']]);
        } else {
            // Update profile without password
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $current_user['id']]);
        }

        $_SESSION['success'] = "Profile updated successfully.";
        header("Location: ../public/backend/profile.php");
        exit;
    } catch (PDOException $e) {
        error_log("Error updating profile: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while updating your profile.";
        header("Location: ../public/backend/profile.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../public/backend/profile.php");
    exit;
}
