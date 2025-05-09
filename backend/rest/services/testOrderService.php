<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../services/OrderService.php';
require_once __DIR__ . '/../dao/OrdersDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

function testOrderService() {
    
    $orderService = new OrderService();

    
    $userId = 30;
    $productId = 4;
    $quantity = 16;

    // Step 1: Add an item to the order - WORKS!
    $orderService->addItemToOrder($userId, $productId, $quantity);
    echo "Test 1 - addItemToOrder: Item added to order.\n";

    // Step 2: Get pending order items - WORKS!
    $items = $orderService->getPendingOrderItems($userId);
    echo "Test 2 - getPendingOrderItems: Total items in order: " . count($items) . "\n";
    

echo "Item details: \n";
foreach ($items as $item) {
    echo "Product ID: " . $item['productId'] . "\n";
    echo "Product Name: " . $item['productName'] . "\n";
    echo "Quantity: " . $item['quantity'] . "\n";
    echo "Subtotal: " . $item['subtotal'] . "\n";
    echo "---------------------------------\n";}

    
}


testOrderService();



?>
