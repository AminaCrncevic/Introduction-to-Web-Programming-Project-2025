
<?php
require_once 'vendor/autoload.php';
require_once 'data/Roles.php';  // Include the Roles class
require_once 'middleware/AuthMiddleware.php';  // Include the AuthMiddleware



/**
 * @OA\Get(
 *      path="/orderitems/{orderId}",
 *      tags={"order_items"},
 *      summary="Get all items for a specific order",
 * security={
    *         {"ApiKey": {}}
    *     },
 *      @OA\Parameter(
 *          in="path", 
 *          name="orderId", 
 *          required=true, 
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Order items retrieved successfully")
 * )
 */

// Get all items for a specific order - WORKS!
Flight::route('GET /orderitems/@orderId', function($orderId) {
   $user = Flight::get('user');  // Get the authenticated user
   Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
   try{ 
    $order = Flight::orderService()->getOrderById($orderId);
    if (!$order) {
        Flight::halt(404, "Order not found.");
    }
    // If user is not admin and not the owner of the order
    if ($user->UserType !== Roles::ADMIN && $user->id !== $order['Users_UserID']) {
        Flight::halt(403, "Forbidden: You can only access your own order items.");
    }
    $items = Flight::orderItemService()->getItemsByOrderId($orderId);
    Flight::json($items);
} catch (Exception $e) {
        Flight::halt(500, "Internal Server Error: " . $e->getMessage());
    }
});







/**
 * @OA\Post(
 *      path="/orderitems/add",
 *      tags={"order_items"},
 *      summary="Add an item to a user's pending order (cart)",
 * security={
    *         {"ApiKey": {}}
    *     },
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *              required={"user_id", "product_id", "quantity"},
 *              @OA\Property(property="user_id", type="integer", example=1),
 *              @OA\Property(property="product_id", type="integer", example=15),
 *              @OA\Property(property="quantity", type="integer", example=3)
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Item added to cart successfully"),
 *  @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *  @OA\Response(
 *          response=500,
 *          description="Internal Server Error"
 *      )
 * )
 */
// Add an item to a user's pending order (cart) - WORKS!
Flight::route('POST /orderitems/add', function() {
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
    $user = Flight::get('user');
    $data = Flight::request()->data->getData();
    try{
         // Validate required fields
        if (!isset($data['user_id'], $data['product_id'], $data['quantity'])) {
            Flight::halt(400, "Missing required fields.");
        }

        // Only admin or the actual user can add to their cart
        if ($user->UserType !== Roles::ADMIN && (int)$user->id !== (int)$data['user_id']) {
            Flight::halt(403, "Forbidden: You can only modify your own cart.");
        }
    
    Flight::orderItemService()->addItemToCart($data['user_id'], $data['product_id'], $data['quantity']);
    Flight::json(["message" => "Item added to cart."]);
    } catch (Exception $e) {
        Flight::halt(500, "Internal Server Error: " . $e->getMessage());
    }
});







/**
 * @OA\Put(
 *      path="/orderitems/update",
 *      tags={"order_items"},
 *      summary="Update quantity of a cart item",
 * security={
    *         {"ApiKey": {}}
    *     },
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *              required={"order_item_id", "new_quantity"},
 *              @OA\Property(property="order_item_id", type="integer", example=1),
 *              @OA\Property(property="new_quantity", type="integer", example=5)
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Cart item updated successfully"))
 */
// Update quantity of a cart item   - WORKS!
Flight::route('PUT /orderitems/update', function() {
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
    $user = Flight::get('user');
    $data = Flight::request()->data->getData();
    try{
          $orderItem = Flight::orderItemService()->getOrderItemById1($data['order_item_id']);

        if (!$orderItem) {
            Flight::halt(404, "Order item not found.");
        }

        // Check ownership or admin role
        if ($user->UserType !== Roles::ADMIN && $user->id !== $orderItem['Users_UserID']) {
            Flight::halt(403, "Forbidden: You can only modify your own cart items.");
        }
    Flight::orderItemService()->updateCartItem($data['order_item_id'], $data['new_quantity']);
    Flight::json(["message" => "Cart item updated successfully."]);
    }
    catch (Exception $e) {
        Flight::halt(500, "Internal Server Error: " . $e->getMessage());
    }
});









/**
 * @OA\Delete(
 *      path="/orderitems/remove/{orderItemId}",
 *      tags={"order_items"},
 *      summary="Remove an item from the cart",
 * security={
    *         {"ApiKey": {}}
    *     },
 *      @OA\Parameter(
 *          in="path", 
 *          name="orderItemId", 
 *          required=true, 
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Cart item removed successfully"),
 *   @OA\Response(
 *         response=403,
 *         description="Forbidden: You can only delete your own cart items"
 *     ),
 *   @OA\Response(
 *         response=404,
 *         description="Order item not found"
 *     )
 * )
 */

// Remove an item from the cart 
Flight::route('DELETE /orderitems/remove/@orderItemId', function($orderItemId) { 
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
     $user = Flight::get('user');
try{
     $orderItem = Flight::orderItemService()->getOrderItemById1($orderItemId);
        if (!$orderItem) {
            Flight::halt(404, "Order item not found.");
        }
        // Check if the user is the owner or an admin
        if ($user->UserType !== Roles::ADMIN && $user->id !== $orderItem['Users_UserID']) {
            Flight::halt(403, "Forbidden: You can only delete your own cart items.");
        }    
    Flight::orderItemService()->removeItemFromCart($orderItemId);
    Flight::json(["message" => "Cart item removed successfully."]);
      } catch (Exception $e) {
        Flight::halt(500, "Internal Server Error: " . $e->getMessage());
    }
});




?>
