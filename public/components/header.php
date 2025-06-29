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
    <!-- <script src="/servicehub/public/assets/js/lottie-player.js"></script> -->

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="/servicehub/public/assets/css/toasted.css" />
    <script src="/servicehub/public/assets/js/toasted.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap">


    <link rel="stylesheet" href="/servicehub/public/assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="/servicehub/public/assets/css/style.css">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Lato', sans-serif;
        }
    </style>

</head>