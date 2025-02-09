-- Create the database
DROP DATABASE IF EXISTS guitar_shop;
CREATE DATABASE guitar_shop;
USE guitar_shop;

-- ========================================================
-- Table: users
-- ========================================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ========================================================
-- Table: guitars
-- ========================================================
CREATE TABLE guitars (
    guitar_id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    type VARCHAR(30) NOT NULL,  -- e.g., electric, acoustic
    price DECIMAL(10,2) NOT NULL,
    quantity_in_stock INT NOT NULL DEFAULT 0,
    description TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ========================================================
-- Table: orders
-- ========================================================
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ========================================================
-- Table: order_items
-- ========================================================
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    guitar_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_orderitems_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_orderitems_guitar
        FOREIGN KEY (guitar_id) REFERENCES guitars(guitar_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ========================================================
-- Dummy Data Inserts
-- ========================================================

-- Insert dummy guitars
INSERT INTO guitars (brand, model, type, price, quantity_in_stock, description)
VALUES
    ('Fender', 'Stratocaster', 'Electric', 1200.00, 10, 'A classic Fender Stratocaster with a versatile tone.'),
    ('Gibson', 'Les Paul', 'Electric', 2500.00, 5, 'The iconic Gibson Les Paul featuring a rich, full sound.'),
    ('Martin', 'D-28', 'Acoustic', 3000.00, 3, 'A top-of-the-line acoustic guitar with impeccable craftsmanship.');
