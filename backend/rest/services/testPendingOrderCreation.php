<?php

require_once 'UserService.php';
require_once 'OrderService.php';
require_once 'PaymentService.php';

$userService = new UserService();


$userData = [
    'FirstName' => 'Emma',
    'LastName' => 'Wats',
    'email' => 'Emma.Watts3fHghdd4@stu.ibu.edu.ba',
    'Password' => 'securepassword12',

];

$newUserId = $userService->createUser($userData);

echo "New user created with ID: " . $newUserId . "\n";

?>
