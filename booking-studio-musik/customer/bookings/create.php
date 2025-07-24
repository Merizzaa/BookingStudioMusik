<?php
require_once '../../config/functions.php';
require_once '../../includes/auth-check.php';
if (!isCustomer()) redirect('/auth/login.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studioId = $_POST['studio_id'];
    $bookingDate = $_POST['booking_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $notes = $_POST['notes'];
    
    // Validasi waktu
    if (strtotime($endTime) <= strtotime($startTime)) {
        $error = "End time must be after start time";
    } else {
        // Cek ketersediaan studio
        $stmt = $pdo->prepare("SELECT id FROM bookings 
                              WHERE studio_id = ? 
                              AND booking_date = ? 
                              AND ((start_time <= ? AND end_time >= ?) 
                              OR (start_time <= ? AND end_time >= ?))");
        $stmt->execute([$studioId, $bookingDate, $startTime, $startTime, $endTime, $endTime]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Studio is already booked for the selected time";
        } else {
            // Hitung harga
            $studio = $pdo->prepare("SELECT price_per_hour FROM studios WHERE id = ?");
            $studio->execute([$studioId]);
            $pricePerHour = $studio->fetchColumn();
            $hours = (strtotime($endTime) - strtotime($startTime)) / 3600;
            $totalPrice = $hours * $pricePerHour;
            
            // Simpan booking
            $stmt = $pdo->prepare("INSERT INTO bookings 
                                  (user_id, studio_id, booking_date, start_time, end_time, total_price, notes) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['user_id'],
                $studioId,
                $bookingDate,
                $startTime,
                $endTime,
                $totalPrice,
                $notes
            ]);
            
            redirect('index.php?success=Booking created successfully');
        }
    }
}

$studios = $pdo->query("SELECT * FROM studios WHERE status = 'available'")->fetchAll();

$title = "New Booking";
include '../../includes/header.php';
?>

<div class="booking-form">
    <h1>New Booking</h1>
    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Studio</label>
            <select name="studio_id" required>
                <?php foreach ($studios as $studio): ?>
                <option value="<?= $studio['id'] ?>">
                    <?= $studio['name'] ?> (Rp <?= number_format($studio['price_per_hour']) ?>/jam)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Booking Date</label>
            <input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Start Time</label>
            <input type="time" name="start_time" min="08:00" max="22:00" required>
        </div>
        
        <div class="form-group">
            <label>End Time</label>
            <input type="time" name="end_time" min="08:00" max="22:00" required>
        </div>
        
        <div class="form-group">
            <label>Notes (Optional)</label>
            <textarea name="notes" placeholder="Special requests..."></textarea>
        </div>
        
        <button type="submit" class="btn">Submit Booking</button>
        <a href="index.php" class="btn cancel">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>