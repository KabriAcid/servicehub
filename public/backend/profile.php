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
            <?php
            require_once __DIR__ . '/../components/navbar.php';
            ?>
            <div class="container-fluid">

            </div>
        </div>
    </div>
</body>

</html>