<?php
require_once __DIR__ . '/../config/config.php';

// Helper function
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

// Ensure form submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../public/backend/profile.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$user_id || !$user_role) {
    $_SESSION['error'] = "Unauthorized access.";
    header("Location: ../public/backend/profile.php");
    exit;
}

// Sanitize inputs
$name             = clean_input($_POST['name'] ?? '');
$email            = clean_input($_POST['email'] ?? '');
$phone            = clean_input($_POST['phone'] ?? '');
$address          = clean_input($_POST['address'] ?? '');
$city             = clean_input($_POST['city'] ?? '');
$password         = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$service_title       = clean_input($_POST['service_title'] ?? '');
$service_description = clean_input($_POST['service_description'] ?? '');
$service_price       = is_numeric($_POST['service_price'] ?? '') ? floatval($_POST['service_price']) : 0.00;

// Validate required fields
if (!$name || !$email || !$phone || !$address || !$city) {
    $_SESSION['error'] = "Please fill all required fields.";
    header("Location: ../public/backend/profile.php");
    exit;
}

// Validate password match
if (!empty($password) || !empty($confirm_password)) {
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../public/backend/profile.php");
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

try {
    // Check for existing email or phone conflict (excluding self)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE (email = ? OR phone = ?) AND id != ?");
    $stmt->execute([$email, $phone, $user_id]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email or phone already in use by another account.";
        header("Location: ../public/backend/profile.php");
        exit;
    }

    // Build update query dynamically
    $fields = "full_name = ?, email = ?, phone = ?, address = ?, city = ?";
    $params = [$name, $email, $phone, $address, $city];

    if (!empty($password)) {
        $fields .= ", password = ?";
        $params[] = $hashed_password;
    }

    // If provider, include service fields
    if ($user_role === 'provider') {
        $fields .= ", service_title = ?, service_description = ?, service_price = ?";
        array_push($params, $service_title, $service_description, $service_price);
    }

    $params[] = $user_id;

    $sql = "UPDATE users SET $fields WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $_SESSION['success'] = "Profile updated successfully.";
    header("Location: ../public/backend/profile.php");
    exit;
} catch (PDOException $e) {
    error_log("Profile update error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred. Please try again.";
    header("Location: ../public/backend/profile.php");
    exit;
}
