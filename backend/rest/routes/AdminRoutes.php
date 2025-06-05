
<?php
require_once 'vendor/autoload.php';
require_once 'data/roles.php';  
require_once 'middleware/AuthMiddleware.php';  
/**
 * @OA\Get(
 *     path="/admin/dashboard",
 *     tags={"admin"},
 *     summary="Get admin dashboard statistics",
 *     security={{"ApiKey":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Dashboard statistics fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="pending_orders", type="integer"),
 *             @OA\Property(property="completed_payments", type="number", format="float"),
 *             @OA\Property(property="total_orders", type="integer"),
 *             @OA\Property(property="total_products", type="integer"),
 *             @OA\Property(property="normal_users", type="integer"),
 *             @OA\Property(property="admin_users", type="integer"),
 *             @OA\Property(property="total_accounts", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Access denied"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /admin/dashboard', function () {
    try {
        Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);
        $user = Flight::get('user');
        if (!isset($user->UserType)) /* || $user->UserType !== 'admin') {*/{  
            Flight::halt(403, 'Access denied');
        }

        $stats = Flight::adminService()->getDashboardStats();
        Flight::json($stats);
    } catch (Exception $e) {
        
        Flight::json(['error' => $e->getMessage()], 500);
    }
});








/**
 * @OA\Patch(
 *     path="/admin/orders/{orderId}/payment",
 *     tags={"admin"},
 *     summary="Update payment status for a specific order",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="orderId",
 *         in="path",
 *         required=true,
 *         description="ID of the order to update payment status",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"status"},
 *             @OA\Property(property="status", type="string", enum={"pending", "completed"}, description="New payment status")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment status updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Payment status updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or failed update",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Invalid payment status.")
 *         )
 *     )
 * )
 */
Flight::route('PATCH /admin/orders/@orderId/payment', function ($orderId) {
 Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);

    $data = Flight::request()->data->getData();
    $newStatus = $data['status'] ?? null;

    if (!in_array($newStatus, ['pending', 'completed'])) {
        Flight::halt(400, 'Invalid payment status.');
    }


    try {
        Flight::paymentService()->updatePaymentStatus($orderId, $newStatus);
        Flight::json(['message' => 'Payment status updated successfully.']);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});



