-- Wonder Gasol Database Schema
-- Simple and beginner-friendly database structure

-- Create database
CREATE DATABASE IF NOT EXISTS wonder_gasol_db;
USE wonder_gasol_db;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT 'images/lpg.png',
    description TEXT,
    availability ENUM('In Stock', 'Out of Stock') DEFAULT 'In Stock',
    delivery_eta VARCHAR(50) DEFAULT '1-2 hours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    delivery_address TEXT NOT NULL,
    barangay VARCHAR(100) NOT NULL,
    city VARCHAR(100) DEFAULT 'Quezon City',
    postal_code VARCHAR(10) NOT NULL,
    delivery_notes TEXT,
    payment_method ENUM('cod', 'gcash') DEFAULT 'cod',
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('confirmed', 'on_the_way', 'delivered', 'cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    product_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Admin users table (simple authentication)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default products
INSERT INTO products (name, price, description, availability, delivery_eta) VALUES
('11kg LPG Tank', 850.00, 'Perfect for home use. Ideal for small to medium-sized families.', 'In Stock', '1-2 hours'),
('22kg LPG Tank', 1600.00, 'Most popular choice for households and small businesses.', 'In Stock', '1-2 hours'),
('50kg LPG Tank', 3500.00, 'Industrial size, perfect for restaurants and large establishments.', 'In Stock', '2-3 hours');

-- Insert default admin user (username: admin, password: admin123)
-- Password is hashed using PHP password_hash()
INSERT INTO admin_users (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@wondergasol.com');
