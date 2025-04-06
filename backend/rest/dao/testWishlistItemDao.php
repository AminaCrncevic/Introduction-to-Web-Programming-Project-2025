
<?php

require_once 'WishlistItemDao.php';

try {

 // Create an instance of WishlistItemDao
 $wishlistItemDao = new WishlistItemDao();

// Test adding an item to the wishlist //WORKS!
 echo "Adding item to wishlist...\n";
 $wishlistItemDao->addWishlistItem(6, 4);  // wishlistId = 6 and productId = 4

 // Test getting all items in the wishlist - WORKS!
 echo "Fetching items in wishlist...\n";
 $items = $wishlistItemDao->getWishlistItems(6);
 print_r($items);

 // Test deleting an item from the wishlist - WORKS!
 echo "Deleting item from wishlist...\n";
 $wishlistItemDao->deleteWishlistItem(6); 
 echo "Test completed successfully.\n";


// TEST -  Call isProductInWishlist - WORKS!
$result = $wishlistItemDao->isProductInWishlist(6, 5);
// Display result
if ($result) {
    echo "Product is in the wishlist.\n";
} else {
    echo "Product is NOT in the wishlist.\n";
}


//TESTING UPDATE FUNCTION - WORKS!
try {
    $wishlistItemDao->updateWishlistItem(7, 5);
    echo "Wishlist item updated successfully.\n";
} catch (Exception $e) {
    echo "Failed to update wishlist item: " . $e->getMessage() . "\n";
}

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>



