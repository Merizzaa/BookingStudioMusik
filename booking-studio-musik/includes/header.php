<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' | ' : '' ?>Studio Booking System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">StudioKu</a>
            <div class="nav-links">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="/admin/dashboard.php">Dashboard</a>
                        <a href="/admin/studios/index.php">Studios</a>
                        <a href="/admin/bookings/index.php">Bookings</a>
                        <a href="/admin/users/index.php">Users</a>
                    <?php elseif (isStaff()): ?>
                        <a href="/staff/bookings/index.php">Bookings</a>
                    <?php else: ?>
                        <a href="/customer/bookings/index.php">My Bookings</a>
                        <a href="/customer/bookings/create.php">New Booking</a>
                    <?php endif; ?>
                    <a href="/auth/logout.php">Logout</a>
                <?php else: ?>
                    <a href="/auth/login.php">Login</a>
                    <a href="/auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container">