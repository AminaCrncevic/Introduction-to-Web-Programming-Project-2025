<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';
require_once 'OrderService.php';

class ProductService extends BaseService {
       /**  @var ProductDao */
        protected $dao;

    public function __construct() {
        $this->dao = new ProductDao();
        parent::__construct($this->dao);
      
    }


    
    public function createProduct($data) {
        
        if (empty($data['ProductName'])) {
            throw new Exception("Product name is required");
        }

        
        if (!isset($data['ProductPrice']) || !is_numeric($data['ProductPrice']) || $data['ProductPrice'] <= 0) {
            throw new Exception("Product price must be a positive number");
        }

        
        if (empty($data['ProductImage'])) {
         throw new Exception("Product Image is required!");
        }

        return $this->dao->addProduct($data);
    }





    // Update an existing product
    public function updateProduct($id, $data) {
        if (!isset($data['ProductPrice']) || !is_numeric($data['ProductPrice']) || $data['ProductPrice'] <= 0) {
            throw new Exception("Product price must be a positive number");
        }

        if (empty($data['ProductImage'])) {
            $data['ProductImage'] = 'default.jpg'; 
        }
        
$result = $this->dao->updateProduct($id, $data);

$orderService = new OrderService();
$orderService->updateOrdersAfterProductPriceChange($id);
       return $result;
    }




    // Get single product
    public function getProduct($id) {
        $product = $this->dao->getProductById($id);
        if (!$product) {
            throw new Exception("Product not found", 404);
        }
        return $this->dao->getProductById($id);
    }



    // Get all products
    public function getAllProducts() {
        return $this->dao->getAllProducts();
    }


    // Delete product
    public function deleteProduct($id) {
        return $this->dao->deleteProduct($id);
    }
}
?>
