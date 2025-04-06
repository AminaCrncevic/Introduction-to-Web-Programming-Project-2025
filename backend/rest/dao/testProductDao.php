<?php
require_once 'ProductDao.php';

// Create ProductDao instance
$productDao = new ProductDao();
try{ 

// Insert a new product - WORKS!
$product = [
    'ProductName' => 'Tulips Bouquet',
    'ProductPrice' => 21.99,
    'ProductImage' => 'https://cdn.igp.com/f_auto,q_auto,t_pnopt12prodlp/products/p-elegant-rose-bouquet-139330-m.jpg',
    'ProductDescription' => 'A beautiful bouquet of tulips for loved ones.'
];
$productDao->addProduct($product);
echo "Product added successfully!\n";

// Get a product by ID - WORKS!
$product = $productDao->getProductById(5);  
echo "Product:\n";
print_r($product);

// Get all products - WORKS!
$products = $productDao->getAllProducts();
echo "All products:\n";
print_r($products);

// Update a product by ID - WORKS!
$productDao->updateProduct(2, [
    'ProductName' => 'Updated Rose Bouquet',
    'ProductPrice' => 34.99,
    'ProductImage' => 'https://cdn.igp.com/f_auto,q_auto,t_pnopt12prodlp/products/p-elegant-rose-bouquet-139330-m.jpg',
    'ProductDescription' => 'An updated beautiful bouquet of roses.'
]);
echo "Product updated successfully!\n";

// Delete a product by ID - WORKS!
$productDao->deleteProduct(1);
echo "Product deleted successfully!\n"; 
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
 }

?>
