<?php
/**
 * Test script for Order Details Pricing Migration
 * This script tests the new pricing structure implementation
 */

require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$orderModel = new OrderModel($db);

echo "<h1>Order Details Pricing Migration Test</h1>\n";
echo "<hr>\n";

// Test 1: Check if using new pricing structure
echo "<h2>Test 1: Check Pricing Structure</h2>\n";
$isNewStructure = $orderModel->isUsingNewPricingStructure();
echo "Using new pricing structure: " . ($isNewStructure ? "YES" : "NO") . "<br>\n";

// Test 2: Create a test order with new structure
echo "<h2>Test 2: Create Test Order</h2>\n";

// Sample cart items (simulating cart structure)
$testCartItems = [
    1 => [
        'name' => 'Test Product 1',
        'price' => 100000, // Unit price
        'quantity' => 2,
        'image' => null
    ],
    2 => [
        'name' => 'Test Product 2', 
        'price' => 50000, // Unit price
        'quantity' => 3,
        'image' => null
    ]
];

// Calculate expected total
$expectedTotal = 0;
foreach ($testCartItems as $item) {
    $expectedTotal += $item['price'] * $item['quantity'];
}
echo "Expected order total: " . number_format($expectedTotal, 0, ',', '.') . " VND<br>\n";

// Create test order
$orderId = $orderModel->createOrder(
    'Test Customer',
    '0123456789',
    'test@example.com',
    'Test Address 123',
    'Test order for pricing migration',
    $expectedTotal
);

if ($orderId) {
    echo "Test order created with ID: $orderId<br>\n";
    
    // Create order details
    $detailsCreated = $orderModel->createOrderDetails($orderId, $testCartItems);
    
    if ($detailsCreated) {
        echo "Order details created successfully<br>\n";
        
        // Test 3: Retrieve and verify order
        echo "<h2>Test 3: Verify Order Data</h2>\n";
        $order = $orderModel->getOrderById($orderId);
        
        if ($order) {
            echo "Order retrieved successfully<br>\n";
            echo "Order total from database: " . number_format($order->total_amount, 0, ',', '.') . " VND<br>\n";
            echo "Expected total: " . number_format($expectedTotal, 0, ',', '.') . " VND<br>\n";
            echo "Totals match: " . ($order->total_amount == $expectedTotal ? "YES" : "NO") . "<br>\n";
            
            echo "<h3>Order Details:</h3>\n";
            echo "<table border='1' cellpadding='5'>\n";
            echo "<tr><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Total Price</th><th>Subtotal</th></tr>\n";
            
            foreach ($order->details as $detail) {
                echo "<tr>\n";
                echo "<td>" . htmlspecialchars($detail->product_name ?? 'Product #' . $detail->product_id) . "</td>\n";
                echo "<td>" . $detail->quantity . "</td>\n";
                echo "<td>" . number_format($detail->unit_price, 0, ',', '.') . " VND</td>\n";
                echo "<td>" . number_format($detail->price, 0, ',', '.') . " VND</td>\n";
                echo "<td>" . number_format($detail->subtotal, 0, ',', '.') . " VND</td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
            
            // Test 4: Verify calculations
            echo "<h2>Test 4: Verify Calculations</h2>\n";
            $calculatedTotal = 0;
            foreach ($order->details as $detail) {
                $calculatedUnitPrice = $detail->unit_price;
                $expectedUnitPrice = $testCartItems[$detail->product_id]['price'];
                $calculatedTotal += $detail->subtotal;
                
                echo "Product {$detail->product_id}:<br>\n";
                echo "- Expected unit price: " . number_format($expectedUnitPrice, 0, ',', '.') . " VND<br>\n";
                echo "- Calculated unit price: " . number_format($calculatedUnitPrice, 0, ',', '.') . " VND<br>\n";
                echo "- Unit prices match: " . (abs($calculatedUnitPrice - $expectedUnitPrice) < 0.01 ? "YES" : "NO") . "<br>\n";
                echo "- Total price in DB: " . number_format($detail->price, 0, ',', '.') . " VND<br>\n";
                echo "- Expected total: " . number_format($expectedUnitPrice * $detail->quantity, 0, ',', '.') . " VND<br>\n";
                echo "<br>\n";
            }
            
            echo "Final calculated total: " . number_format($calculatedTotal, 0, ',', '.') . " VND<br>\n";
            echo "Order total matches: " . ($calculatedTotal == $order->total_amount ? "YES" : "NO") . "<br>\n";
            
        } else {
            echo "ERROR: Could not retrieve test order<br>\n";
        }
    } else {
        echo "ERROR: Could not create order details<br>\n";
    }
} else {
    echo "ERROR: Could not create test order<br>\n";
}

// Test 5: Test statistics calculation
echo "<h2>Test 5: Test Statistics</h2>\n";
$stats = $orderModel->getOrderStatistics();
echo "Total orders: " . $stats->total_orders . "<br>\n";
echo "Total revenue: " . number_format($stats->total_revenue, 0, ',', '.') . " VND<br>\n";
echo "Today's orders: " . $stats->today_orders . "<br>\n";
echo "Today's revenue: " . number_format($stats->today_revenue, 0, ',', '.') . " VND<br>\n";

// Clean up test data (optional)
echo "<h2>Cleanup</h2>\n";
if (isset($orderId) && $orderId) {
    try {
        $stmt = $db->prepare("DELETE FROM order_details WHERE order_id = ?");
        $stmt->execute([$orderId]);
        
        $stmt = $db->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        
        echo "Test order cleaned up successfully<br>\n";
    } catch (Exception $e) {
        echo "Error cleaning up test data: " . $e->getMessage() . "<br>\n";
    }
}

echo "<hr>\n";
echo "<h2>Migration Summary</h2>\n";
echo "<p>The new pricing structure stores total amounts (unit_price × quantity) in the order_details.price column.</p>\n";
echo "<p>This improves performance for order total calculations and simplifies the data model.</p>\n";
echo "<p>Unit prices are calculated dynamically when needed for display purposes.</p>\n";

?>
