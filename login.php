<?php require __DIR__ . '/includes/main-header.php'; ?>

<body>
    <div class="container-fluid py-5 mt-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold accent-color">Welcome Back!</h1>
            <p class="lead">Login to your account to continue</p>
        </div>
        <div class="container d-flex align-items-center justify-content-center mt-5">
            <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%;">
                <form action="api/process-login.php" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="input-field" id="email" name="email" placeholder="you@email.com" required inputmode="email" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="input-field" id="password" name="password" placeholder="Enter your password" required minlength="6">
                    </div>
                    <button type="submit" class="btn primary-btn w-100">Login</button>
                    <div class="container text-center">
                  <a href="index.php" class="btn primary-btn w-20" style="float: left";> Go Back</a>
                </div>

                </form>
                <div class="text-center mt-3">
                    <span>Don't have an account?</span>
                    <a href="register.php" class="fw-bold">Register</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>