<?php
/**
 * Shopping Cart Page
 * View and manage cart items
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Wonder Gasol</title>
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
                <li><a href="cart.php" class="navbar-link cart-badge active">
                    Cart
                    <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span>
                </a></li>
            </ul>
        </div>
    </nav>

    <!-- Cart Section -->
    <section class="section" style="padding-top: var(--space-16); min-height: 60vh;">
        <div class="container">
            <h1 class="mb-8">Shopping Cart</h1>
            
            <?php if (empty($cartItems)): ?>
            <div style="text-align: center; padding: var(--space-16) 0;">
                <p class="text-muted" style="font-size: 1.2rem;">Your cart is empty</p>
                <a href="product.php" class="btn btn-primary mt-6">Browse Products</a>
            </div>
            <?php else: ?>
            <div>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-8);">
                    <div>
                        <?php foreach ($cartItems as $item): ?>
                        <div class="card mb-4" style="display: flex; flex-direction: row; gap: var(--space-4);">
                            <img src="<?php echo htmlspecialchars($item['product']['image']); ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: var(--radius-md);">
                            <div style="flex: 1;">
                                <h4 style="margin-bottom: var(--space-2);"><?php echo htmlspecialchars($item['product']['name']); ?></h4>
                                <p style="color: var(--accent-primary); font-weight: 600; font-size: 1.1rem; margin-bottom: var(--space-3);">₱<?php echo number_format($item['product']['price'], 2); ?></p>
                                <div style="display: flex; gap: var(--space-3); align-items: center;">
                                    <form method="POST" action="api/cart_handler.php" style="display: inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo $item['quantity'] - 1; ?>">
                                        <button type="submit" class="btn btn-secondary">-</button>
                                    </form>
                                    <span style="font-weight: 600;"><?php echo $item['quantity']; ?></span>
                                    <form method="POST" action="api/cart_handler.php" style="display: inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                        <button type="submit" class="btn btn-secondary">+</button>
                                    </form>
                                    <form method="POST" action="api/cart_handler.php" style="display: inline; margin-left: auto;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                        <button type="submit" class="btn btn-secondary" style="background-color: #EF4444; color: white;">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div>
                        <div class="card">
                            <h3 class="card-title">Order Summary</h3>
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
                            <a href="checkout.php" class="btn btn-primary btn-lg" style="width: 100%;">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
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
