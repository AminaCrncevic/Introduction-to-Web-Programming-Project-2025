<?php

require_once 'OrderItemService.php';
require_once 'OrderService.php';
require_once __DIR__ . '/../dao/OrdersDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

$orderItemService = new OrderItemService();
$orderService = new OrderService();


echo "Fetching pending order items...\n";
$items = $orderItemService->getItemsByOrderId(21);
echo "Items in cart: \n";
print_r($items);



$newQuantity = 5; 
echo "Updating item quantity...\n";
$orderItemService->updateCartItem(16, 4);


?>