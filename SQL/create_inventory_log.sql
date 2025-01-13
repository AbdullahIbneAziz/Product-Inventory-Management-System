CREATE TABLE IF NOT EXISTS inventory_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50),
    action_type ENUM('update', 'add', 'remove') NOT NULL,
    quantity_change INT,
    price_change DECIMAL(10,2),
    action_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
); 