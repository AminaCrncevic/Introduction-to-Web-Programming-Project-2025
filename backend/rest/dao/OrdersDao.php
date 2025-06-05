<?php

//The user can modify his order (add/remove items).
//As a finalized order → When the user proceeds to checkout, enters payment details, and confirms payment,
// the order is marked as completed.

require_once 'BaseDao.php';

class OrderDao extends BaseDao {
    public function __construct() {
        parent::__construct("Orders"); 
    }

    // Get order by ID
    public function getOrderById($id) {
        return $this->getById($id);  
    }

    // Add a new order
    public function addOrder($order) {
        return $this->insert($order);  
    }
    
    public function addOrder1($orderData) {
        $sql = "INSERT INTO orders (Users_UserID, OrderStatus) VALUES (:userId, :status)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':userId', $orderData['Users_UserID']);
        $stmt->bindParam(':status', $orderData['OrderStatus']);
        $stmt->execute();
        
        // Return the last inserted ID
        return $this->connection->lastInsertId();  
    }
    

    // Update order by ID 
    public function updateOrder($id, $order) {
        return $this->update($id, $order);  
    }

    // Delete order by ID 
    public function deleteOrder($id) {
        return $this->delete($id);  
    }

    // Get orders by user ID
    public function getOrdersByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM Orders WHERE Users_UserID = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll();  
    }

        // Get a pending order for a user
    public function getPendingOrderByUserId($userId) {
     $stmt = $this->connection->prepare("SELECT * FROM Orders WHERE Users_UserID = :userId AND OrderStatus = 'pending'");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);  // Return the first row (pending order) for the user
        }
    
    
    public function countAllOrders() {
    return $this->queryValue("SELECT COUNT(*) FROM orders", []);
}

    public function countOrdersByStatus($status) {
    return $this->queryValue("SELECT COUNT(*) FROM orders WHERE OrderStatus = ?", [$status]);
}

}

?>