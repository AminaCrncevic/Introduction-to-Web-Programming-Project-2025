
<?php
require_once 'vendor/autoload.php';
require_once 'data/Roles.php';  // Include the Roles class
require_once 'middleware/AuthMiddleware.php';  // Include the AuthMiddleware
/**
 * @OA\Get(
 *     path="/users",
 *     tags={"users"},
 *     summary="Get all users",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of all users"
 *     )
 * )
 */

// Get all users - WORKS!
Flight::route('GET /users', function(){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);
     Flight::json(Flight::userService()->getAllUsers());
});










/**
 * @OA\Get(
 *     path="/user/{id}",
 *     tags={"users"},
 *     summary="Get a specific user by ID",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user details"
 *     ),
 *   @OA\Response(
 *         response=403,
 *         description="Forbidden: Access denied"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */

// Get user by ID - WORKS!
Flight::route('GET /user/@id', function($id){
        Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
              $user = Flight::get('user');
 // Only allow if the requester is the same user or an admin
        if ($user->UserType !== Roles::ADMIN && $user->id != $id) {
            Flight::json(['error' => 'Forbidden: You are not allowed to access this resource.'], 403);
            return;
    }
    try {
        Flight::json(Flight::userService()->getUser($id));
    } catch (Exception $e) {
        
        Flight::json(['error' => $e->getMessage()], $e->getCode());
    }
});












/**
 * @OA\Post(
 *     path="/user",
 *     tags={"users"},
 *     summary="Create a new user (register)",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"FirstName", "LastName", "email", "Password", "UserType"}, 
 *             @OA\Property(property="FirstName", type="string", example="Amina"),
 *             @OA\Property(property="LastName", type="string", example="Crncevic"),
 *             @OA\Property(property="email", type="string", example="amina.crncevic@example.com"),
 *             @OA\Property(property="Password", type="string", example="password123"),
 *             @OA\Property(property="UserType", type="string", example="customer")      
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error creating user"
 *     )
 * )
 */

 //Create new user (register) - WORKS!
Flight::route('POST /user', function(){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
   $data = Flight::request()->data->getData();
   try {
        $userId = Flight::userService()->createUser($data);
        Flight::json(['user_id' => $userId], 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});







/**
 * @OA\Put(
 *     path="/user/{id}",
 *     tags={"users"},
 *     summary="Update a user completely",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"FirstName", "LastName", "email", "Password", "UserType"},
 *             @OA\Property(property="FirstName", type="string", example="NewFirstName"),
 *             @OA\Property(property="LastName", type="string", example="NewLastName"),
 *             @OA\Property(property="email", type="string", example="new.password@example.com"),
 *             @OA\Property(property="Password", type="string", example="newpassword"),
 *             @OA\Property(property="UserType", type="string", example="admin")      
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error updating user"
 *     )
 * )
 */

// Update user completely - WORKS!
Flight::route('PUT /user/@id', function($id){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->updateUser($id, $data));
});




/**
 * @OA\Patch(
 *     path="/user/{id}",
 *     tags={"users"},
 *     summary="Partially update a user (e.g., only name)",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="FirstName", type="string", example="New Name"),
 *            
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User partially updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error updating user"
 *     )
 * )
 */

// Partially update user (e.g., only name) - WORKS!
Flight::route('PATCH /user/@id', function($id){
     Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
      $user = Flight::get('user');
       $data = Flight::request()->data->getData();

 // Only allow if the requester is the same user or an admin
 if ($user->UserType !== Roles::ADMIN && (int)$user->id != (int)$id) {
     Flight::json(['error' => 'Forbidden: You are not allowed to access this resource.'], 403);
     return;
    }
$data = array_change_key_case($data, CASE_LOWER);
if ($user->UserType !== Roles::ADMIN) {
    unset($data['usertype'], $data['email'], $data['password']);
}
 
    Flight::json(Flight::userService()->updateUser($id, $data)); 
});






/**
 * @OA\Delete(
 *     path="/user/{id}",
 *     tags={"users"},
 *     summary="Delete a user by ID",
 * *  security={
*      {"ApiKey": {}}
*     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */

// Delete user - WORKS!
Flight::route('DELETE /user/@id', function($id){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
    try {
        Flight::userService()->deleteUser($id);
        Flight::json(["message" => "User deleted successfully."]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 404);
    }
});






/**
 * @OA\Post(
 *     path="/user/login",
 *     tags={"users"},
 *     summary="User login",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "Password"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="Password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User logged in successfully"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

// User login - WORKS!
Flight::route('POST /user/login', function(){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
    try {
        $data = Flight::request()->data->getData();
        $user = Flight::userService()->login($data['email'], $data['Password']);
        Flight::json($user);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 401); 
    }
});



?>
