<?php
/**
 * Admin Dashboard - Product Management
 * CRUD operations for products
 */
require_once 'auth.php';
require_once '../config/database.php';

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php');
    exit;
}

// Get all products
$stmt = $db->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wonder Gasol</title>
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
                <li><a href="index.php" class="navbar-link active">Products</a></li>
                <li><a href="orders.php" class="navbar-link">Orders</a></li>
                <li><a href="logout.php" class="navbar-link">Logout (<?php echo htmlspecialchars($_SESSION['admin_username']); ?>)</a></li>
            </ul>
        </div>
    </nav>

    <!-- Admin Dashboard -->
    <section class="section" style="padding-top: var(--space-16);">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-8);">
                <h1>Product Management</h1>
                <a href="add_product.php" class="btn btn-primary">+ Add New Product</a>
            </div>

            <!-- Products Table -->
            <div class="card">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-light); text-align: left;">
                            <th style="padding: var(--space-3);">ID</th>
                            <th style="padding: var(--space-3);">Name</th>
                            <th style="padding: var(--space-3);">Price</th>
                            <th style="padding: var(--space-3);">Availability</th>
                            <th style="padding: var(--space-3);">Delivery ETA</th>
                            <th style="padding: var(--space-3);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: var(--space-3);"><?php echo $product['id']; ?></td>
                            <td style="padding: var(--space-3);">
                                <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                            </td>
                            <td style="padding: var(--space-3);">₱<?php echo number_format($product['price'], 2); ?></td>
                            <td style="padding: var(--space-3);">
                                <span class="badge <?php echo $product['availability'] === 'In Stock' ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $product['availability']; ?>
                                </span>
                            </td>
                            <td style="padding: var(--space-3);"><?php echo htmlspecialchars($product['delivery_eta']); ?></td>
                            <td style="padding: var(--space-3);">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary" style="margin-right: var(--space-2);">Edit</a>
                                <a href="?delete=<?php echo $product['id']; ?>" 
                                   class="btn btn-secondary" 
                                   onclick="return confirm('Are you sure you want to delete this product?');"
                                   style="background-color: #EF4444; color: white;">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
