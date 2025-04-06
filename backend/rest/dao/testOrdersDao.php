<?php

require_once 'OrdersDao.php'; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Create an instance of OrderDao
$orderDao = new OrderDao();

// Test: Create a new order (Insert) - WORKS!
$newOrder = [
    'Users_UserID' => 7,           // Assuming user with ID 6 exists - WORKS!
    'OrderStatus' => 'pending',
    'TotalAmount' => 1034.00
];
$orderDao->addOrder($newOrder);
echo "Order created successfully.\n";


// Test: Get the order by ID (Read) - WORKS!
$order = $orderDao->getOrderById(7); // I FETCHED ORDER WITH Order ID  7 - WORKS!
if ($order) {
    echo "Order fetched by ID successfully: ";
    print_r($order);
} else {
    echo "Order not found.\n";
}


// Test: Update an existing order - WORKS!
$updatedOrder = [
    'OrderStatus' => 'completed', 
    'TotalAmount' => 125.00
];
$orderDao->updateOrder(8, $updatedOrder); 
echo "Order updated successfully.\n";


// Test: Delete an order - WORKS!
$orderDao->deleteOrder(6);  //  the order ID is 6  - WORKS! - ORDER DELETED
echo "Order deleted successfully.\n"; 


// Test: Retrieve all orders for a user - WORKS!
$orders = $orderDao->getOrdersByUserId(2); // user ID is 2 - WORKS!
if ($orders) {
    echo "Orders fetched for user with ID 2: ";
    print_r($orders);
} else {
    echo "No orders found for user with ID 2.\n";
}





?>
