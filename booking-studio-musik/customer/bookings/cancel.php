<?php
require_once '../../config/functions.php';
require_once '../../includes/auth-check.php';
if (!isCustomer()) redirect('/auth/login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    try {
        // Pastikan booking milik user yang login
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' 
                              WHERE id = ? AND user_id = ? AND status = 'pending'");
        $stmt->execute([$id, $_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            redirect('index.php?success=Booking cancelled');
        } else {
            redirect('index.php?error=Cannot cancel booking');
        }
    } catch (PDOException $e) {
        redirect('index.php?error=Error cancelling booking');
    }
} else {
    redirect('index.php');
}
?>