<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';
?>

<body>
    <div class="main-container">
        <?php
        require_once __DIR__ . '/../components/sidebar.php';
        ?>
        <div class="dashboard-body">
            <?php # require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <div class="row g-4 my-3">
                    <?php if ($user['role'] == 'client') { ?>
                        <!-- Customer Dashboard -->
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
                    <?php } elseif ($user['role'] == 'provider') { ?>
                        <!-- Provider Dashboard -->
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon text-primary mb-2"><i class="fa-solid fa-briefcase"></i></div>
                                <h5 class="card-title">Jobs Completed</h5>
                                <p class="card-text fs-4">--</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon text-success mb-2"><i class="fa-solid fa-wallet"></i></div>
                                <h5 class="card-title">Earnings</h5>
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
                    <?php } elseif ($user['role'] == 'admin') { ?>
                        <!-- Admin Dashboard -->
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon text-primary mb-2"><i class="fa-solid fa-users"></i></div>
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text fs-4">--</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon text-success mb-2"><i class="fa-solid fa-calendar-alt"></i></div>
                                <h5 class="card-title">Total Bookings</h5>
                                <p class="card-text fs-4">--</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow text-center p-4">
                                <div class="card-icon text-warning mb-2"><i class="fa-solid fa-wallet"></i></div>
                                <h5 class="card-title">Revenue</h5>
                                <p class="card-text fs-4">₦ --</p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>