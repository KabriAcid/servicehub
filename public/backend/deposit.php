<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../../functions/utilities.php';
?>

<body>
    <div class="main-container">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="dashboard-body">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <div class="container py-5" style="max-width: 600px;">
                    <h2 class="text-center mb-4">Deposit Funds</h2>
                    <p class="text-center text-muted mb-4">
                        Add funds to your wallet securely to access premium services and manage your transactions effortlessly.
                    </p>
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger text-white text-center alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>
                    <form id="deposit-form">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₦)</label>
                            <input type="number" class="input-field" id="amount" name="amount" placeholder="Enter amount" required min="100">
                        </div>
                        <button type="button" id="deposit-btn" class="btn primary-btn w-100">Deposit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flutterwave JavaScript SDK -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        document.getElementById('deposit-btn').addEventListener('click', function() {
            const amount = document.getElementById('amount').value;

            if (!amount || amount < 100) {
                alert('Please enter a valid amount (minimum ₦100).');
                return;
            }

            // Initialize Flutterwave payment
            FlutterwaveCheckout({
                public_key: "<?php echo $_ENV['FLUTTERWAVE_PUBLIC_KEY']; ?>", // Replace with your public key
                tx_ref: "TX_" + Math.random().toString(36).substr(2, 9), // Unique transaction reference
                amount: amount,
                currency: "NGN",
                redirect_url: "<?php echo $_ENV['APP_BASE_URL']; ?>api/verify-deposit.php", // Redirect URL
                customer: {
                    email: "<?php echo $user['email']; ?>", // Using `$user` for the logged-in user's email
                    name: "<?php echo $user['name']; ?>" // Using `$user` for the logged-in user's name
                },
                payment_options: "card,banktransfer",
                onclose: function() {
                    alert("Payment process was closed.");
                },
                callback: function(data) {
                    console.log(data);
                    alert("Payment successful!");
                }
            });
        });
    </script>
</body>

</html>