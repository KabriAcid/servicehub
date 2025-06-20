<?php require __DIR__ . '/includes/main-header.php'; ?>

<body>
    <?php require __DIR__ . '/includes/navbar.php'; ?>
    <div class="container-fluid py-5 mt-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Welcome to Our Service</h1>
            <p class="lead">Create your account to get started</p>
        </div>
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="card shadow-lg p-4" style="max-width: 800px; width: 100%;">
                <h3 class="text-center mb-4 fw-bold">Create Your Account</h3>
                <form action="api/process-register.php" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Jane Doe" required inputmode="text" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="you@email.com" required inputmode="email" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="08012345678" required inputmode="tel" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Register As</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Select role</option>
                            <option value="client">Client</option>
                            <option value="provider">Service Provider</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="City, State" required inputmode="text" maxlength="100">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <span>Already have an account?</span>
                    <a href="login.php" class="fw-bold">Login</a>
                </div>
            </div>
        </div>
</body>

</html>