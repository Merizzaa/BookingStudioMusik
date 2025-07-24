<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? '/admin/dashboard.php' : (isStaff() ? '/staff/bookings/index.php' : '/customer/bookings/index.php'));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    
    if ($password !== $confirmPassword) {
        $error = "Passwords don't match";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email already exists";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'customer')");
            $stmt->execute([$name, $email, $hashedPassword, $phone]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role'] = 'customer';
            $_SESSION['name'] = $name;
            
            redirect('/customer/bookings/index.php');
        }
    }
}

$title = "Register";
include '../includes/header.php';
?>

<div class="auth-form">
    <h1>Register</h1>
    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="/auth/login.php">Login here</a></p>
</div>

<?php include '../includes/footer.php'; ?>