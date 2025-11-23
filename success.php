<?php
/**
 * Order Success Page
 * Displays order confirmation after successful checkout
 */
require_once 'config/database.php';
require_once 'config/session.php';

// Get order number from session
$orderNumber = $_SESSION['last_order'] ?? null;

if (!$orderNumber) {
    header('Location: home_page.php');
    exit;
}

// Get order details
$stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ?");
$stmt->execute([$orderNumber]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: home_page.php');
    exit;
}

// Get order items
$stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order['id']]);
$orderItems = $stmt->fetchAll();

$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Wonder Gasol</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar-minimal">
        <div class="navbar-container">
            <a href="home_page.php">
                <img src="images/WonderGasolLOGO.png" alt="Wonder Gasol" class="navbar-logo">
            </a>
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="home_page.php" class="navbar-link">Home</a></li>
                <li><a href="product.php" class="navbar-link">Products</a></li>
                <li><a href="about_us.php" class="navbar-link">About</a></li>
                <li><a href="cart.php" class="navbar-link cart-badge">
                    Cart
                    <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span>
                </a></li>
            </ul>
        </div>
    </nav>

    <!-- Success Message -->
    <section class="section" style="padding-top: var(--space-16); min-height: 70vh;">
        <div class="container" style="max-width: 800px;">
            <!-- Success Icon -->
            <div style="text-align: center; margin-bottom: var(--space-8);">
                <div style="width: 80px; height: 80px; background-color: #10B981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-4); font-size: 2.5rem;">
                    ✓
                </div>
                <h1 style="color: var(--accent-primary); margin-bottom: var(--space-3);">Order Placed Successfully!</h1>
                <p class="text-muted" style="font-size: 1.1rem;">Thank you for your order. We're preparing your delivery.</p>
            </div>

            <!-- Order Details Card -->
            <div class="card mb-6">
                <h3 class="card-title">Order Information</h3>
                <div style="display: grid; gap: var(--space-4); margin-bottom: var(--space-4);">
                    <div>
                        <p class="text-muted" style="margin-bottom: var(--space-1); font-size: 0.85rem;">Order Number</p>
                        <p style="margin: 0; font-weight: 600; font-size: 1.1rem;"><?php echo htmlspecialchars($order['order_number']); ?></p>
                    </div>
                    <div>
                        <p class="text-muted" style="margin-bottom: var(--space-1); font-size: 0.85rem;">Delivery Address</p>
                        <p style="margin: 0;"><?php echo htmlspecialchars($order['delivery_address']); ?>, <?php echo htmlspecialchars($order['barangay']); ?>, <?php echo htmlspecialchars($order['city']); ?></p>
                    </div>
                    <div>
                        <p class="text-muted" style="margin-bottom: var(--space-1); font-size: 0.85rem;">Contact Number</p>
                        <p style="margin: 0;"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                    </div>
                    <div>
                        <p class="text-muted" style="margin-bottom: var(--space-1); font-size: 0.85rem;">Payment Method</p>
                        <p style="margin: 0;"><?php echo $order['payment_method'] === 'cod' ? 'Cash on Delivery' : 'GCash'; ?></p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-6">
                <h3 class="card-title">Order Items</h3>
                <?php foreach ($orderItems as $item): ?>
                <div style="display: flex; justify-content: space-between; padding: var(--space-3) 0; border-bottom: 1px solid var(--border-light);">
                    <div>
                        <p style="margin: 0; font-weight: 600;"><?php echo htmlspecialchars($item['product_name']); ?></p>
                        <p class="text-muted" style="margin: 0; font-size: 0.85rem;">Quantity: <?php echo $item['quantity']; ?> × ₱<?php echo number_format($item['product_price'], 2); ?></p>
                    </div>
                    <p style="margin: 0; font-weight: 600;">₱<?php echo number_format($item['subtotal'], 2); ?></p>
                </div>
                <?php endforeach; ?>
                <div style="display: flex; justify-content: space-between; padding-top: var(--space-4); margin-top: var(--space-3);">
                    <p style="margin: 0; font-weight: 700; font-size: 1.1rem;">Total Amount</p>
                    <p style="margin: 0; font-weight: 700; font-size: 1.1rem; color: var(--accent-primary);">₱<?php echo number_format($order['total_amount'], 2); ?></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: var(--space-4); justify-content: center;">
                <a href="order-tracking.php?order=<?php echo htmlspecialchars($order['order_number']); ?>" class="btn btn-primary btn-lg">Track Order</a>
                <a href="home_page.php" class="btn btn-secondary btn-lg">Back to Home</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">© 2025 Wonder Gasol. All rights reserved.</p>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
