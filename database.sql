CREATE DATABASE IF NOT EXISTS inventory_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventory_management;
CREATE TABLE IF NOT EXISTS products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 sku VARCHAR(80) NOT NULL UNIQUE,
 category VARCHAR(100) NOT NULL DEFAULT '',
 quantity INT NOT NULL DEFAULT 0,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 low_stock_threshold INT NOT NULL DEFAULT 5,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT IGNORE INTO products(name,sku,category,quantity,price,low_stock_threshold) VALUES
('Wireless Mouse','WM-001','Electronics',24,1200,5),
('Notebook','NB-002','Stationery',8,180,10),
('USB-C Cable','UC-003','Accessories',0,650,4);
