
<?php
/*use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthMiddleware {
   public function verifyToken($token){
       if(!$token)
           Flight::halt(401, "Missing authentication header");
       $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
       Flight::set('user', $decoded_token->user);
       Flight::set('jwt_token', $token);
       return TRUE;
   }
   public function authorizeRole($requiredRole) {
       $user = Flight::get('user');
       if ($user->role !== $requiredRole) {
           Flight::halt(403, 'Access denied: insufficient privileges');
       }
   }
   public function authorizeRoles($roles) {
       $user = Flight::get('user');
       if (!in_array($user->role, $roles)) {
           Flight::halt(403, 'Forbidden: role not allowed');
       }
   }
   function authorizePermission($permission) {
       $user = Flight::get('user');
       if (!in_array($permission, $user->permissions)) {
           Flight::halt(403, 'Access denied: permission missing');
       }
   }   
}*/

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
   
   // Verify the token and decode it
   public function verifyToken($token){
     //Checks if a token exists.
       if(!$token)
           Flight::halt(401, "Missing authentication header");

       // Decode the token Decodes the token using the secret and algorithm (HS256)
       $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
       
       // Set user information and JWT token in the flight context /Stores the decoded user info in the
       // Flight context (Flight::set('user', ...)) 
       //so it can be accessed later in route handlers or other middleware.
       Flight::set('user', $decoded_token->user);
       Flight::set('jwt_token', $token);
       
       return TRUE;
   }

   // Authorize user based on UserType
   //Ensures only users with a specific UserType can proceed.
   public function authorizeUserType($requiredUserType) {
       $user = Flight::get('user');
       if ($user->UserType !== $requiredUserType) {
           Flight::halt(403, 'Access denied: insufficient privileges');
       }
   }

   // Authorize user based on multiple allowed UserTypes
   public function authorizeUserTypes($userTypes) {
       $user = Flight::get('user');
       if (!in_array($user->UserType, $userTypes)) {
           Flight::halt(403, 'Forbidden: UserType not allowed');
       }
   }

   // Authorize user based on specific permissions
   function authorizePermission($permission) {
       $user = Flight::get('user');
       if (!in_array($permission, $user->permissions)) {
           Flight::halt(403, 'Access denied: permission missing');
       }
   }   
}
