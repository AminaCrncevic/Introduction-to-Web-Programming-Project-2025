<?php

//Orders table will serve two purposes:
//As a cart → When a user adds items to the cart, an order is created with a pending status. 
//The user can modify this order (add/remove items).
//As a finalized order → When the user proceeds to checkout, enters payment details, and confirms payment,
// the order is marked as completed.

require_once 'BaseDao.php';

class OrderDao extends BaseDao {
    public function __construct() {
        parent::__construct("Orders"); // Pass "Orders" table name to BaseDao constructor
    }

    // Get order by ID
    public function getOrderById($id) {
        return $this->getById($id);  // Use BaseDao's getById method
    }

    // Add a new order
    public function addOrder($order) {
        return $this->insert($order);  // Use BaseDao's insert method
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
    

    // Update order by ID (simple update operation)
    public function updateOrder($id, $order) {
        return $this->update($id, $order);  // Use BaseDao's update method
    }

    // Delete order by ID (simple delete operation)
    public function deleteOrder($id) {
        return $this->delete($id);  // Use BaseDao's delete method
    }

    // Get orders by user ID
    public function getOrdersByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table . " WHERE Users_UserID = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll();  // Fetch all orders for a specific user
    }

        // Get a pending order for a user
    public function getPendingOrderByUserId($userId) {
     $stmt = $this->connection->prepare("SELECT * FROM Orders WHERE Users_UserID = :userId AND OrderStatus = 'pending'");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);  // Return the first row (pending order) for the user
        }
}


?>