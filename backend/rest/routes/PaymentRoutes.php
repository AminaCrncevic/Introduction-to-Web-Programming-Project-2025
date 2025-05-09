
<?php




/**
 * @OA\Get(
 *     path="/payments/pending/{userId}",
 *     tags={"payments"},
 *     summary="Get the pending payment for a user",
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Pending payment data"),
 *     @OA\Response(response="404", description="No pending order/payment found")
 * )
 */
// Get the pending payment for a user -WORKS!
Flight::route('GET /payments/pending/@userId', function($userId) {
  try {
    Flight::json(Flight::paymentService()->getPendingPaymentForUser($userId));
} catch (Exception $e) {
    Flight::json(["error" => $e->getMessage()], 404);
}
});












/**
 * @OA\Post(
 *     path="/payments/create",
 *     tags={"payments"},
 *     summary="Create a pending payment for a user's order",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id", "user_id"},
 *             @OA\Property(property="order_id", type="integer", example=123),
 *             @OA\Property(property="user_id", type="integer", example=456)
 *         )
 *     ),
 *     @OA\Response(response="200", description="Pending payment created"),
 *     @OA\Response(response="400", description="Pending payment already exists or bad request")
 * )
 */
// Create a pending payment for a user's order - WORKS!
Flight::route('POST /payments/create', function() {
    try {
        $data = Flight::request()->data->getData();
        Flight::paymentService()->createPendingPaymentForOrder($data['order_id'], $data['user_id']);
        Flight::json(["message" => "Pending payment created."]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});










/**
 * @OA\Put(
 *     path="/payments/mark-completed/{orderId}",
 *     tags={"payments"},
 *     summary="Mark the payment as completed for an order",
 *     @OA\Parameter(
 *         name="orderId",
 *         in="path",
 *         required=true,
 *         description="ID of the order",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Payment marked as completed"),
 *     @OA\Response(response="400", description="Order or payment not found, or payment not pending")
 * )
 */
// Mark the payment as completed for an order - WORKS!
Flight::route('PUT /payments/mark-completed/@orderId', function($orderId) {
    try {
        Flight::paymentService()->markPaymentAsCompleted1($orderId);
        Flight::json(["message" => "Payment marked as completed."]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});







/**
 * @OA\Get(
 *     path="/payments/by-order/{orderId}",
 *     tags={"payments"},
 *     summary="Get payment details by order ID",
 *     @OA\Parameter(
 *         name="orderId",
 *         in="path",
 *         required=true,
 *         description="ID of the order",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Payment details retrieved successfully")
 * )
 */

// Get payment details by order ID - WORKS!
Flight::route('GET /payments/by-order/@orderId', function($orderId) {
    Flight::json(Flight::paymentService()->getPaymentByOrderId($orderId));
});








/**
 * @OA\Put(
 *     path="/payments/update-status/{orderId}",
 *     tags={"payments"},
 *     summary="Update the payment status to completed by order ID",
 *     @OA\Parameter(
 *         name="orderId",
 *         in="path",
 *         required=true,
 *         description="ID of the order",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Payment status updated to completed successfully"),
 *     @OA\Response(response="404", description="Payment not found or already completed")
 * )
 */
// Update the payment status to completed - WORKS!
Flight::route('PUT /payments/update-status/@orderId', function($orderId) {
    try {
        Flight::paymentService()->updatePaymentStatusToCompleted($orderId);
        Flight::json(["message" => "Payment status updated to completed."]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 404);
    }
});










/**
 * @OA\Post(
 *     path="/payments/create-for-user/{userId}",
 *     tags={"payments"},
 *     summary="Create a pending payment for a new user account",
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Pending payment created successfully for the new user"),
 *     @OA\Response(response="400", description="Error while creating pending payment")
 * )
 */
// Create a pending payment when a user account is created-WORKS!
Flight::route('POST /payments/create-for-user/@userId', function($userId) {
    try {
        Flight::paymentService()->createPendingPaymentForUser($userId);
        Flight::json(["message" => "Pending payment created for new user."]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});








/**
 * @OA\Delete(
 *     path="/payments/{id}",
 *     tags={"payments"},
 *     summary="Delete a payment by its ID",
 *     @OA\Parameter(
 *         in="path", 
 *         name="id", 
 *         required=true, 
 *         description="The ID of the payment to be deleted",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200", 
 *         description="Payment deleted successfully"
 *     )
 * )
 */

// Delete a payment by its ID
Flight::route('DELETE /payments/@id', function($id) {
    Flight::paymentService()->delete($id);
    Flight::json(["message" => "Payment deleted successfully."]);
  
});


?>
