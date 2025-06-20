<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard | ServiceHub</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php
    require_once __DIR__ . '/../components/sidebar.php';
    ?>
    <div class="dashboard-content">
        <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>!</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <div class="card-icon text-primary mb-2"><i class="fa-solid fa-users"></i></div>
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text fs-4">--</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <div class="card-icon text-success mb-2"><i class="fa-solid fa-wallet"></i></div>
                    <h5 class="card-title">Wallet Balance</h5>
                    <p class="card-text fs-4">₦ --</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <div class="card-icon text-warning mb-2"><i class="fa-solid fa-star"></i></div>
                    <h5 class="card-title">Your Ratings</h5>
                    <p class="card-text fs-4">--</p>
                </div>
            </div>
        </div>

        <div class="section-title">Quick Actions</div>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="../services.php" class="btn btn-outline-primary w-100"><i class="fa fa-search"></i> Find Services</a>
            </div>
            <div class="col-md-3">
                <a href="../bookings.php" class="btn btn-outline-success w-100"><i class="fa fa-calendar-check"></i> My Bookings</a>
            </div>
            <div class="col-md-3">
                <a href="../wallet.php" class="btn btn-outline-info w-100"><i class="fa fa-wallet"></i> Wallet</a>
            </div>
            <div class="col-md-3">
                <a href="../profile.php" class="btn btn-outline-dark w-100"><i class="fa fa-user"></i> Profile</a>
            </div>
        </div>

        <div class="section-title">Recent Activity</div>
        <div class="card mt-3 p-3">
            <p class="text-muted mb-0">No recent activity yet.</p>
        </div>
    </div>
</body>

</html>