<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/WishlistItemDao.php';
require_once __DIR__ . '/../dao/WishlistDao.php';

class WishlistItemService extends BaseService {
    /** @var WishlistItemDao */
    protected $dao;

    /** @var WishlistDao */
    protected $wishlistDao;

    public function __construct() {
        $this->dao = new WishlistItemDao();
        $this->wishlistDao = new WishlistDao();
        parent::__construct($this->dao);
    }

    // Add item to wishlist only if not already present
    public function addItemToWishlist($userId, $productId) {
        try{ 
        
        $wishlist = $this->wishlistDao->getWishlistByUserId($userId);
        if (!$wishlist) {
            throw new Exception("Wishlist not found for user.");
        }

        $wishlistId = $wishlist['id'];

        // Check if product already exists in the wishlist
        $existingItem = $this->dao->isProductInWishlist($wishlistId, $productId);
        if ($existingItem) {
            throw new Exception("This product is already in the wishlist.");
        }

        // Add product to wishlist
        $this->dao->addWishlistItem($wishlistId, $productId);
    } catch (Exception $e) {
        
        throw new Exception($e->getMessage(), $e->getCode());
    }}

    // Get all items in user's wishlist
    public function getItemsInWishlist($userId) {
        $wishlist = $this->wishlistDao->getWishlistByUserId($userId);
        if (!$wishlist) {
            return [];
        }
        return $this->dao->getWishlistItems($wishlist['id']);
    }

    // Delete an item from wishlist
    public function deleteItemFromWishlist($wishlistItemId) {
        $this->dao->deleteWishlistItem($wishlistItemId);
    }

    // Update an item in the wishlist 
    public function updateWishlistItem($wishlistItemId, $newProductId, $userId) {
        try{ 
        $wishlist = $this->wishlistDao->getWishlistByUserId($userId);
        if (!$wishlist) {
            throw new Exception("Wishlist not found.");
        }

        // Prevent adding a duplicate product
        if ($this->dao->isProductInWishlist($wishlist['id'], $newProductId)) {
            throw new Exception("Product already exists in the wishlist.");
        }

        $this->dao->updateWishlistItem($wishlistItemId, $newProductId);
    }catch (Exception $e){
        throw new Exception($e->getMessage());
    }
    }


    public function clearWishlist($userId) {
        $wishlist = $this->wishlistDao->getWishlistByUserId($userId);
        if (!$wishlist) {
            throw new Exception("Wishlist not found.");
        }
        $this->dao->clearWishlistItems($wishlist['id']);
    }

}
