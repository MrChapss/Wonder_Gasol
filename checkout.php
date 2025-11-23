<?php
/**
 * Checkout Page
 * Customer fills delivery info and places order
 */
require_once 'config/database.php';
require_once 'config/session.php';

// Get cart items
$cart = getCart();
$cartItems = [];
$subtotal = 0;

foreach ($cart as $productId => $quantity) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if ($product) {
        $itemTotal = $product['price'] * $quantity;
        $subtotal += $itemTotal;
        $cartItems[] = [
            'product' => $product,
            'quantity' => $quantity,
            'total' => $itemTotal
        ];
    }
}

$deliveryFee = 50;
$total = $subtotal + $deliveryFee;
$cartCount = getCartCount();

// Redirect if cart is empty
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Wonder Gasol</title>
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

    <!-- Checkout Section -->
    <section class="section" style="padding-top: var(--space-16);">
        <div class="container">
            <h1 class="mb-8">Checkout</h1>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-8);">
                <div>
                    <!-- Delivery Information -->
                    <div class="card mb-6">
                        <h3 class="card-title">Delivery Information</h3>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div style="background-color: #FEE2E2; color: #991B1B; padding: var(--space-3); border-radius: var(--radius-md); margin-bottom: var(--space-4);">
                                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <form id="checkoutForm" method="POST" action="api/process_checkout.php">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-input" placeholder="+63" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Delivery Address</label>
                                <input type="text" name="address" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Barangay</label>
                                <input type="text" name="barangay" class="form-input" required>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                                <div class="form-group">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-input" value="Quezon City" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" name="postal" class="form-input" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Delivery Notes (Optional)</label>
                                <textarea name="notes" class="form-input" rows="3" placeholder="e.g., landmarks, gate code"></textarea>
                            </div>

                        </form>
                    </div>

                    <!-- Payment Method -->
                    <div class="card">
                        <h3 class="card-title">Payment Method</h3>
                        <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                            <label style="display: flex; align-items: center; padding: var(--space-3); border: 1px solid var(--border-light); border-radius: var(--radius-md); cursor: pointer;">
                                <input type="radio" name="payment" value="cod" checked style="margin-right: var(--space-3);" form="checkoutForm">
                                <span>Cash on Delivery</span>
                            </label>
                            <label style="display: flex; align-items: center; padding: var(--space-3); border: 1px solid var(--border-light); border-radius: var(--radius-md); cursor: pointer;">
                                <input type="radio" name="payment" value="gcash" style="margin-right: var(--space-3);" form="checkoutForm">
                                <span>GCash</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="card">
                        <h3 class="card-title">Order Summary</h3>
                        <div style="margin-bottom: var(--space-4);">
                            <?php foreach ($cartItems as $item): ?>
                            <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-3); padding-bottom: var(--space-3); border-bottom: 1px solid var(--border-light);">
                                <div>
                                    <p style="margin: 0; font-weight: 600;"><?php echo htmlspecialchars($item['product']['name']); ?></p>
                                    <p class="text-muted" style="margin: 0; font-size: 0.85rem;">Qty: <?php echo $item['quantity']; ?></p>
                                </div>
                                <p style="margin: 0;">₱<?php echo number_format($item['total'], 2); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <div style="margin-bottom: var(--space-4);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-2);">
                                <span class="text-muted">Subtotal:</span>
                                <span>₱<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-2);">
                                <span class="text-muted">Delivery Fee:</span>
                                <span>₱50.00</span>
                            </div>
                            <hr>
                            <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700;">
                                <span>Total:</span>
                                <span style="color: var(--accent-primary);">₱<?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
                        <button type="submit" form="checkoutForm" class="btn btn-primary btn-lg" style="width: 100%;">Place Order</button>
                    </div>
                </div>
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
