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

    <script>
        document.getElementById('deposit-btn').addEventListener('click', function() {
            const amount = document.getElementById('amount').value;

            if (!amount || amount < 100) {
                alert('Please enter a valid amount (minimum ₦100).');
                return;
            }

            // Send the deposit request to the backend
            fetch('/servicehub/api/process-deposit.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        amount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to the payment page
                        window.location.href = data.payment_link;
                    } else {
                        alert(data.message || 'An error occurred while processing your deposit.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your deposit.');
                });
        });
    </script>
</body>

</html>