<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? null;
// echo $role;
?>

<aside class="sidebar bg-light-purple">
    <nav class="d-flex flex-column align-items-center py-">
        <!-- Header -->
        <div class="header mb-4">
            <a href="dashboard.php" class="d-flex flex-column align-items-center text-decoration-none">
                <img src="/servicehub/public/favicon.png" alt="favicon" class="rounded-circle mb-2" width="50px" height="50px">
                <span class="text-dark lato-bold">ServiceHub</span>
            </a>
        </div>

        <!-- Navigation -->
        <ul class="nav flex-column text-center w-100">
            <li class="nav-item mb-3">
                <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="fa fa-home text-purple"></i>
                    <span class="d-block text-purple">Home</span>
                </a>
            </li>

            <?php if ($role === 'client') { ?>
                <!-- CLIENT MENU -->
                <li class="nav-item mb-3">
                    <a href="services.php" class="nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">
                        <i class="fa fa-concierge-bell text-purple"></i>
                        <span class="d-block text-purple">Services</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($role === 'provider') { ?>
                <!-- PROVIDER MENU -->
                <li class="nav-item mb-3">
                    <a href="services.php" class="nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">
                        <i class="fa fa-briefcase text-purple"></i>
                        <span class="d-block text-purple">My Services</span>
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="bookings.php" class="nav-link <?php echo ($current_page == 'bookings.php') ? 'active' : ''; ?>">
                        <i class="fa fa-calendar-check text-purple"></i>
                        <span class="d-block text-purple">My Jobs</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($role === 'admin') { ?>
                <!-- ADMIN MENU -->
                <li class="nav-item mb-3">
                    <a href="manage_app.php" class="nav-link <?php echo ($current_page == 'manage_app.php') ? 'active' : ''; ?>">
                        <i class="fa fa-cog text-purple"></i>
                        <span class="d-block text-purple">Manage App</span>
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="add_service.php" class="nav-link <?php echo ($current_page == 'add_service.php') ? 'active' : ''; ?>">
                        <i class="fa fa-plus-circle text-purple"></i>
                        <span class="d-block text-purple">Add Service</span>
                    </a>
                </li>
            <?php } ?>

            <!-- Common Links -->
            <li class="nav-item mb-3">
                <a href="profile.php" class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                    <i class="fa fa-user text-purple"></i>
                    <span class="d-block text-purple">Profile</span>
                </a>
            </li>

            <li class="nav-item mb-3">
                <a href="bookings.php" class="nav-link <?php echo ($current_page == 'bookings.php') ? 'active' : ''; ?>">
                    <i class="fa fa-calendar-alt text-purple"></i>
                    <span class="d-block text-purple">Bookings</span>
                </a>
            </li>
        </ul>

        <!-- Footer -->
        <div class="sidebar-footer mt-auto">
            <a href="/servicehub/logout.php" class="nav-link text-purple">
                <i class="fa fa-sign-out-alt text-center d-block"></i>
                <span class="d-block">Logout</span>
            </a>
        </div>

        <!-- Avatar -->
        <div class="avatar-section mt-4">
            <img src="/servicehub/public/assets/img/avatar.jpg" alt="User Avatar" class="rounded">
            <span class="status-indicator bg-success rounded-circle"></span>
        </div>
    </nav>
</aside>