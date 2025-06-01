ALTER TABLE orders
ADD COLUMN user_id INT AFTER id,
ADD FOREIGN KEY (user_id) REFERENCES accounts(id);
