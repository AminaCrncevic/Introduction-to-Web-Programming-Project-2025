<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/WishlistDao.php';
require_once __DIR__ . '/../dao/UserDao.php';  
 //WISHLIST IS CREATED UPON THE CREATION OF USER - EACH USER ONE WISHLIST
class WishlistService extends BaseService {
    /** @var WishlistDao */
    protected $dao;
    
    /** @var UserDao */
    protected $userDao;

    public function __construct() {
        $this->dao = new WishlistDao();
        $this->userDao = new UserDao();
        parent::__construct($this->dao);
    }

    // Create a new wishlist for a user, called from UserService when a user is created - WORKS!

    public function createWishlistForUser($userId) {
    // Ensure the user exists before creating the wishlist
    $existingUser = $this->userDao->getUserById($userId);
    if (!$existingUser) {
        throw new Exception("User does not exist.");
   }
        // Check if the user already has a wishlist
        $existingWishlist = $this->dao->getWishlistByUserId($userId);
        if ($existingWishlist) {
            throw new Exception("This user already has a wishlist.");
        }
       

        // If no wishlist exists for this user, create a new one
        $wishlistId = $this->dao->insertWishlist($userId);
        return $wishlistId;
    }

    

    // Get wishlist for a user
    public function getWishlistByUserId($userId) {
    
       $wishlist = $this->dao->getWishlistByUserId($userId);

    if (!$wishlist) {
        throw new Exception("Wishlist not found for user ID: " . $userId, 404);
    }

    return $wishlist;
    
    }




    // Delete a wishlist entry for a user (optional)
    public function deleteWishlist($wishlistId) {
        $this->dao->deleteWishlist($wishlistId);
    }


}

/*createWishlistForUser: This method checks if the user already has a wishlist. If not, it creates a new wishlist entry for them.
getWishlistByUserId: Returns the wishlist for a given user.
deleteWishlist: Deletes the wishlist by ID. This is an optional method.
getWishlistById: Retrieves the wishlist by its unique ID.*/

?>
