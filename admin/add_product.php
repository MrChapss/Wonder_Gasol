<?php
/**
 * Add New Product Page
 * Form to create a new product
 */
require_once 'auth.php';
require_once '../config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $availability = $_POST['availability'];
    $delivery_eta = $_POST['delivery_eta'];
    $image = $_POST['image'] ?? 'images/lpg.png';
    
    // Insert new product
    $stmt = $db->prepare("INSERT INTO products (name, price, description, availability, delivery_eta, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $availability, $delivery_eta, $image]);
    
    // Redirect to dashboard
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Wonder Gasol Admin</title>
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
                <li><a href="orders.php" class="navbar-link">Orders</a></li>
                <li><a href="logout.php" class="navbar-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Add Product Form -->
    <section class="section" style="padding-top: var(--space-16);">
        <div class="container" style="max-width: 800px;">
            <h1 class="mb-8">Add New Product</h1>

            <div class="card">
                <form method="POST" action="">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-input" required placeholder="e.g., 11kg LPG Tank">
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label class="form-label">Price (₱)</label>
                        <input type="number" name="price" class="form-input" step="0.01" required placeholder="850.00">
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="4" required placeholder="Product description..."></textarea>
                    </div>

                    <!-- Availability -->
                    <div class="form-group">
                        <label class="form-label">Availability</label>
                        <select name="availability" class="form-input" required>
                            <option value="In Stock">In Stock</option>
                            <option value="Out of Stock">Out of Stock</option>
                        </select>
                    </div>

                    <!-- Delivery ETA -->
                    <div class="form-group">
                        <label class="form-label">Delivery ETA</label>
                        <input type="text" name="delivery_eta" class="form-input" required placeholder="1-2 hours">
                    </div>

                    <!-- Image Path -->
                    <div class="form-group">
                        <label class="form-label">Image Path</label>
                        <input type="text" name="image" class="form-input" value="images/lpg.png" placeholder="images/lpg.png">
                        <small class="text-muted">Enter the path to the product image (default: images/lpg.png)</small>
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: var(--space-3); justify-content: flex-end;">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
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
