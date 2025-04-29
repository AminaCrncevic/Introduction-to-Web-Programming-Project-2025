
<?php


/**
 * @OA\Get(
 *      path="/orderitems/{orderId}",
 *      tags={"order_items"},
 *      summary="Get all items for a specific order",
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
    $items = Flight::orderItemService()->getItemsByOrderId($orderId);
    Flight::json($items);
});







/**
 * @OA\Post(
 *      path="/orderitems/add",
 *      tags={"order_items"},
 *      summary="Add an item to a user's pending order (cart)",
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
 *          description="Item added to cart successfully"))
 */
// Add an item to a user's pending order (cart) - WORKS!
Flight::route('POST /orderitems/add', function() {
    $data = Flight::request()->data->getData();
    Flight::orderItemService()->addItemToCart($data['user_id'], $data['product_id'], $data['quantity']);
    Flight::json(["message" => "Item added to cart."]);
});







/**
 * @OA\Put(
 *      path="/orderitems/update",
 *      tags={"order_items"},
 *      summary="Update quantity of a cart item",
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
    $data = Flight::request()->data->getData();
    Flight::orderItemService()->updateCartItem($data['order_item_id'], $data['new_quantity']);
    Flight::json(["message" => "Cart item updated successfully."]);
});









/**
 * @OA\Delete(
 *      path="/orderitems/remove/{orderItemId}",
 *      tags={"order_items"},
 *      summary="Remove an item from the cart",
 *      @OA\Parameter(
 *          in="path", 
 *          name="orderItemId", 
 *          required=true, 
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Cart item removed successfully"))
 */

// Remove an item from the cart 
Flight::route('DELETE /orderitems/remove/@orderItemId', function($orderItemId) {
    Flight::orderItemService()->removeItemFromCart($orderItemId);
    Flight::json(["message" => "Cart item removed successfully."]);
});


?>
