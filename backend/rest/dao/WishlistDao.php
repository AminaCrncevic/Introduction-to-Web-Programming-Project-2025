<?php

require_once 'BaseDao.php';

class WishlistDao extends BaseDao {
    public function __construct() {
        parent::__construct("Wishlist");
    }

    //  new wishlist entry for a user
    public function insertWishlist($userId) {
        $stmt = $this->connection->prepare("
            INSERT INTO Wishlist (Users_UserID) VALUES (:userId)
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $this->connection->lastInsertId();  // Return the ID of the new wishlist
    }

    // Get wishlist by user ID 
    public function getWishlistByUserId($userId) {
        $stmt = $this->connection->prepare("
            SELECT * FROM Wishlist WHERE Users_UserID = :userId
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete a wishlist entry by ID (basic CRUD operation)
    public function deleteWishlist($wishlistId) {
        $stmt = $this->connection->prepare("
            DELETE FROM Wishlist WHERE id = :wishlistId
        ");
        $stmt->bindParam(':wishlistId', $wishlistId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Get wishlist by ID (basic CRUD operation)
    public function getWishlistById($wishlistId) {
        $stmt = $this->connection->prepare("SELECT * FROM Wishlist WHERE id = :wishlistId");
        $stmt->bindParam(':wishlistId', $wishlistId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>









