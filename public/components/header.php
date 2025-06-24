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
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Font Awesome for icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="../assets/js/lottie-player.js"></script> -->

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="../assets/css/toasted.css" />
    <script src="../assets/js/toasted.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap">


    <link rel="stylesheet" href="../assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            font-family: 'Sora', sans-serif;
        }
    </style>

</head>

<body>

</body>

</html>