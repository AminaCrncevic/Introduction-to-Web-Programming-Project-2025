<?php
require_once 'vendor/autoload.php';
require_once 'data/roles.php';  
require_once 'middleware/AuthMiddleware.php';  

/**
 * @OA\Get(
 *     path="/product/{id}",
 *     tags={"products"},
 *     summary="Get a specific product by ID",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the product details"
 *     ),
 *     @OA\Response(
*         response=404,
*         description="Product not found"
*     )
 * )
 */

// Get a specific product by ID - GET - WORKS!
Flight::route('GET /product/@id', function($id){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
    try{ 
    Flight::json(Flight::productService()->getProduct($id));
} catch (Exception $e) {
    Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 400);
}
});





/**
 * @OA\Get(
 *     path="/product",
 *     tags={"products"},
 *     summary="Get all products",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Response(
 *         response=200,
 *         description="Returns all products"
 *     )
 * )
 */

// Get all products 
Flight::route('GET /product', function(){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
    Flight::json(Flight::productService()->getAllProducts());
});




/**
 * @OA\Post(
 *     path="/product",
 *     tags={"products"},
 *     summary="Create a new product",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"ProductName", "ProductPrice","ProductImage", "ProductDescription"}, 
 *             @OA\Property(property="ProductName", type="string", example="Rose Bouquet"),
 *             @OA\Property(property="ProductPrice", type="number", format="float", example=29.99),
 *             @OA\Property(property="ProductImage", type="string", example="rose_bouquet.jpg"),
 *             @OA\Property(property="ProductDescription", type="string", example="A beautiful bouquet of red roses")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error creating product"
 *     )
 * )
 */

// Add a new product - WORKS! -POST
Flight::route('POST /product', function(){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
      // Validate image URL exists
    if (empty($data['ProductImage']) || !filter_var($data['ProductImage'], FILTER_VALIDATE_URL)) {
        Flight::json(['error' => 'Valid ProductImage URL is required'], 400);
        return;
    }
    try {
        $product = Flight::productService()->createProduct($data);
        Flight::json($product, 201); 
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});




/**
 * @OA\Put(
 *     path="/product/{id}",
 *     tags={"products"},
 *     summary="Update a product completely",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"ProductName", "ProductPrice", "ProductImage", "ProductDescription"},
 *             @OA\Property(property="ProductName", type="string", example="Updated Product Name"),
 *             @OA\Property(property="ProductPrice", type="number", format="float", example=39.99),
 *             @OA\Property(property="ProductImage", type="string", example="UpdatedProductImage.jpg"),
 *             @OA\Property(property="ProductDescription", type="string", example="Updated product description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error updating product"
 *     )
 * )
 */

// Update a product fully (PUT) -WORKS
Flight::route('PUT /product/@id', function($id){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    try {
        $product = Flight::productService()->updateProduct($id, $data);
        Flight::json($product);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});






/**
 * @OA\Patch(
 *     path="/product/{id}",
 *     tags={"products"},
 *     summary="Partially update a product",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="ProductName", type="string", example="New Name Only"),
 *             @OA\Property(property="ProductPrice", type="number", format="float", example=49.99),
 *             @OA\Property(property="ProductImage", type="string", example="UpdatedProductImage.jpg"),
 *             @OA\Property(property="ProductDescription", type="string", example="Changed description only")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product partially updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error updating product"
 *     )
 * )
 */

// Partial update (PATCH) — optional to implement
Flight::route('PATCH /product/@id', function($id){
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    try {
        
        $existing = Flight::productService()->getProduct($id);
        $merged = array_merge($existing, $data);
        $product = Flight::productService()->updateProduct($id, $merged);
        Flight::json($product);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});





/**
 * @OA\Delete(
 *     path="/product/{id}",
 *     tags={"products"},
 *     summary="Delete a product by ID",
 * security={
    *         {"ApiKey": {}}
    *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=8)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product deleted successfully"
 *     )
 * )
 */

// Delete a product
Flight::route('DELETE /product/@id', function($id){
     Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN]);
    Flight::json(Flight::productService()->deleteProduct($id));
});


?>
