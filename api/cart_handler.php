<?php
/**
 * Cart Handler API
 * Handles add/update/remove cart operations
 */
require_once '../config/database.php';
require_once '../config/session.php';

// Set JSON header
header('Content-Type: application/json');

// Get action
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        // Add product to cart
        $productId = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        addToCart($productId, $quantity);
        
        // Check if request is from form or AJAX
        if (isset($_POST['redirect'])) {
            header('Location: ../' . $_POST['redirect']);
            exit;
        }
        echo json_encode(['success' => true, 'count' => getCartCount()]);
        break;
        
    case 'update':
        // Update cart quantity
        $productId = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        updateCartQuantity($productId, $quantity);
        
        // Redirect back to cart
        header('Location: ../cart.php');
        exit;
        break;
        
    case 'remove':
        // Remove product from cart
        $productId = $_POST['product_id'] ?? 0;
        
        removeFromCart($productId);
        
        // Redirect back to cart
        header('Location: ../cart.php');
        exit;
        break;
        
    case 'get':
        // Get cart items
        $cart = getCart();
        $items = [];
        
        foreach ($cart as $productId => $quantity) {
            $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            
            if ($product) {
                $items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $quantity
                ];
            }
        }
        
        echo json_encode(['success' => true, 'items' => $items, 'count' => getCartCount()]);
        break;
        
    case 'clear':
        // Clear entire cart
        clearCart();
        echo json_encode(['success' => true, 'count' => 0]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
