<?php
// Mulai session dan set default timezone
session_start();
date_default_timezone_set('Asia/Jakarta');

// Define BASE_URL untuk path absolut
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['PHP_SELF']));

// Include file functions
require_once __DIR__ . '/config/functions.php';

$title = "Home";
$current_page = 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> | StudioKu</title>
    
    <!-- Load CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    
    <!-- Load Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Load Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Include Header -->
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Welcome to StudioKu</h1>
                <p>Book your favorite music studio with ease</p>
                <div class="cta-buttons">
                    <?php if (!isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>/auth/login.php" class="btn btn-primary">Login</a>
                        <a href="<?php echo BASE_URL; ?>/auth/register.php" class="btn btn-secondary">Register</a>
                    <?php else: ?>
                        <?php if (isCustomer()): ?>
                            <a href="<?php echo BASE_URL; ?>/customer/bookings/create.php" class="btn btn-primary">Book Now</a>
                        <?php endif; ?>
                        <a href="<?php echo isAdmin() ? BASE_URL.'/admin/dashboard.php' : (isStaff() ? BASE_URL.'/staff/bookings/index.php' : BASE_URL.'/customer/bookings/index.php'); ?>" 
                           class="btn btn-secondary">
                            Go to Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2>Why Choose Us?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-guitar"></i>
                        <h3>Quality Equipment</h3>
                        <p>State-of-the-art recording equipment for professional results</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-tag"></i>
                        <h3>Affordable Rates</h3>
                        <p>Competitive pricing with flexible booking options</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Easy Booking</h3>
                        <p>Simple online booking system available 24/7</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Include Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Load JavaScript -->
    <script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>
</body>
</html>