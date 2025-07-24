<?php
require_once '../../config/functions.php';
require_once '../../includes/auth-check.php';
if (!isCustomer()) redirect('/auth/login.php');

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT b.*, s.name as studio_name, s.description as studio_description
                      FROM bookings b
                      JOIN studios s ON b.studio_id = s.id
                      WHERE b.id = ? AND b.user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    redirect('index.php');
}

$title = "Booking Details";
include '../../includes/header.php';
?>

<div class="booking-details">
    <h1>Booking Details</h1>
    
    <div class="detail-card">
        <h2>Booking Information</h2>
        <div class="detail-row">
            <span class="detail-label">Booking ID:</span>
            <span class="detail-value"><?= $booking['id'] ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status:</span>
            <span class="detail-value status-badge <?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Studio:</span>
            <span class="detail-value"><?= $booking['studio_name'] ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Booking Date:</span>
            <span class="detail-value"><?= formatDate($booking['booking_date']) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Time:</span>
            <span class="detail-value"><?= formatTime($booking['start_time']) ?> - <?= formatTime($booking['end_time']) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Total Price:</span>
            <span class="detail-value">Rp <?= number_format($booking['total_price']) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Notes:</span>
            <span class="detail-value"><?= $booking['notes'] ? htmlspecialchars($booking['notes']) : 'No notes' ?></span>
        </div>
    </div>
    
    <div class="detail-card">
        <h2>Studio Information</h2>
        <div class="detail-row">
            <span class="detail-label">Description:</span>
            <span class="detail-value"><?= $booking['studio_description'] ?></span>
        </div>
    </div>
    
    <a href="index.php" class="btn">Back to My Bookings</a>
</div>

<?php include '../../includes/footer.php'; ?>