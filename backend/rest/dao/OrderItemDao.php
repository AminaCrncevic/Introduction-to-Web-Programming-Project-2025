<?php
require_once 'BaseDao.php';

class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("OrderItem");
    }

// Get all order items (uses BaseDao's getAll method)
public function getAllOrderItems() {
    return $this->getAll();  
}

// Get an order item by ID (uses BaseDao's getById method)
public function getOrderItemById($id) {
    return $this->getById($id);  
}
public function getOrderItemById1($id) {
    $stmt = $this->connection->prepare("SELECT oi.*, o.Users_UserID 
                                        FROM orderitem oi 
                                        JOIN orders o ON oi.Orders_OrderID = o.id 
                                        WHERE oi.id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        error_log("Order Item Found: " . print_r($result, true));  
    } else {
        error_log("No Order Item Found for ID: " . $id);  
    }

    return $result;
}

// Get order items by OrderID - for specific order
public function getOrderItemsByOrderId($orderId) {
$stmt = $this->connection->prepare("SELECT * FROM OrderItem WHERE Orders_OrderID = :orderId");
$stmt->bindParam(':orderId', $orderId);
$stmt->execute();
return $stmt->fetchAll(); 
}


// Insert a new order item 
public function insertOrderItem($orderItemData) {
     $stmt = $this->connection->prepare("
     INSERT INTO OrderItem (Orders_OrderID, Products_ProductID, Quantity, Price) 
            VALUES (:orderId, :productId, :quantity, 
                (SELECT ProductPrice FROM Products WHERE id = :productId))
        ");
        $stmt->bindParam(':orderId', $orderItemData['Orders_OrderID'], PDO::PARAM_INT);
        $stmt->bindParam(':productId', $orderItemData['Products_ProductID'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $orderItemData['Quantity'], PDO::PARAM_INT);
        $stmt->execute();
    }

// Update order item by ID
public function updateOrderItem($id, $orderItem) {
    return $this->update($id, $orderItem);  
}


// Delete an order item 
public function deleteOrderItem($orderItemId) {
        $stmt = $this->connection->prepare("
            DELETE FROM OrderItem WHERE id = :orderItemId
        ");
        $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
        $stmt->execute();
    
    }



// Get a specific item by OrderID and ProductID
public function getItemByOrderAndProduct($orderId, $productId) {
    $stmt = $this->connection->prepare("
        SELECT * FROM OrderItem 
        WHERE Orders_OrderID = :orderId AND Products_ProductID = :productId
    ");
    $stmt->bindParam(':orderId', $orderId);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();
    return $stmt->fetch();
}

// Update the quantity of a specific order item
public function updateQuantity($orderItemId, $newQuantity) {
    $stmt = $this->connection->prepare("
        UPDATE `OrderItem` 
        SET `Quantity` = :newQuantity
        WHERE `id` = :orderItemId
    ");
    $stmt->bindParam(':newQuantity', $newQuantity, PDO::PARAM_INT);
    $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
    $stmt->execute();
}


/*************************************** */
// Get all order items for a specific product
public function getOrderItemsByProductId($productId) {
    $stmt = $this->connection->prepare("SELECT * FROM OrderItem WHERE Products_ProductID = ?");
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update price of an order item
public function updateOrderItemPrice($orderItemId, $newPrice) {
    $stmt = $this->connection->prepare("UPDATE OrderItem SET Price = ? WHERE id = ?");
    $stmt->execute([$newPrice, $orderItemId]);
}

}

?>
