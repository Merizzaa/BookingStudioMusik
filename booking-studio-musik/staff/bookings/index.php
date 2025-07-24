<?php
require_once '../../config/functions.php';
require_once '../../includes/auth-check.php';
if (!isStaff()) redirect('/auth/login.php');

$bookings = $pdo->query("SELECT b.*, u.name as user_name, s.name as studio_name 
                         FROM bookings b
                         JOIN users u ON b.user_id = u.id
                         JOIN studios s ON b.studio_id = s.id
                         ORDER BY b.booking_date DESC")->fetchAll();

$title = "Manage Bookings";
include '../../includes/header.php';
?>

<div class="booking-list">
    <h1>Manage Bookings</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Studio</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?= $booking['id'] ?></td>
                <td><?= $booking['user_name'] ?></td>
                <td><?= $booking['studio_name'] ?></td>
                <td><?= formatDate($booking['booking_date']) ?></td>
                <td><?= formatTime($booking['start_time']) ?> - <?= formatTime($booking['end_time']) ?></td>
                <td><span class="status-badge <?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                <td class="actions">
                    <a href="view.php?id=<?= $booking['id'] ?>" class="btn btn-sm">View</a>
                    <form action="update-status.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>