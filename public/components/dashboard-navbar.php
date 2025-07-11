<div class="dashboard-navbar d-flex justify-content-between align-items-center px-4 py-3 text-white">
    <!-- <h4 class="m-0">Welcome, <?php echo htmlspecialchars($user['user_name'] ?? 'User'); ?>!</h4> -->
    <span class="fw-bold">
        <?php
        if ($user['role'] == 0) {
            echo "Admin Dashboard";
        } else if ($user['role'] == 2) {
            echo "Client Dashboard";
        } elseif ($user['role'] == 1) {
            echo "Provider Dashboard";
        } else {
            echo "Welcome User!";
        }
        ?>
    </span>
    <div class="navbar-icons d-flex align-items-center">
        <!-- Deposit Icon -->
        <?php
        if ($user['role'] == 2) {
        ?>
            <a href="/servicehub/public/backend/deposit.php" class="text-white me-4" title="Deposit">
                <i class="fa-solid fa-wallet"></i>
            </a>
        <?php
        } else if ($user['role'] == 1) {
        ?>
            <a href="/servicehub/public/backend/withdraw.php" class="text-white me-4" title="Withdraw">
                <!-- withdraw icon -->
                <i class="fa-solid fa-money-bill-wave"></i>
            </a>
        <?php
        }
        ?>
        <!-- Notifications Icon -->
        <a href="/servicehub/public/backend/notifications.php" class="text-white me-4" title="Notifications">
            <i class="fa-solid fa-bell"></i>
        </a>
        <!-- Settings Icon -->
        <a href="/servicehub/public/backend/settings.php" class="text-white" title="Settings">
            <i class="fa-solid fa-cog"></i>
        </a>
    </div>
</div>