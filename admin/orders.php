<?php
/**
 * Admin Orders Page
 * View and manage all orders
 */
require_once 'auth.php';
require_once '../config/database.php';

// Get all orders
$stmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Wonder Gasol Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar-minimal">
        <div class="navbar-container">
            <a href="../home_page.php">
                <img src="../images/WonderGasolLOGO.png" alt="Wonder Gasol" class="navbar-logo">
            </a>
            <ul class="navbar-menu">
                <li><a href="index.php" class="navbar-link">Products</a></li>
                <li><a href="orders.php" class="navbar-link active">Orders</a></li>
                <li><a href="logout.php" class="navbar-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Orders List -->
    <section class="section" style="padding-top: var(--space-16);">
        <div class="container">
            <h1 class="mb-8">Order Management</h1>

            <div class="card">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-light); text-align: left;">
                            <th style="padding: var(--space-3);">Order #</th>
                            <th style="padding: var(--space-3);">Customer</th>
                            <th style="padding: var(--space-3);">Phone</th>
                            <th style="padding: var(--space-3);">Total</th>
                            <th style="padding: var(--space-3);">Status</th>
                            <th style="padding: var(--space-3);">Date</th>
                            <th style="padding: var(--space-3);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" style="padding: var(--space-6); text-align: center; color: var(--text-secondary);">
                                No orders yet
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: var(--space-3);">
                                <strong><?php echo htmlspecialchars($order['order_number']); ?></strong>
                            </td>
                            <td style="padding: var(--space-3);"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td style="padding: var(--space-3);"><?php echo htmlspecialchars($order['customer_phone']); ?></td>
                            <td style="padding: var(--space-3);">₱<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td style="padding: var(--space-3);">
                                <span class="badge badge-primary">
                                    <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                                </span>
                            </td>
                            <td style="padding: var(--space-3);">
                                <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                            </td>
                            <td style="padding: var(--space-3);">
                                <a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">© 2025 Wonder Gasol. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
