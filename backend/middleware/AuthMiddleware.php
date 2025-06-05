<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
   
   
   public function verifyToken($token){
       if(!$token)
           Flight::halt(401, "Missing authentication header");
       // Decode the token Decodes the token using the secret and algorithm (HS256)
       $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
       Flight::set('user', $decoded_token->user);
       Flight::set('jwt_token', $token);
       return TRUE;
   }



   //Authorize user based on UserType
   //only users with a specific UserType can proceed.
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
