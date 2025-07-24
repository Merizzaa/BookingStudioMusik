<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        
        if ($user['role'] === 'admin') {
            redirect('/admin/dashboard.php');
        } elseif ($user['role'] === 'staff') {
            redirect('/staff/bookings/index.php');
        } else {
            redirect('/customer/bookings/index.php');
        }
    } else {
        $error = "Email atau password salah";
    }
}
?>

<!-- HTML Form Login -->
<?php include '../includes/header.php'; ?>
<div class="login-form">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>