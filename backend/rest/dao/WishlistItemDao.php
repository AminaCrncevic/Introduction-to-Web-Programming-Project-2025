<?php

require_once 'BaseDao.php';

class WishlistItemDao extends BaseDao {

    public function __construct() {
        parent::__construct("wishlistitem");
    }

    // Add item to wishlist 
    public function addWishlistItem($wishlistId, $productId) {
        $stmt = $this->connection->prepare("INSERT INTO wishlistitem (Wishlist_WishlistID, Products_ProductID) VALUES (:wishlistId, :productId)");
        $stmt->bindParam(':wishlistId', $wishlistId, PDO::PARAM_INT);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Get all items in a wishlist
    public function getWishlistItems($wishlistId) {
        $stmt = $this->connection->prepare("SELECT w.id, p.ProductName, p.ProductPrice, p.ProductImage
                                            FROM wishlistitem w 
                                            JOIN products p ON w.Products_ProductID = p.id 
                                            WHERE w.Wishlist_WishlistID = :wishlistId");
        $stmt->bindParam(':wishlistId', $wishlistId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a specific wishlist item
    public function deleteWishlistItem($wishlistItemId) {
        $stmt = $this->connection->prepare("DELETE FROM wishlistitem WHERE id = :wishlistItemId");
        $stmt->bindParam(':wishlistItemId', $wishlistItemId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Check if product already exists in the wishlist (for business logic)
    public function isProductInWishlist($wishlistId, $productId) {
        $stmt = $this->connection->prepare("SELECT id FROM wishlistitem WHERE Wishlist_WishlistID = :wishlistId AND Products_ProductID = :productId");
        $stmt->bindParam(':wishlistId', $wishlistId, PDO::PARAM_INT);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // returns the item if exists
    }

    // Update wishlist item (e.g., replace product with a new one)
    public function updateWishlistItem($wishlistItemId, $newProductId) {
        $stmt = $this->connection->prepare("UPDATE wishlistitem SET Products_ProductID = :newProductId WHERE id = :wishlistItemId");
        $stmt->bindParam(':wishlistItemId', $wishlistItemId, PDO::PARAM_INT);
        $stmt->bindParam(':newProductId', $newProductId, PDO::PARAM_INT);
        $stmt->execute();
    }


    public function clearWishlistItems($wishlistId) {
        $stmt = $this->connection->prepare("DELETE FROM wishlistitem WHERE Wishlist_WishlistID = :wishlistId");
        $stmt->bindParam(':wishlistId', $wishlistId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
}

?>
