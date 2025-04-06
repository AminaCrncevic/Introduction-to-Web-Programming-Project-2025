<?php

require_once 'WishlistDao.php';

// Initialize WishlistDao
$wishlistDao = new WishlistDao();

// Test Insert Wishlist (Assuming a user with ID 7 exists) //EACH USER WITH IT'S ID CAN HAVE JUST ONE WISHLIST AT THE MOMENT-WORKS!
echo "Testing Insert Wishlist\n";
$wishlistId = $wishlistDao->insertWishlist(7);  // Create a new wishlist for user with ID 7
echo "New Wishlist ID: " . $wishlistId . "\n";

// Test Get Wishlist by User ID / - WORKS!
echo "Testing Get Wishlist by User ID\n";
$wishlist = $wishlistDao->getWishlistByUserId(7);  // Get wishlist for user with ID 2
print_r($wishlist);

// Test Get Wishlist by ID - WORKS!
echo "Testing Get Wishlist by ID\n";
$wishlistById = $wishlistDao->getWishlistById(10);  // Get wishlist by ID
print_r($wishlistById);

// Test Delete Wishlist - WORKS!
echo "Testing Delete Wishlist\n";
$wishlistDao->deleteWishlist(10);  // Delete the wishlist by its ID
echo "Wishlist with ID 10 has been deleted.\n";

?>





