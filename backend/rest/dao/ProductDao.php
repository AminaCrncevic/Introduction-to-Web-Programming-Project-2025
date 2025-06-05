<?php
require_once 'BaseDao.php';

class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct("Products");
    }

    // Get product by ID
    public function getProductById($id) {
        return $this->getById($id);  // Uses BaseDao's getById method
    }

    // Get all products
    public function getAllProducts() {
        return $this->getAll();  // Uses BaseDao's getAll method
    }

    // Add new product
    public function addProduct($product) {
        return $this->insert($product);  // Uses BaseDao's insert method
    }

    // Update product by ID
    public function updateProduct($id, $product) {
        return $this->update($id, $product);  // Uses BaseDao's update method
    }

    // Delete product by ID
    public function deleteProduct($id) {
        return $this->delete($id);  // Uses BaseDao's delete method
    }
 
    public function countAll() {
    return $this->queryValue("SELECT COUNT(*) FROM products", []);
}

}
?>
