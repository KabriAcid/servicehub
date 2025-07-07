<?php
function set_title(string $title = 'ServiceHub')
{
    if (isset($title)) {
        return $title;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically -->
    <title><?= set_title('ServiceHub' ?? null) ?></title>
    <link rel="shortcut icon" href="/servicehub/public/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="/servicehub/public/assets/js/lottie-player.js"></script> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="/servicehub/public/assets/css/toasted.css" />
    <script src="/servicehub/public/assets/js/toasted.js"></script>


    <link rel="stylesheet" href="/servicehub/public/assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="/servicehub/public/assets/css/style.css">

    <style>
        body {
            font-family: 'Lato', sans-serif;
        }
    </style>

</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">Service<span class="accent-color">Hub</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Track</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                </ul>
                <div class="d-flex ms-3">
                    <a class="btn secondary-btn me-2" href="login.php">Log In</a>
                    <a class="btn primary-btn" href="register.php">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero d-flex align-items-center">
        <div class="container hero-content text-center">
            <h1 class="display-1 fw-bold text-white">Effortless Services,<br>Every Step of the Way.</h1>
            <p class="lead text-white">Find trusted service providers near you and manage your bookings from one platform.</p>
            <div class="mt-4">
                <a class="btn primary-btn me-2" href="#">Get a Free Quote</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Three Main <span class="accent-color">Services</span></h2>
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card-service h-100 p-4 shadow-sm rounded">
                        <div class="mb-3 text-accent fs-1">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h5 class="fw-semibold">On-Demand Professionals</h5>
                        <p>Hire verified providers near you in seconds and get the job done.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-service h-100 p-4 shadow-sm rounded">
                        <div class="mb-3 text-accent fs-1">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h5 class="fw-semibold">Live Booking Updates</h5>
                        <p>Track booking status in real time and manage your active jobs easily.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-service h-100 p-4 shadow-sm rounded">
                        <div class="mb-3 text-accent fs-1">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="fw-semibold">Insights and Reviews</h5>
                        <p>Make decisions using verified user reviews and performance ratings.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Quote Section -->
    <section class="py-5 bg-light">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <div class="quote-box mb-4 mb-lg-0">
                <h5 class="fw-bold">Your Comfort, Our Top Priority</h5>
                <p>We ensure every provider on ServiceHub is pre-screened, reviewed, and ready to deliver top-notch service in your area.</p>
            </div>
            <img src="https://via.placeholder.com/220x120" alt="Service Vehicle" class="img-fluid rounded shadow">
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 text-center">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h4 class="fw-bold">15,000+</h4>
                    <p>Jobs Completed</p>
                </div>
                <div class="col">
                    <h4 class="fw-bold">3,000+</h4>
                    <p>Verified Professionals</p>
                </div>
                <div class="col">
                    <h4 class="fw-bold">99%</h4>
                    <p>Positive Reviews</p>
                </div>
                <div class="col">
                    <h4 class="fw-bold">250+</h4>
                    <p>Cities Served</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold">Ready to Transform Your Service Experience?</h4>
                <p>Join thousands who’ve simplified their home and office tasks with ServiceHub.</p>
            </div>
            <a class="btn primary-btn" href="#">Get a Free Quote</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5>ServiceHub</h5>
                    <p>We connect you to verified professionals for all your service needs.</p>
                </div>
                <div class="col-md-3">
                    <h6>Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Getting Started</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Help Articles</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Home Cleaning</a></li>
                        <li><a href="#">Electricians</a></li>
                        <li><a href="#">Plumbing</a></li>
                        <li><a href="#">Legal Advice</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-light">
            <p class="text-center">&copy; ServiceHub 2024. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>