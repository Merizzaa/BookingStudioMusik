<?php
session_start();
require_once '../config/functions.php';

if (!isLoggedIn()) {
    redirect('/auth/login.php');
}

// Role-based access control
$currentPage = basename($_SERVER['PHP_SELF']);
$allowedPages = [
    'admin' => ['dashboard.php', 'studios/index.php', 'studios/add.php', 'studios/edit.php', 
               'bookings/index.php', 'bookings/view.php', 'users/index.php', 'users/add.php'],
    'staff' => ['bookings/index.php', 'bookings/view.php'],
    'customer' => ['bookings/index.php', 'bookings/create.php', 'bookings/view.php', 'profile.php']
];

$role = $_SESSION['role'];
if (!in_array($currentPage, $allowedPages[$role])) {
    die("Unauthorized access!");
}
?>