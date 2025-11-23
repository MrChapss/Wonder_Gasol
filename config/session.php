<?php
/**
 * Session Configuration
 * Handles session initialization for cart and user data
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Initialize cart in session if it doesn't exist
 */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/**
 * Add item to cart
 */
function addToCart($productId, $quantity = 1) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

/**
 * Remove item from cart
 */
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

/**
 * Update cart item quantity
 */
function updateCartQuantity($productId, $quantity) {
    if ($quantity <= 0) {
        removeFromCart($productId);
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

/**
 * Get cart items count
 */
function getCartCount() {
    return array_sum($_SESSION['cart']);
}

/**
 * Clear entire cart
 */
function clearCart() {
    $_SESSION['cart'] = [];
}

/**
 * Get cart items
 */
function getCart() {
    return $_SESSION['cart'];
}
?>
