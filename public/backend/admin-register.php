<?php
require_once __DIR__ . '/../../config/config.php';

// Helper function
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password || !$confirm_password) {
        $_SESSION['error'] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        try {
            // Check if admin already exists
            $stmt = $pdo->prepare("SELECT id FROM admin WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = "Admin with this email already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO admin (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$username, $email, $hashed_password]);

                $_SESSION['success'] = "Admin account created successfully.";
                header("Location: admin-login.php");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Admin registration error: " . $e->getMessage());
            $_SESSION['error'] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h3 class="text-center mb-4">Admin Registration</h3>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success'];
                                                            unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Admin Name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="admin@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Create Password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>