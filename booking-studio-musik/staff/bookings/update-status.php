<?php
require_once '../../config/functions.php';
require_once '../../includes/auth-check.php';
if (!isStaff()) redirect('/auth/login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        redirect('index.php?success=Booking status updated');
    } catch (PDOException $e) {
        redirect('index.php?error=Error updating booking status');
    }
} else {
    redirect('index.php');
}
?>