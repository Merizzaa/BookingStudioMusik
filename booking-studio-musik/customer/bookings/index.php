<?php
require_once '../../config/functions.php';
require_once '../../includes/auth-check.php';
if (!isCustomer()) redirect('/auth/login.php');

$bookings = getUserBookings($_SESSION['user_id']);

$title = "My Bookings";
include '../../includes/header.php';
?>

<div class="booking-list">
    <h1>My Bookings</h1>
    <a href="create.php" class="btn">New Booking</a>
    
    <?php if (empty($bookings)): ?>
        <div class="alert info">You don't have any bookings yet.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Studio</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['studio_name'] ?></td>
                    <td><?= formatDate($booking['booking_date']) ?></td>
                    <td><?= formatTime($booking['start_time']) ?> - <?= formatTime($booking['end_time']) ?></td>
                    <td>Rp <?= number_format($booking['total_price']) ?></td>
                    <td><span class="status-badge <?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                    <td class="actions">
                        <a href="view.php?id=<?= $booking['id'] ?>" class="btn btn-sm">View</a>
                        <?php if ($booking['status'] === 'pending'): ?>
                            <form action="cancel.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                <button type="submit" class="btn btn-sm danger">Cancel</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>