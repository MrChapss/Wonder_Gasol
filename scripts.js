// Wonder Gasol - JavaScript Functions

// ===== CART MANAGEMENT =====
let cart = [];

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('wonderGasolCart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
    updateCartCount();
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('wonderGasolCart', JSON.stringify(cart));
    updateCartCount();
}

// Update cart count in navbar
function updateCartCount() {
    const cartCountElements = document.querySelectorAll('#cartCount');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCountElements.forEach(el => {
        el.textContent = totalItems;
        el.style.display = totalItems > 0 ? 'flex' : 'none';
    });
}

// ===== PRODUCT DATA =====
const products = [
    {
        id: 1,
        name: '11kg LPG Tank',
        price: 850,
        image: 'images/lpg.png',
        description: 'Perfect for home use. Ideal for small to medium-sized families.',
        availability: 'In Stock',
        deliveryEta: '1-2 hours'
    },
    {
        id: 2,
        name: '22kg LPG Tank',
        price: 1600,
        image: 'images/lpg.png',
        description: 'Most popular choice for households and small businesses.',
        availability: 'In Stock',
        deliveryEta: '1-2 hours'
    },
    {
        id: 3,
        name: '50kg LPG Tank',
        price: 3500,
        image: 'images/lpg.png',
        description: 'Industrial size, perfect for restaurants and large establishments.',
        availability: 'In Stock',
        deliveryEta: '2-3 hours'
    }
];

// ===== PRODUCT PAGE FUNCTIONS =====
let currentProduct = null;

// Load products on product page
function loadProducts() {
    const productList = document.getElementById('productList');
    if (!productList) return;

    productList.innerHTML = products.map(product => `
        <div class="product-card" onclick="openProductModal(${product.id})">
            <img src="${product.image}" alt="${product.name}" class="product-image">
            <div class="product-content">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-price">₱${product.price.toLocaleString()}</p>
                <p class="product-availability in-stock">${product.availability}</p>
                <button class="btn btn-primary" style="width: 100%;">View Details</button>
            </div>
        </div>
    `).join('');
}

// Open product detail modal
function openProductModal(productId) {
    currentProduct = products.find(p => p.id === productId);
    if (!currentProduct) return;

    document.getElementById('modalProductName').textContent = currentProduct.name;
    document.getElementById('modalProductImage').src = currentProduct.image;
    document.getElementById('modalProductImage').alt = currentProduct.name;
    document.getElementById('modalProductPrice').textContent = `₱${currentProduct.price.toLocaleString()}`;
    document.getElementById('modalProductDescription').textContent = currentProduct.description;
    document.getElementById('modalProductAvailability').textContent = currentProduct.availability;
    document.getElementById('modalDeliveryEta').textContent = currentProduct.delivery_eta || currentProduct.deliveryEta;
    document.getElementById('quantityInput').value = 1;
    
    // Set product ID in form
    const modalProductId = document.getElementById('modalProductId');
    if (modalProductId) {
        modalProductId.value = currentProduct.id;
    }
    
    document.getElementById('productModal').style.display = 'block';
}

// Close product modal
function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

// Quantity controls
function increaseQuantity() {
    const input = document.getElementById('quantityInput');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantityInput');
    const min = parseInt(input.min);
    const current = parseInt(input.value);
    if (current > min) {
        input.value = current - 1;
    }
}

// Add to cart
function addToCart() {
    if (!currentProduct) return;

    const quantity = parseInt(document.getElementById('quantityInput').value);
    const existingItem = cart.find(item => item.id === currentProduct.id);

    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: currentProduct.id,
            name: currentProduct.name,
            price: currentProduct.price,
            image: currentProduct.image,
            quantity: quantity
        });
    }

    saveCart();
    closeProductModal();
    alert(`${currentProduct.name} added to cart!`);
}

