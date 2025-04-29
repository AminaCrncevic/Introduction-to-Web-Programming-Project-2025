<?php
require_once __DIR__ . '/../services/UserService.php';
require_once 'WishlistService.php';

$userService = new UserService();

$data = [
    'FirstName' => 'Alice',
    'LastName' => 'Smith',
    'email' => 'aliceTEST.smith@example.com',
    'Password' => 'securepassword123',
];

try {
    $userId = $userService->createUser($data);
    echo "User created successfully with ID: $userId\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}


?>
