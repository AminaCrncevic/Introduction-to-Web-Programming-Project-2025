
<?php

/**
 * @OA\Post(
 *      path="/wishlist",
 *      tags={"wishlist"},
 *      summary="Create a wishlist for a user",
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *              required={"user_id"},
 *              @OA\Property(property="user_id", type="integer", example=1)
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Wishlist created"
 *      ),
 *       @OA\Response(
 *          response=400,
 *          description="Error creating wishlist (e.g., user already has a wishlist)"
 *      )
 * )
 */

//CREATE A WISHLIST FOR THE USER IF USER DOES NOT HAVE ONE ALREADY - WORKS!
Flight::route('POST /wishlist', function () { 
   try {
    $data = Flight::request()->data->getData();
    $wishlist = Flight::wishlistService()->createWishlistForUser($data['user_id']);
    Flight::json($wishlist);
} catch (Exception $e) {
    Flight::json(["error" => $e->getMessage()], 400); 
}
});





/**
 * @OA\Get(
 *      path="/wishlist/{user_id}",
 *      tags={"wishlist"},
 *      summary="Get wishlist by user ID",
 *      @OA\Parameter(in="path", name="user_id", required=true, @OA\Schema(type="integer")),
 *      @OA\Response(response=200, description="Wishlist for user"),
 * @OA\Response(response=404, description="Wishlist not found"),
 * @OA\Response(response=500, description="Internal Server Error")
*     )
 */

 //GET WISHLIST BY USER ID - WORKS!
Flight::route('GET /wishlist/@user_id', function ($user_id) {
   try {
    Flight::json(Flight::wishlistService()->getWishlistByUserId($user_id));
} catch (Exception $e) {
    Flight::json(["error" => $e->getMessage()], $e->getCode() ?: 500);
}
});








/**
 * @OA\Delete(
 *      path="/wishlist/{id}",
 *      tags={"wishlist"},
 *      summary="Delete wishlist by ID",
 *      @OA\Parameter(in="path", name="id", required=true, @OA\Schema(type="integer")),
 *      @OA\Response(response=200, description="Wishlist deleted")
 * )
 */
//DELETE WISHLIST BY ID - WORKS!
Flight::route('DELETE /wishlist/@id', function ($id) {
    Flight::wishlistService()->deleteWishlist($id);
    Flight::json(["message" => "Wishlist deleted successfully."]);
});






?>
