# Wonder Gasol - Installation Guide

## Overview
Wonder Gasol is a professional, minimalist e-commerce website for LPG (Gasul) products. Users can browse products, add to cart, checkout, and track orders. Includes an admin panel for product management.

## Features
- **Client Side**: Home, Products, Product Details, Cart, Checkout, Order Success, Order Tracking
- **Admin Side**: Product Management (Add/Edit/Delete), Order Management
- **Modern Design**: Clean, responsive, white background, dark text, blue accent color
- **Technologies**: PHP, MySQL, PDO, Sessions, HTML5, CSS3, JavaScript

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server
- Web browser (Chrome, Firefox, Safari, Edge)

## Installation Steps

### 1. Setup Database

1. Create a new MySQL database:
```sql
CREATE DATABASE wonder_gasol_db;
```

2. Import the database schema:
```bash
mysql -u root -p wonder_gasol_db < database.sql
```

Or import via phpMyAdmin:
- Open phpMyAdmin
- Select the `wonder_gasol_db` database
- Click "Import" tab
- Choose the `database.sql` file
- Click "Go"

### 2. Configure Database Connection

Edit `config/database.php` if needed (default settings):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'wonder_gasol_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Deploy Files

1. Copy all project files to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\wonder_gasol\`
   - **WAMP**: `C:\wamp\www\wonder_gasol\`
   - **MAMP**: `/Applications/MAMP/htdocs/wonder_gasol/`
   - **Linux**: `/var/www/html/wonder_gasol/`

2. Ensure proper file permissions (Linux/Mac):
```bash
chmod -R 755 /var/www/html/wonder_gasol
chmod -R 777 /var/www/html/wonder_gasol/config
```

### 4. Access the Website

1. Start your web server (Apache) and MySQL
2. Open your browser and navigate to:
   - **Customer Site**: `http://localhost/wonder_gasol/home_page.php`
   - **Admin Panel**: `http://localhost/wonder_gasol/admin/login.php`

### 5. Admin Login Credentials

Default admin credentials:
- **Username**: `admin`
- **Password**: `admin123`

**Important**: Change these credentials in production!

## File Structure

```
wonder_gasol/
├── config/
│   ├── database.php       # Database connection
│   └── session.php        # Session management
├── admin/
│   ├── login.php          # Admin login
│   ├── index.php          # Product management
│   ├── add_product.php    # Add new product
│   ├── edit_product.php   # Edit product
│   ├── orders.php         # View orders
│   ├── auth.php           # Authentication check
│   └── logout.php         # Logout
├── api/
│   ├── cart_handler.php   # Cart operations API
│   └── process_checkout.php # Checkout processing
├── css/
│   └── styles.css         # Main stylesheet
├── images/                # Product images
├── home_page.php          # Homepage
├── product.php            # Products listing
├── about_us.php           # About page
├── cart.php               # Shopping cart
├── checkout.php           # Checkout form
├── success.php            # Order success
├── order-tracking.php     # Track orders
├── scripts.js             # JavaScript functions
└── database.sql           # Database schema
```

## Usage Guide

### Customer Flow
1. Browse products on homepage or products page
2. Click "View Details" to see product information
3. Add products to cart
4. Proceed to checkout
5. Fill delivery information
6. Place order
7. View order confirmation
8. Track order status

### Admin Flow
1. Login at `/admin/login.php`
2. View all products in dashboard
3. Add new products with details
4. Edit existing products
5. Delete products
6. View all customer orders
7. Logout when done

## Database Tables

### products
- `id`: Primary key
- `name`: Product name
- `price`: Product price
- `image`: Image path
- `description`: Product description
- `availability`: In Stock / Out of Stock
- `delivery_eta`: Estimated delivery time

### orders
- `id`: Primary key
- `order_number`: Unique order identifier
- `customer_name`: Customer name
- `customer_phone`: Phone number
- `delivery_address`: Full address
- `barangay`: Barangay name
- `city`: City (default: Quezon City)
- `postal_code`: Postal code
- `payment_method`: cod / gcash
- `total_amount`: Total order amount
- `status`: confirmed / on_the_way / delivered / cancelled

### order_items
- `id`: Primary key
- `order_id`: Foreign key to orders
- `product_id`: Foreign key to products
- `product_name`: Product name snapshot
- `product_price`: Price at time of order
- `quantity`: Quantity ordered
- `subtotal`: Item subtotal

### admin_users
- `id`: Primary key
- `username`: Admin username
- `password`: Hashed password
- `email`: Admin email

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify database credentials in `config/database.php`
- Ensure database exists

### Session Errors
- Check PHP session is enabled
- Verify write permissions on session directory
- Check `session.save_path` in php.ini

### Admin Can't Login
- Verify admin user exists in database
- Check password hash matches
- Clear browser cookies and try again

### Cart Not Working
- Ensure sessions are working
- Check browser allows cookies
- Verify `config/session.php` is included

## Security Notes

1. **Change Default Admin Password**: First thing after installation!
2. **Use HTTPS**: In production, always use SSL/TLS
3. **Validate Input**: All user inputs are validated
4. **Prepared Statements**: PDO prepared statements prevent SQL injection
5. **Session Security**: Session data is server-side only
6. **XSS Protection**: All output is HTML-escaped

## Browser Compatibility

- Google Chrome (recommended)
- Mozilla Firefox
- Safari
- Microsoft Edge
- Opera

## Support

For issues or questions, refer to the code comments - every file is well-documented and beginner-friendly!

## License

© 2025 Wonder Gasol. All rights reserved.
