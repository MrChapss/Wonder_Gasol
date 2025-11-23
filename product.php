<?php
/**
 * Products Page
 * Displays all products from database
 */
require_once 'config/database.php';
require_once 'config/session.php';

// Get all products from database
$stmt = $db->query("SELECT * FROM products ORDER BY id ASC");
$products = $stmt->fetchAll();

// Get cart count
$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Wonder Gasol</title>
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
                <li><a href="product.php" class="navbar-link active">Products</a></li>
                <li><a href="about_us.php" class="navbar-link">About</a></li>
                <li><a href="cart.php" class="navbar-link cart-badge">
                    Cart
                    <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span>
                </a></li>
            </ul>
        </div>
    </nav>

    <!-- Products Section -->
    <section class="section">
        <div class="container">
            <h1 class="text-center mb-8">Our Products</h1>
            <div class="grid grid-3" id="productList">
                <?php foreach ($products as $product): ?>
                <div class="product-card" onclick="openProductModal(<?php echo $product['id']; ?>)">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    <div class="product-content">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price">₱<?php echo number_format($product['price'], 2); ?></p>
                        <p class="product-availability <?php echo $product['availability'] === 'In Stock' ? 'in-stock' : 'out-of-stock'; ?>">
                            <?php echo $product['availability']; ?>
                        </p>
                        <button class="btn btn-primary" style="width: 100%;">View Details</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Hidden product data for JavaScript -->
    <script>
    const products = <?php echo json_encode($products); ?>;
    </script>

    <!-- Product Detail Modal -->
    <div id="productModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 2000; padding: var(--space-6); overflow-y: auto;">
        <div style="max-width: 800px; margin: var(--space-8) auto; background-color: var(--bg-white); border-radius: var(--radius-md); padding: var(--space-8);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6);">
                <h2 id="modalProductName">Product Name</h2>
                <button onclick="closeProductModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary);">&times;</button>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-8);">
                <div>
                    <img id="modalProductImage" src="" alt="" style="width: 100%; border-radius: var(--radius-md);">
                </div>
                <div>
                    <p id="modalProductPrice" style="font-size: 2rem; font-weight: 700; color: var(--accent-primary); margin-bottom: var(--space-4);"></p>
                    <p id="modalProductDescription" style="color: var(--text-secondary); margin-bottom: var(--space-6);"></p>
                    <p id="modalProductAvailability" class="badge badge-success" style="margin-bottom: var(--space-6);"></p>
                    <form method="POST" action="api/cart_handler.php" id="addToCartForm">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" id="modalProductId">
                        <input type="hidden" name="redirect" value="product.php">
                        <div style="margin-bottom: var(--space-6);">
                            <label class="form-label">Quantity</label>
                            <div style="display: flex; gap: var(--space-3); align-items: center;">
                                <button type="button" onclick="decreaseQuantity()" class="btn btn-secondary">-</button>
                                <input type="number" name="quantity" id="quantityInput" value="1" min="1" max="10" style="width: 60px; text-align: center; padding: var(--space-2); border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                                <button type="button" onclick="increaseQuantity()" class="btn btn-secondary">+</button>
                            </div>
                        </div>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: var(--space-6);">
                            <strong>Estimated Delivery:</strong> <span id="modalDeliveryEta">1-2 hours</span>
                        </p>
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">© 2025 Wonder Gasol. All rights reserved.</p>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>