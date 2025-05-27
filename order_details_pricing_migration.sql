-- =====================================================
-- ORDER DETAILS PRICING STRUCTURE MIGRATION
-- =====================================================
-- This file contains SQL statements to migrate the order_details table
-- from storing unit prices to storing total prices (unit_price * quantity)
--
-- IMPORTANT: Backup your database before running these statements!
-- =====================================================

-- 1. DATABASE SCHEMA CLARIFICATION
-- =====================================================
-- The order_details table structure remains the same, but the meaning of the 'price' column changes:
-- 
-- OLD STRUCTURE:
-- - price column = unit price of the product
-- - total calculation = price * quantity (calculated dynamically)
--
-- NEW STRUCTURE:
-- - price column = total amount for this line item (unit_price * quantity)
-- - total calculation = SUM(price) from all order_details for an order

-- Current table structure (no changes needed):
-- CREATE TABLE order_details (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     order_id INT NOT NULL,
--     product_id INT NOT NULL,
--     quantity INT NOT NULL,
--     price DECIMAL(10, 2) NOT NULL,  -- NOW STORES TOTAL AMOUNT INSTEAD OF UNIT PRICE
--     FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
-- );

-- 2. DATA MIGRATION SCRIPT
-- =====================================================
-- This script converts existing order_details from unit price to total price structure

-- Step 1: Create a backup table (RECOMMENDED)
CREATE TABLE order_details_backup AS SELECT * FROM order_details;

-- Step 2: Add a temporary column to track migration status
ALTER TABLE order_details ADD COLUMN migration_status ENUM('old', 'new') DEFAULT 'old';

-- Step 3: Update existing records to use total price structure
-- This converts unit prices to total prices by multiplying price * quantity
UPDATE order_details 
SET 
    price = price * quantity,
    migration_status = 'new'
WHERE migration_status = 'old';

-- Step 4: Verify the migration
-- Check that all records have been migrated
SELECT 
    migration_status,
    COUNT(*) as count
FROM order_details 
GROUP BY migration_status;

-- Step 5: Remove the temporary migration status column (after verification)
-- ALTER TABLE order_details DROP COLUMN migration_status;

-- 3. VERIFICATION QUERIES
-- =====================================================

-- Query to check if migration was successful
-- Compare totals before and after migration
SELECT 
    od.order_id,
    od.product_id,
    od.quantity,
    od.price as total_price,
    (od.price / od.quantity) as calculated_unit_price,
    p.price as current_product_price
FROM order_details od
LEFT JOIN product p ON od.product_id = p.id
LIMIT 10;

-- Query to verify order totals are calculated correctly
SELECT 
    o.id as order_id,
    o.name as customer_name,
    SUM(od.price) as order_total,
    COUNT(od.id) as item_count
FROM orders o
LEFT JOIN order_details od ON o.id = od.order_id
GROUP BY o.id
ORDER BY o.created_at DESC
LIMIT 10;

-- 4. ROLLBACK SCRIPT (if needed)
-- =====================================================
-- Use this only if you need to rollback the migration

-- Restore from backup (if backup table exists)
-- TRUNCATE TABLE order_details;
-- INSERT INTO order_details SELECT id, order_id, product_id, quantity, price FROM order_details_backup;

-- Or manually convert back to unit prices (if you know the original structure)
-- UPDATE order_details 
-- SET price = price / quantity 
-- WHERE quantity > 0;

-- 5. POST-MIGRATION CLEANUP
-- =====================================================
-- After successful migration and verification, clean up backup table
-- DROP TABLE order_details_backup;

-- 6. TESTING QUERIES
-- =====================================================
-- Test order creation with new structure
-- These queries simulate how the new system will work

-- Example: Calculate order total using new structure
SELECT 
    order_id,
    SUM(price) as total_amount
FROM order_details 
WHERE order_id = 1
GROUP BY order_id;

-- Example: Get order details with unit prices calculated from totals
SELECT 
    od.id,
    od.order_id,
    od.product_id,
    p.name as product_name,
    od.quantity,
    od.price as total_price,
    (od.price / od.quantity) as unit_price,
    p.price as current_product_price
FROM order_details od
LEFT JOIN product p ON od.product_id = p.id
WHERE od.order_id = 1;

-- 7. PERFORMANCE CONSIDERATIONS
-- =====================================================
-- The new structure should improve performance for order total calculations
-- Old way: SELECT SUM(price * quantity) FROM order_details WHERE order_id = ?
-- New way: SELECT SUM(price) FROM order_details WHERE order_id = ?

-- Consider adding an index on order_id if not already present
-- CREATE INDEX idx_order_details_order_id ON order_details(order_id);

-- 8. APPLICATION CODE CHANGES SUMMARY
-- =====================================================
-- The following changes have been made to the OrderModel.php:
-- 
-- 1. createOrderDetails() - Now stores total price (unit_price * quantity)
-- 2. getOrderById() - Updated to handle price as total amount
-- 3. calculateOrderTotal() - Changed from SUM(price * quantity) to SUM(price)
-- 4. getOrderStatistics() - Updated revenue calculations
-- 5. Added migration and helper methods
--
-- Views that display pricing information will automatically work with the new structure
-- because the OrderModel now provides both total_price and calculated unit_price
