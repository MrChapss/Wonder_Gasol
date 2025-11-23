<?php
/**
 * Process Checkout
 * Handles order creation from checkout form
 */
require_once '../config/database.php';
require_once '../config/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../checkout.php');
    exit;
}

// Get form data
$customerName = $_POST['full_name'] ?? '';
$customerPhone = $_POST['phone'] ?? '';
$deliveryAddress = $_POST['address'] ?? '';
$barangay = $_POST['barangay'] ?? '';
$city = $_POST['city'] ?? 'Quezon City';
$postalCode = $_POST['postal'] ?? '';
$deliveryNotes = $_POST['notes'] ?? '';
$paymentMethod = $_POST['payment'] ?? 'cod';

// Validate required fields
if (empty($customerName) || empty($customerPhone) || empty($deliveryAddress) || empty($barangay) || empty($postalCode)) {
    $_SESSION['error'] = 'Please fill in all required fields';
    header('Location: ../checkout.php');
    exit;
}

// Get cart items
$cart = getCart();
if (empty($cart)) {
    $_SESSION['error'] = 'Your cart is empty';
    header('Location: ../cart.php');
    exit;
}

// Calculate total
$subtotal = 0;
$orderItems = [];

foreach ($cart as $productId => $quantity) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if ($product) {
        $itemSubtotal = $product['price'] * $quantity;
        $subtotal += $itemSubtotal;
        
        $orderItems[] = [
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'product_price' => $product['price'],
            'quantity' => $quantity,
            'subtotal' => $itemSubtotal
        ];
    }
}

$deliveryFee = 50;
$totalAmount = $subtotal + $deliveryFee;

// Generate unique order number
$orderNumber = 'WG' . date('Ymd') . rand(1000, 9999);

try {
    // Begin transaction
    $db->beginTransaction();
    
    // Insert order
    $stmt = $db->prepare("INSERT INTO orders (order_number, customer_name, customer_phone, delivery_address, barangay, city, postal_code, delivery_notes, payment_method, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')");
    $stmt->execute([$orderNumber, $customerName, $customerPhone, $deliveryAddress, $barangay, $city, $postalCode, $deliveryNotes, $paymentMethod, $totalAmount]);
    
    $orderId = $db->lastInsertId();
    
    // Insert order items
    $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($orderItems as $item) {
        $stmt->execute([
            $orderId,
            $item['product_id'],
            $item['product_name'],
            $item['product_price'],
            $item['quantity'],
            $item['subtotal']
        ]);
    }
    
    // Commit transaction
    $db->commit();
    
    // Clear cart and save order number in session
    clearCart();
    $_SESSION['last_order'] = $orderNumber;
    
    // Redirect to success page
    header('Location: ../success.php');
    exit;
    
} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();
    $_SESSION['error'] = 'Failed to process order. Please try again.';
    header('Location: ../checkout.php');
    exit;
}
?>
