<?php

require_once __DIR__ . '/../services/WishlistItemService.php';

$wishlistItemService = new WishlistItemService();

$userId = 22;
$productId1 = 4; 
$productId2 = 5; 

//TEST CLEAR WISHLIST FOR A USER WITH SPECIFIC ID - WORKS!
try {
    $wishlistItemService->clearWishlist($userId);
    echo "Wishlist cleared successfully for user ID: $userId\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}


echo "====== Testing Add Item to Wishlist ======\n"; //WORKS!
try {
    $wishlistItemService->addItemToWishlist($userId, $productId1);
    echo "Product $productId1 added to user $userId's wishlist.\n";
} catch (Exception $e) {
    echo "Add item failed: " . $e->getMessage() . "\n";
}


echo "\n====== Testing Get Wishlist Items ======\n"; //WORKS!
try {
    $items = $wishlistItemService->getItemsInWishlist($userId);
    foreach ($items as $item) {
        echo "WishlistItem ID: {$item['id']}, Product: {$item['ProductName']}, Price: {$item['ProductPrice']}\n";
    }
} catch (Exception $e) {
    echo "Get items failed: " . $e->getMessage() . "\n";
}



echo "\n====== Testing Update Wishlist Item ======\n"; //WORKS!
try {
    if (!empty($items)) {
        $wishlistItemId = $items[0]['id'];
        $wishlistItemService->updateWishlistItem($wishlistItemId, $productId2, $userId);
        echo "Wishlist item {$wishlistItemId} updated to product $productId2.\n";
    } else {
        echo "No items to update.\n";
    }
} catch (Exception $e) {
    echo "Update item failed: " . $e->getMessage() . "\n";
}

echo "\n====== Testing Delete Wishlist Item ======\n"; //-  WORKS!
try {
    if (!empty($items)) {
        $wishlistItemId = $items[0]['id'];
        $wishlistItemService->deleteItemFromWishlist($wishlistItemId);
        echo "Wishlist item {$wishlistItemId} deleted.\n";
    } else {
        echo "No items to delete.\n";
    }
} catch (Exception $e) {
    echo "Delete item failed: " . $e->getMessage() . "\n";
}

?>
