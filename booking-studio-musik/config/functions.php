<?php
require_once 'database.php';

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Authentication functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isStaff() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
}

function isCustomer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

// Get all studios
function getStudios() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM studios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get user bookings
function getUserBookings($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT b.*, s.name as studio_name 
                          FROM bookings b
                          JOIN studios s ON b.studio_id = s.id
                          WHERE b.user_id = ?
                          ORDER BY b.booking_date DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Format date
function formatDate($date) {
    return date('d M Y', strtotime($date));
}

// Format time
function formatTime($time) {
    return date('H:i', strtotime($time));
}
?>