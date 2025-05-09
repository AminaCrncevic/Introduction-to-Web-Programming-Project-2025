<?php



require_once 'OrderItemDao.php';

$orderItemDao = new OrderItemDao();

// Test 1: Get all order items by OrderID - WORKS!
$orderId = 4;  
$orderItems = $orderItemDao->getOrderItemsByOrderId($orderId);
echo "Test 1: Get Order Items by OrderID\n";
print_r($orderItems);  
echo "\n";


// Test 2: Insert a new order item - WORKS!
$orderItemData = [
    'Orders_OrderID' => 4,  
    'Products_ProductID' => 2,  
    'Quantity' => 3,
    //price is dynamically added
];
$orderItemDao->insertOrderItem($orderItemData);
echo "Test 2: Insert New Order Item\n";
echo "New order item inserted for OrderID: {$orderItemData['Orders_OrderID']}, ProductID: {$orderItemData['Products_ProductID']}\n";
echo "\n";




$orderItemDao->updateOrderItem(9, ["Quantity" => 3,
"Price" => 34.99
]);


// Get all order items - WORKS!
$orderItems = $orderItemDao->getAllOrderItems();
// Display the results
if ($orderItems) {
    echo "All Order Items:\n";
    print_r($orderItems);
} else {
    echo "No order items found.\n";
}


// Get a specific order item by OrderItem ID - WORKS!
$orderItem = $orderItemDao->getOrderItemById(9);
print_r($orderItem);



// Test 4: Delete an order item - WORKS!
$orderItemIdToDelete = 15;  
$orderItemDao->deleteOrderItem($orderItemIdToDelete);
echo "Test 4: Delete Order Item\n";
echo "Deleted OrderItemID: {$orderItemIdToDelete}\n";
echo "\n";

?>

