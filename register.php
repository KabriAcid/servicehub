<?php require __DIR__ . '/includes/main-header.php'; ?>

<body>
    <?php require __DIR__ . '/includes/navbar.php'; ?>
    <div class="container-fluid py-5 mt-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold accent-color">Welcome to Our Service</h1>
            <p class="lead">Create your account to get started</p>
        </div>
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="card shadow-lg p-4" style="max-width: 800px; width: 100%;">
                <!-- Alert Section -->
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
                <form action="api/process-register.php" method="POST" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="input-field form-control" id="name" name="name" placeholder="Jane Doe" required inputmode="text" maxlength="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="input-field form-control" id="email" name="email" placeholder="you@email.com" required inputmode="email" maxlength="100">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="input-field form-control" id="phone" name="phone" placeholder="08012345678" required inputmode="tel" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="input-field form-control" id="location" name="location" placeholder="City, State" required inputmode="text" maxlength="100">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="input-field form-control" id="password" name="password" placeholder="Create a password" required minlength="6">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="input-field form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required minlength="6">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Register As</label>
                            <select class="input-field form-select" id="role" name="role" required>
                                <option value="" disabled selected>Select role</option>
                                <option value="client">Client</option>
                                <option value="provider">Service Provider</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn primary-btn w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <span>Already have an account?</span>
                    <a href="login.php" class="fw-bold">Login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>