<?php
/**
 * Edit Product Page
 * Form to update an existing product
 */
require_once 'auth.php';
require_once '../config/database.php';

// Get product ID
$id = $_GET['id'] ?? 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $availability = $_POST['availability'];
    $delivery_eta = $_POST['delivery_eta'];
    $image = $_POST['image'];
    
    // Update product
    $stmt = $db->prepare("UPDATE products SET name = ?, price = ?, description = ?, availability = ?, delivery_eta = ?, image = ? WHERE id = ?");
    $stmt->execute([$name, $price, $description, $availability, $delivery_eta, $image, $id]);
    
    // Redirect to dashboard
    header('Location: index.php');
    exit;
}

// Get product details
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Wonder Gasol Admin</title>
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

    <!-- Edit Product Form -->
    <section class="section" style="padding-top: var(--space-16);">
        <div class="container" style="max-width: 800px;">
            <h1 class="mb-8">Edit Product</h1>

            <div class="card">
                <form method="POST" action="">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-input" required value="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label class="form-label">Price (₱)</label>
                        <input type="number" name="price" class="form-input" step="0.01" required value="<?php echo $product['price']; ?>">
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <!-- Availability -->
                    <div class="form-group">
                        <label class="form-label">Availability</label>
                        <select name="availability" class="form-input" required>
                            <option value="In Stock" <?php echo $product['availability'] === 'In Stock' ? 'selected' : ''; ?>>In Stock</option>
                            <option value="Out of Stock" <?php echo $product['availability'] === 'Out of Stock' ? 'selected' : ''; ?>>Out of Stock</option>
                        </select>
                    </div>

                    <!-- Delivery ETA -->
                    <div class="form-group">
                        <label class="form-label">Delivery ETA</label>
                        <input type="text" name="delivery_eta" class="form-input" required value="<?php echo htmlspecialchars($product['delivery_eta']); ?>">
                    </div>

                    <!-- Image Path -->
                    <div class="form-group">
                        <label class="form-label">Image Path</label>
                        <input type="text" name="image" class="form-input" value="<?php echo htmlspecialchars($product['image']); ?>">
                        <small class="text-muted">Enter the path to the product image</small>
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: var(--space-3); justify-content: flex-end;">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
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
