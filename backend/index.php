<?php
require 'vendor/autoload.php'; 

/*
Flight::route('/', function(){  //define route and define function to handle request
   echo 'Hello world!';
});*/

require_once __DIR__ . '/rest/services/ProductService.php';
Flight::register('productService', 'ProductService');
require_once __DIR__ . '/rest/routes/ProductRoutes.php'; 


require_once __DIR__ . '/rest/services/UserService.php';
Flight::register('userService', 'UserService');
require_once __DIR__ . '/rest/routes/UserRoutes.php';



require_once __DIR__ . '/rest/services/PaymentService.php';
Flight::register('paymentService', 'PaymentService');
require_once __DIR__ . '/rest/routes/PaymentRoutes.php';


require_once __DIR__ . '/rest/services/WishlistService.php';
Flight::register('wishlistService', 'WishlistService');
require_once __DIR__ . '/rest/routes/WishlistRoutes.php';



require_once __DIR__ . '/rest/services/WishlistItemService.php';
Flight::register('wishlistItemService', 'WishlistItemService');
require_once __DIR__ . '/rest/routes/WishlistItemRoutes.php';


require_once __DIR__ . '/rest/services/OrderService.php';
Flight::register('orderService', 'OrderService');
require_once __DIR__ . '/rest/routes/OrderRoutes.php';


require_once __DIR__ . '/rest/services/OrderItemService.php';
Flight::register('orderItemService', 'OrderItemService');
require_once __DIR__ . '/rest/routes/OrderItemRoutes.php';




Flight::start();  //start FlightPHP
?>
