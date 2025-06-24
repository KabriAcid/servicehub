<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
?>

<?php require __DIR__ . '/../components/header.php'; ?>

<body>
    <div class="main-content">
        <?php
        require_once __DIR__ . '/../components/sidebar.php';
        ?>
        <div class="dashboard-body">
            <div class="dashboard-navbar">
                <h2 class="mb-4 text-white">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>!</h2>
            </div>
            <div class="container-fluid">

            </div>
        </div>
    </div>
</body>

</html>