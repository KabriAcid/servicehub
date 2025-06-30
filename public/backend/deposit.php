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
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
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

    <!-- Flutterwave CDN -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        document.getElementById('deposit-btn').addEventListener('click', function() {
            const amount = document.getElementById('amount').value;

            if (!amount || amount <= 0) {
                alert('Please enter a valid amount.');
                return;
            }

            FlutterwaveCheckout({
                public_key: "<?php echo $_ENV['FLUTTERWAVE_PUBLIC_KEY']; ?>",
                tx_ref: "TX_" + Math.random().toString(36).substring(2, 15),
                amount: amount,
                currency: "NGN",
                redirect_url: "<?php echo $_ENV['APP_BASE_URL']; ?>api/process-deposit.php",
                customer: {
                    email: "<?php echo $current_user['email']; ?>",
                    name: "<?php echo $current_user['name']; ?>"
                },
                customizations: {
                    title: "ServiceHub Wallet Deposit",
                    description: "Deposit funds into your ServiceHub wallet."
                },
                callback: function(data) {
                    // Handle successful payment
                    if (data.status === "successful") {
                        // Send AJAX request to update the database
                        fetch("<?php echo $_ENV['APP_BASE_URL']; ?>api/update-wallet.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    transaction_id: data.transaction_id,
                                    amount: data.amount
                                })
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    alert("Wallet funded successfully!");
                                    window.location.reload();
                                } else {
                                    alert("Failed to update wallet. Please contact support.");
                                }
                            })
                            .catch(error => {
                                console.error("Error updating wallet:", error);
                                alert("An error occurred. Please try again.");
                            });
                    } else {
                        alert("Payment failed. Please try again.");
                    }
                },
                onclose: function() {
                    alert("Payment window closed.");
                }
            });
        });
    </script>
</body>

</html>