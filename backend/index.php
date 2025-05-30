<?php
require 'vendor/autoload.php'; 
require "middleware/AuthMiddleware.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('orderService', 'OrderService');
Flight::register('auth_middleware', "AuthMiddleware");

// This wildcard route intercepts all requests and applies authentication checks before proceeding.
//So everything what comes to backend will come to this route - and we are going to perform authentication here
Flight::route('/*', function() {
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
       return TRUE;
   } else {
       try {
           $token = Flight::request()->getHeader("Authentication");
           if(!$token)
               Flight::halt(401, "Missing authentication header");


           $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));


           Flight::set('user', $decoded_token->user);
           Flight::set('jwt_token', $token);
           return TRUE;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});


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
//Flight::register('orderService', 'OrderService');
require_once __DIR__ . '/rest/routes/OrderRoutes.php';


require_once __DIR__ . '/rest/services/OrderItemService.php';
Flight::register('orderItemService', 'OrderItemService');
require_once __DIR__ . '/rest/routes/OrderItemRoutes.php';

require_once __DIR__ . '/rest/services/AuthService.php';
Flight::register('auth_service', "AuthService");
require_once __DIR__ .'/rest/routes/AuthRoutes.php';

require_once __DIR__ . '/rest/dao/WishlistDao.php'; 

Flight::map('wishlistDao', function() {
    return new WishlistDao();
});


Flight::start();  //start FlightPHP
?>
