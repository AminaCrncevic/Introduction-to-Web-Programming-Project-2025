
<?php


/**
 * @OA\Get(
 *     path="/orders/{userId}",
 *     tags={"orders"},
 *     summary="Get all orders for a specific user",
 *     @OA\Parameter(in="path", name="userId", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Returns all orders for the user"),
 *  @OA\Response(
 *          response=404,
 *          description="No orders found for the given user ID")
 * )
 */
// Get all orders for a specific user - WORKS!
Flight::route('GET /orders/@userId', function($userId) {
    //Flight::json(Flight::orderService()->getOrdersByUserId($userId));
    try {
        $orders = Flight::orderService()->getOrdersByUserId($userId);
        Flight::json($orders);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 404);  // Internal server error
    }
});






/**
 * @OA\Get(
 *     path="/orders/single/{orderId}",
 *     tags={"orders"},
 *     summary="Get a single order by order ID",
 *     @OA\Parameter(in="path", name="orderId", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Returns the order"),
 *     @OA\Response(response=404, description="Order not found")
 * )
 */
// Get a single order by order ID - WORKS!
Flight::route('GET /orders/single/@orderId', function($orderId) {
    //Flight::json(Flight::orderService()->getOrderById($orderId));
    try {
        $order = Flight::orderService()->getOrderById($orderId);
        Flight::json($order);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 404);  // Order not found
    }
});






/**
 * @OA\Post(
 *     path="/orders/add-item",
 *     tags={"orders"},
 *     summary="Add item to a user's pending order",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             required={"user_id", "product_id", "quantity"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="product_id", type="integer", example=1),
 *             @OA\Property(property="quantity", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Item added to order"),
 *     @OA\Response(response=400, description="Bad request")
 *  
 * )
 */
// Add item to a user's pending order - WORKS!
Flight::route('POST /orders/add-item', function() {
    $data = Flight::request()->data->getData();
   try {
    Flight::orderService()->addItemToOrder($data['user_id'], $data['product_id'], $data['quantity']);
    Flight::json(["message" => "Item added to order."]);
} catch (Exception $e) {
    Flight::json(['error' => $e->getMessage()], 400);  // Bad Request
}
});





/**
 * @OA\Delete(
 *     path="/orders/remove-item/{orderItemId}/{userId}",
 *     tags={"orders"},
 *     summary="Remove an item from a user's order",
 *     @OA\Parameter(in="path", name="orderItemId", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(in="path", name="userId", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Item removed from order"),
 *     @OA\Response(response=400, description="Bad request")
 * )
 */
// Remove an item from a user's order
Flight::route('DELETE /orders/remove-item/@orderItemId/@userId', function($orderItemId, $userId) {
    //Flight::orderService()->removeItemFromOrder($orderItemId, $userId);
    //Flight::json(["message" => "Item removed from order."]);
    try {
        Flight::orderService()->removeItemFromOrder($orderItemId, $userId);
        Flight::json(["message" => "Item removed from order."]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);  
    }
});






/**
 * @OA\Put(
 *     path="/orders/update-item",
 *     tags={"orders"},
 *     summary="Update quantity of an item in the user's order",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             required={"order_item_id", "new_quantity", "user_id"},
 *             @OA\Property(property="order_item_id", type="integer", example=1),
 *             @OA\Property(property="new_quantity", type="integer", example=3),
 *             @OA\Property(property="user_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Item quantity updated"),
 *     @OA\Response(response=400, description="Bad request"),
 *     @OA\Response(response=500, description="Internal server error")
 * )
 */
// Update quantity of an item in the user's order - WORKS!
Flight::route('PUT /orders/update-item', function() {
    $data = Flight::request()->data->getData();
    Flight::orderService()->updateItemQuantity($data['order_item_id'], $data['new_quantity'], $data['user_id']);
    Flight::json(["message" => "Item quantity updated."]);
});








/**
 * @OA\Get(
 *      path="/orders/pending-items/{userId}",
 *      tags={"orders"},
 *      summary="Get all items in the user's pending order",
 *      @OA\Parameter(
 *          in="path", 
 *          name="userId", 
 *          required=true, 
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Items in the user's pending order retrieved successfully"),

 *      @OA\Response(
 *          response=500,
 *          description="Internal server error")
 * )
 */

// Get all items in the user's pending order - WORKS!
Flight::route('GET /orders/pending-items/@userId', function($userId) {
   // Flight::json(Flight::orderService()->getPendingOrderItems($userId));
   try {
    $items = Flight::orderService()->getPendingOrderItems($userId);
    Flight::json($items);
} catch (Exception $e) {
    Flight::json(['error' => $e->getMessage()], 500);  // Internal server error
}
});




/**
 * @OA\Put(
 *     path="/orders/complete/{orderId}/{userId}",
 *     tags={"orders"},
 *     summary="Finalize (complete) an order",
 *     @OA\Parameter(in="path", name="orderId", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(in="path", name="userId", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Order completed successfully"),
 *     @OA\Response(response=400, description="Bad request")
 * )
 */

// Finalize (complete) an order - WORKS! -When user clicks pay first will be order finalized and then payment for that order;they will
//be marked as completed and new pendind order and payment will be created.
Flight::route('PUT /orders/complete/@orderId/@userId', function($orderId, $userId) {
   // Flight::orderService()->completeOrder($orderId, $userId);
    //Flight::json(["message" => "Order completed successfully."]);
    try {
        Flight::orderService()->completeOrder($orderId, $userId);
        Flight::json(["message" => "Order completed successfully."]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);  // Bad Request
    }
});






/**
 * @OA\Get(
 *      path="/orders/pending-or-create/{userId}",
 *      tags={"orders"},
 *      summary="Ensure a pending order exists or create one for a user",
 *      @OA\Parameter(
 *          in="path", 
 *          name="userId", 
 *          required=true, 
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Pending order retrieved or created successfully"),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error")
 *      
 * )
 */

// Ensure a pending order exists or create one for a user -WORKS!
Flight::route('GET /orders/pending-or-create/@userId', function($userId) {
 //   Flight::json(Flight::orderService()->getOrCreatePendingOrder($userId));
 try {
    $order = Flight::orderService()->getOrCreatePendingOrder($userId);
    Flight::json($order);
} catch (Exception $e) {
    Flight::json(['error' => $e->getMessage()], 500);  // Internal server error
}
});




?>
