<!-- filepath: c:\xampp\htdocs\servicehub\public\backend\book-service.php -->
<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';

// Fetch available services and providers
$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
$providers = $pdo->query("SELECT * FROM users WHERE role = 'provider'")->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="container py-5" style="max-width: 600px;">
                    <h2 class="text-center mb-4">Book a Service</h2>
                    <form action="/servicehub/api/process-booking.php" method="POST">
                        <div class="mb-3">
                            <label for="service" class="form-label">Select Service</label>
                            <select class="form-select" id="service" name="service_id" required>
                                <option value="" disabled selected>Select a service</option>
                                <?php foreach ($services as $service) { ?>
                                    <option value="<?php echo $service['id']; ?>">
                                        <?php echo htmlspecialchars($service['name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="provider" class="form-label">Select Provider</label>
                            <select class="form-select" id="provider" name="provider_id" required>
                                <option value="" disabled selected>Select a provider</option>
                                <?php foreach ($providers as $provider) { ?>
                                    <option value="<?php echo $provider['id']; ?>">
                                        <?php echo htmlspecialchars($provider['name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Schedule Date</label>
                            <input type="datetime-local" class="form-control" id="date" name="scheduled_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Book Service</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>