// ===== CART PAGE FUNCTIONS =====
function loadCartPage() {
    const emptyCart = document.getElementById('emptyCart');
    const cartContent = document.getElementById('cartContent');
    const cartItems = document.getElementById('cartItems');

    if (cart.length === 0) {
        emptyCart.style.display = 'block';
        cartContent.style.display = 'none';
        return;
    }

    emptyCart.style.display = 'none';
    cartContent.style.display = 'block';

    cartItems.innerHTML = cart.map(item => `
        <div class="card mb-4" style="display: flex; flex-direction: row; gap: var(--space-4);">
            <img src="${item.image}" alt="${item.name}" style="width: 120px; height: 120px; object-fit: cover; border-radius: var(--radius-md);">
            <div style="flex: 1;">
                <h4 style="margin-bottom: var(--space-2);">${item.name}</h4>
                <p style="color: var(--accent-primary); font-weight: 600; font-size: 1.1rem; margin-bottom: var(--space-3);">₱${item.price.toLocaleString()}</p>
                <div style="display: flex; gap: var(--space-3); align-items: center;">
                    <button onclick="updateCartQuantity(${item.id}, -1)" class="btn btn-secondary">-</button>
                    <span style="font-weight: 600;">${item.quantity}</span>
                    <button onclick="updateCartQuantity(${item.id}, 1)" class="btn btn-secondary">+</button>
                    <button onclick="removeFromCart(${item.id})" class="btn btn-secondary" style="margin-left: auto;">Remove</button>
                </div>
            </div>
        </div>
    `).join('');

    updateCartTotals();
}

// Update cart quantity
function updateCartQuantity(productId, change) {
    const item = cart.find(i => i.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            saveCart();
            loadCartPage();
        }
    }
}

// Remove item from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    loadCartPage();
}

// Update cart totals
function updateCartTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const deliveryFee = 50;
    const total = subtotal + deliveryFee;

    document.getElementById('subtotal').textContent = `₱${subtotal.toLocaleString()}`;
    document.getElementById('total').textContent = `₱${total.toLocaleString()}`;
}

// ===== CHECKOUT PAGE FUNCTIONS =====
function loadCheckoutPage() {
    const orderSummary = document.getElementById('orderSummary');
    
    if (cart.length === 0) {
        window.location.href = 'cart.html';
        return;
    }

    orderSummary.innerHTML = cart.map(item => `
        <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-3); padding-bottom: var(--space-3); border-bottom: 1px solid var(--border-light);">
            <div>
                <p style="margin: 0; font-weight: 600;">${item.name}</p>
                <p class="text-muted" style="margin: 0; font-size: 0.85rem;">Qty: ${item.quantity}</p>
            </div>
            <p style="margin: 0;">₱${(item.price * item.quantity).toLocaleString()}</p>
        </div>
    `).join('');

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const deliveryFee = 50;
    const total = subtotal + deliveryFee;

    document.getElementById('subtotal').textContent = `₱${subtotal.toLocaleString()}`;
    document.getElementById('total').textContent = `₱${total.toLocaleString()}`;
}

// Place order
function placeOrder() {
    const form = document.getElementById('checkoutForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Generate order ID
    const orderId = 'WG' + Date.now().toString().slice(-8);
    
    // Save order details
    const order = {
        id: orderId,
        date: new Date().toISOString(),
        items: cart,
        customer: {
            name: document.getElementById('fullName').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            barangay: document.getElementById('barangay').value,
            city: document.getElementById('city').value,
            postal: document.getElementById('postal').value,
            notes: document.getElementById('notes').value
        },
        payment: document.querySelector('input[name="payment"]:checked').value,
        status: 'confirmed'
    };

    localStorage.setItem('currentOrder', JSON.stringify(order));
    
    // Clear cart
    cart = [];
    saveCart();
    
    // Redirect to tracking page
    window.location.href = 'order-tracking.html';
}

// ===== MOBILE MENU =====
function toggleMobileMenu() {
    const menu = document.getElementById('navbarMenu');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        menu.classList.add('visible');
    } else {
        menu.classList.remove('visible');
        menu.classList.add('hidden');
    }
}

// ===== INITIALIZE =====
document.addEventListener('DOMContentLoaded', function() {
    loadCart();

    // Load appropriate page content
    const path = window.location.pathname;
    
    if (path.includes('product.html')) {
        loadProducts();
    } else if (path.includes('cart.html')) {
        loadCartPage();
    } else if (path.includes('checkout.html')) {
        loadCheckoutPage();
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (modal && event.target === modal) {
        closeProductModal();
    }
};
