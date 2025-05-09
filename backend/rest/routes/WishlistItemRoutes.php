<?php
/**
 * @OA\Get(
 *      path="/wishlistitems/{userId}",
 *      tags={"wishlist_items"},
 *      summary="Get all items in the user's wishlist",
 *      @OA\Parameter(in="path", name="userId", required=true, @OA\Schema(type="integer")),
 *      @OA\Response(response=200, description="List of items in user's wishlist")
 * )
 */
// Get all items in the user's wishlist -  WORKS!
Flight::route('GET /wishlistitems/@userId', function($userId) {
    Flight::json(Flight::wishlistItemService()->getItemsInWishlist($userId));
});





/**
 * @OA\Post(
 *      path="/wishlistitems",
 *      tags={"wishlist_items"},
 *      summary="Add a product to the user's wishlist",
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *              required={"user_id", "product_id"},
 *              @OA\Property(property="user_id", type="integer", example=1),
 *              @OA\Property(property="product_id", type="integer", example=10)
 *          )
 *      ),
 *      @OA\Response(response=200, description="Product added to wishlist"),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request - Product already in wishlist or wishlist not found"
 *      )
 * )
 */
// Add product to user's wishlist - WORKS!
Flight::route('POST /wishlistitems', function() {
   try {
    $data = Flight::request()->data->getData();
    Flight::wishlistItemService()->addItemToWishlist($data['user_id'], $data['product_id']);
    Flight::json(["message" => "Product added to wishlist"], 200); 
} catch (Exception $e) {
    
    Flight::json(["error" => $e->getMessage()], 400); 
}
});






/**
 * @OA\Delete(
 *      path="/wishlistitems/{id}",
 *      tags={"wishlist_items"},
 *      summary="Delete a specific item from the wishlist by wishlist item id",
 *      @OA\Parameter(in="path", name="id", required=true, @OA\Schema(type="integer")),
 *      @OA\Response(response=200, description="Wishlist item deleted")
 * )
 */

// Delete a specific wishlist item by wishlist item id - WORKS!
Flight::route('DELETE /wishlistitems/@id', function($id) {
    Flight::wishlistItemService()->deleteItemFromWishlist($id);
    Flight::json(["message" => "Wishlist item deleted."]);
});



/**
 * @OA\Put(
 *      path="/wishlistitems/{wishlistItemId}",
 *      tags={"wishlist_items"},
 *      summary="Update a wishlist item (change product)",
 *      @OA\Parameter(in="path", name="wishlistItemId", required=true, @OA\Schema(type="integer")),
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *              required={"new_product_id", "user_id"},
 *              @OA\Property(property="new_product_id", type="integer", example=20),
 *              @OA\Property(property="user_id", type="integer", example=1)
 *          )
 *      ),
 *      @OA\Response(response=200, description="Wishlist item updated"),
 * @OA\Response(response=404,
 *          description="Wishlist not found for the given user ID"),
 * @OA\Response(
 *          response=409,
 *          description="Product already exists in the wishlist"),
 * @OA\Response(
 *          response=500,
 *          description="Internal server error")
 * )
 */

// Update a wishlist item (change product inside the wishlist item) - WORKS!
Flight::route('PUT /wishlistitems/@wishlistItemId', function($wishlistItemId) {
    try{ 
    $data = Flight::request()->data->getData();
    Flight::wishlistItemService()->updateWishlistItem($wishlistItemId, $data['new_product_id'], $data['user_id']);
    Flight::json(['message' => 'Product updated successfully.'], 200);
    } catch (Exception $e) {
        
        if ($e->getMessage() == 'Wishlist not found.') {
            Flight::json(['error' => $e->getMessage()], 404);  
        } elseif ($e->getMessage() == 'Product already exists in the wishlist.') {
            Flight::json(['error' => $e->getMessage()], 409);  
        } else {
            
            Flight::json(['error' => 'An unexpected error occurred.'], 500);  
        }
    }
});





/**
 * @OA\Delete(
 *      path="/wishlistitems/clear/{userId}",
 *      tags={"wishlist_items"},
 *      summary="Clear entire wishlist for a user",
 *      @OA\Parameter(in="path", name="userId", required=true, @OA\Schema(type="integer")),
 *      @OA\Response(response=200, description="Wishlist cleared successfully."),
 *      @OA\Response(response=404, description="Wishlist not found for the user."),
 *      @OA\Response(response=500, description="Internal server error.")
 * )
 */

// Clear entire wishlist for a user - WORKS!
Flight::route('DELETE /wishlistitems/clear/@userId', function($userId) {
    try{ 
    Flight::wishlistItemService()->clearWishlist($userId);
    Flight::json(["message" => "Wishlist cleared."], 200);
    } catch (Exception $e) {
        
        if ($e->getMessage() == 'Wishlist not found.') {
            Flight::json(['error' => $e->getMessage()], 404);  
        } else {
            
            Flight::json(['error' => 'An unexpected error occurred.'], 500);  
        }
    }
});



?>