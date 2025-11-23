<?php
/**
 * About Us Page
 * Information about Wonder Gasol company
 */
require_once 'config/session.php';
$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Wonder Gasol</title>
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
                <li><a href="about_us.php" class="navbar-link active">About</a></li>
                <li><a href="cart.php" class="navbar-link cart-badge">
                    Cart
                    <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span>
                </a></li>
            </ul>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="section" style="padding-top: var(--space-16);">
        <div class="container text-center">
            <h1 class="mb-6">About Wonder Gasol</h1>
            <p class="hero-subtitle">Trusted LPG delivery service serving Quezon City since 2020</p>
        </div>
    </section>

    <!-- Company Info Section -->
    <section class="section">
        <div class="container">
            <div class="grid grid-2" style="gap: var(--space-12);">
                <div class="card">
                    <h3 class="card-title">Our Company</h3>
                    <p class="card-text">Wonder Gasol is a Filipino home-grown company focused on delivery and providing quality, trusted gas cooking products to the whole Quezon City region.</p>
                </div>
                <div class="card">
                    <h3 class="card-title">Our Story</h3>
                    <p class="card-text">Starting in 2020, our goal has been to expand business throughout the Quezon City region and provide citizens and business owners with quality and fairly priced gas products.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Factory Image -->
    <section class="section" style="background-color: var(--bg-light); padding: var(--space-12) 0;">
        <div class="container">
            <img src="images/WGFactory.png" alt="Wonder Gasol Factory" style="width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-md);">
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="section">
        <div class="container">
            <div class="grid grid-2" style="gap: var(--space-12);">
                <div style="text-align: center; padding: var(--space-8);">
                    <h2 style="color: var(--accent-primary); margin-bottom: var(--space-4);">Mission</h2>
                    <p class="text-muted">To deliver quality and affordable gas to the Quezon City community and its population.</p>
                </div>
                <div style="text-align: center; padding: var(--space-8);">
                    <h2 style="color: var(--accent-primary); margin-bottom: var(--space-4);">Vision</h2>
                    <p class="text-muted">To be the #1 trusted and preferred gas delivery business in the whole Quezon City region, known for providing affordable, best-quality gas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Delivery Image -->
    <section class="section" style="padding-bottom: var(--space-16);">
        <div class="container">
            <img src="images/deliveringLPG.png" alt="Wonder Gasol Delivery" style="width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-md);">
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