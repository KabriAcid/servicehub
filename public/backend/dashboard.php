<?php
require __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/auth.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../components/header.php';
// echo $user['role'] ?? 'NULL';
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <div class="row g-4 my-3">
                    <?php if ($user['role'] == 2) { ?>
                        <!-- Customer Dashboard -->
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon accent-color mb-2"><i class="fa-solid fa-users"></i></div>
                                <h5 class="card-title">Total Bookings</h5>
                                <p class="card-text fw-bold accent-color fs-3">
                                    <?php echo getTotalBookings($pdo, $user['id']); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon accent-color mb-2"><i class="fa-solid fa-wallet"></i></div>
                                <h5 class="card-title">Wallet</h5>
                                <p class="card-text fw-bold accent-color fs-3">
                                    ₦<?php echo number_format(getWalletBalance($pdo, $user['id']), 2); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon accent-color mb-2"><i class="fa-solid fa-star"></i></div>
                                <h5 class="card-title">Your Ratings</h5>
                                <p class="card-text fw-bold accent-color fs-3">--</p> <!-- Ratings for customers can be added later -->
                            </div>
                        </div>
                    <?php } elseif ($user['role'] == 1) {
                    ?>
                        <!-- Provider Dashboard -->
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon accent-color mb-2"><i class="fa-solid fa-briefcase"></i></div>
                                <h5 class="card-title">Jobs Completed</h5>
                                <p class="card-text fw-bold accent-color fs-3">
                                    <?php echo getTotalJobsCompleted($pdo, $user['id']); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon accent-color mb-2"><i class="fa-solid fa-wallet"></i></div>
                                <h5 class="card-title">Earnings</h5>
                                <p class="card-text fw-bold accent-color fs-3 lato-bold">
                                    ₦<?php echo number_format(getWalletBalance($pdo, $user['id']), 2); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon accent-color mb-2"><i class="fa-solid fa-star"></i></div>
                                <h5 class="card-title">Bookings</h5>
                                <p class="card-text fw-bold accent-color fs-3">
                                    <?php echo getTotalBookings($pdo, $user['id']); ?>
                                </p>
                            </div>
                        </div>
                    <?php } elseif ($user['role'] == 0) { ?>
                        <!-- Admin Dashboard -->
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon accent-color mb-2"><i class="fa-solid fa-users"></i></div>
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text fw-bold accent-color fs-3 fw-bold accent-color">
                                    <?php
                                    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
                                    $result = $stmt->fetch();
                                    echo $result['total'];
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon text-success mb-2"><i class="fa-solid fa-calendar-alt"></i></div>
                                <h5 class="card-title">Total Bookings</h5>
                                <p class="card-text fw-bold accent-color fs-3 fw-bold accent-color">
                                    <?php
                                    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM bookings");
                                    $result = $stmt->fetch();
                                    echo $result['total'];
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon text-warning mb-2"><i class="fa-solid fa-wallet"></i></div>
                                <h5 class="card-title">Revenue</h5>
                                <p class="card-text fw-bold accent-color fs-3 fw-bold accent-color">
                                    ₦
                                    <?php
                                    $stmt = $pdo->query("SELECT SUM(balance) AS total FROM wallets");
                                    $result = $stmt->fetch();
                                    echo $result['total'] ? number_format($result['total'], 2) : '0.00';
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>