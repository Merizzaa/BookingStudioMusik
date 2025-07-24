<?php
require_once '../config/functions.php';
require_once '../includes/auth-check.php';
if (!isCustomer()) redirect('/auth/login.php');

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$_SESSION['user_id']]);
$user = $user->fetch();

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validasi email unik
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        $error = "Email already exists";
    } elseif (!empty($password) && $password !== $confirmPassword) {
        $error = "Passwords don't match";
    } else {
        try {
            // Jika password diisi, update password
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET 
                                      name = ?, email = ?, phone = ?, password = ?
                                      WHERE id = ?");
                $stmt->execute([$name, $email, $phone, $hashedPassword, $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET 
                                      name = ?, email = ?, phone = ?
                                      WHERE id = ?");
                $stmt->execute([$name, $email, $phone, $_SESSION['user_id']]);
            }
            
            $_SESSION['name'] = $name;
            $success = "Profile updated successfully";
        } catch (PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }
    }
}

$title = "My Profile";
include '../includes/header.php';
?>

<div class="profile-form">
    <h1>My Profile</h1>
    
    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="password">
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
        </div>
        <button type="submit" class="btn">Update Profile</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>