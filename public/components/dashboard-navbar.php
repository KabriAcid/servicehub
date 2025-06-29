<div class="dashboard-navbar d-flex justify-content-between align-items-center px-4 py-3 bg-primary text-white">
    <h4 class="m-0">Welcome, <?php echo htmlspecialchars($user['user_name'] ?? 'User'); ?>!</h4>
    <div class="navbar-icons d-flex align-items-center">
        <!-- Deposit Icon -->
        <a href="/servicehub/wallet.php" class="text-white me-3" title="Deposit">
            <i class="fa-solid fa-wallet fs-5"></i>
        </a>
        <!-- Notifications Icon -->
        <a href="/servicehub/notifications.php" class="text-white me-3" title="Notifications">
            <i class="fa-solid fa-bell fs-5"></i>
        </a>
        <!-- Settings Icon -->
        <a href="/servicehub/settings.php" class="text-white" title="Settings">
            <i class="fa-solid fa-cog fs-5"></i>
        </a>
    </div>
</div>