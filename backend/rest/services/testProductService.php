<?php
//require_once __DIR__ . '/../dao/ProductDao.php';
//require_once __DIR__ . '/../services/ProductService.php';
require_once 'ProductService.php';


$productService = new ProductService();

// Test: Add a new product - WORKS!
$newProduct = [
    'ProductName' => 'Test Product',
    'ProductPrice' => 29.99,
    'ProductImage' => 'test_product.jpg',
    'ProductDescription' => 'This is a test product.'
];
$addedProduct = $productService->createProduct($newProduct);
echo "Product Added: " . print_r($addedProduct, true) . "\n";

// Test: Get all products  - WORKS!
$allProducts = $productService->getAllProducts();
echo "All Products: " . print_r($allProducts, true) . "\n";


// Test: Get a specific product by ID - WORKS!
$singleProduct = $productService->getProduct(7);
echo "Single Product by ID: " . print_r($singleProduct, true) . "\n";

// Test: Update the product's details - works!
$updatedProduct = [
    'ProductName' => 'Updated Product',
    'ProductPrice' => 35.99,
    'ProductImage' => 'updated_product.jpg',
    'ProductDescription' => 'This is an updated test product.'
];
$productService->updateProduct(7, $updatedProduct);
$updatedProductDetails = $productService->getProduct(7);
echo "Updated Product: " . print_r($updatedProductDetails, true) . "\n";

// Test: Delete the product - works!
$productService->deleteProduct(7);
$deletedProduct = $productService->getProduct(7);
echo "Deleted Product (should be null or error): " . print_r($deletedProduct, true) . "\n";

?>
