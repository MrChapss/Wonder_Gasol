<?php
/**
 * Admin Login Page
 * Simple authentication for admin access
 */
require_once '../config/database.php';
require_once '../config/session.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Query database for admin user
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    // Verify password
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Wonder Gasol</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <section class="section" style="padding-top: var(--space-16); min-height: 100vh; display: flex; align-items: center; justify-content: center; background-color: var(--bg-light);">
        <div class="card" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-6">Admin Login</h2>
            
            <?php if (isset($error)): ?>
                <div style="background-color: #FEE2E2; color: #991B1B; padding: var(--space-3); border-radius: var(--radius-md); margin-bottom: var(--space-4);">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Login</button>
            </form>
            
            <p class="text-center text-muted mt-4" style="font-size: 0.85rem;">
                Default credentials: admin / admin123
            </p>
        </div>
    </section>
</body>
</html>
