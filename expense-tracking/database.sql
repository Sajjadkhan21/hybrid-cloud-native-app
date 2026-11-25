CREATE DATABASE IF NOT EXISTS expense_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE expense_tracker;
CREATE TABLE IF NOT EXISTS transactions (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(120) NOT NULL,
 amount DECIMAL(12,2) NOT NULL,
 type ENUM('income','expense') NOT NULL,
 category VARCHAR(80) NOT NULL,
 transaction_date DATE NOT NULL,
 notes VARCHAR(500) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_date(transaction_date),
 INDEX idx_type_category(type,category)
) ENGINE=InnoDB;
INSERT INTO transactions(title,amount,type,category,transaction_date,notes) VALUES
('Monthly salary',85000,'income','Salary',CURDATE(),'Monthly income sample'),
('Grocery shopping',6800,'expense','Food & Dining','2026-11-03','Weekly groceries'),
('University transport',2400,'expense','Transport','2026-11-05','Commute'),
('Online course',3500,'expense','Education','2026-11-08','Learning resources'),
('Freelance website work',12000,'income','Freelance','2026-11-10','Project payment');
