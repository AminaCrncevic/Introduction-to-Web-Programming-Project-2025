<?php
/*
 require_once 'BaseDao.php'; 

 class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("OrderItem");
    }

    // Get order items by OrderID
    public function getOrderItemsByOrderId($orderId) {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table . " WHERE Orders_OrderID = :orderId");
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(); // Fetch all order items for a specific order
    }

    // Insert a new order item
    public function insertOrderItem($orderItemData) {
        // Check if the order is pending
        $orderDao = new OrderDao();
        $order = $orderDao->getOrderById($orderItemData['Orders_OrderID']);
        if (!$order || $order['OrderStatus'] != 'pending') {
            echo "Cannot insert an item into a non-pending order.";
            return false;  // Prevent insertion if the order is not pending
        }

        // Check if the product already exists in the order and update quantity if it does
        $stmt = $this->connection->prepare("
            SELECT id, Quantity FROM OrderItem 
            WHERE Orders_OrderID = :orderId AND Products_ProductID = :productId
        ");
        $stmt->bindParam(':orderId', $orderItemData['Orders_OrderID'], PDO::PARAM_INT);
        $stmt->bindParam(':productId', $orderItemData['Products_ProductID'], PDO::PARAM_INT);
        $stmt->execute();
        
        $existingOrderItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingOrderItem) {
            // Update the quantity if the product is already in the order
            $newQuantity = $existingOrderItem['Quantity'] + $orderItemData['Quantity'];
            $stmt = $this->connection->prepare("
                UPDATE OrderItem 
                SET Quantity = :quantity,
                 Price = (SELECT ProductPrice FROM Products WHERE id = :productId) 
    WHERE id = :orderItemId");
            $stmt->bindParam(':quantity', $newQuantity, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $orderItemData['Products_ProductID'], PDO::PARAM_INT);
            $stmt->bindParam(':orderItemId', $existingOrderItem['id'], PDO::PARAM_INT);
            $stmt->execute();
            
            echo "Product already in order, quantity updated.\n";
        } else {
            // Insert a new order item if the product is not in the order
            $stmt = $this->connection->prepare("
                INSERT INTO OrderItem (Orders_OrderID, Products_ProductID, Quantity, Price) 
                VALUES (:orderId, :productId, :quantity, 
                    (SELECT ProductPrice FROM Products WHERE id = :productId))
            ");
            $stmt->bindParam(':orderId', $orderItemData['Orders_OrderID'], PDO::PARAM_INT);
            $stmt->bindParam(':productId', $orderItemData['Products_ProductID'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $orderItemData['Quantity'], PDO::PARAM_INT);
            $stmt->execute();
    
            echo "New order item inserted.\n";
            // **Update total order amount**
    $this->updateTotalOrderAmount($orderItemData['Orders_OrderID']);
        }
    }


 // Update an existing order item only if the order is pending
 public function updateOrderItem($orderItemId, $quantity) {
    // Check if the order associated with the order item is pending
    $stmt = $this->connection->prepare("SELECT Orders_OrderID FROM OrderItem WHERE id = :orderItemId");
    $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
    $stmt->execute();
    $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$orderItem) {
        echo "Order item not found.\n";
        return false;
    }

    $orderDao = new OrderDao();
    $order = $orderDao->getOrderById($orderItem['Orders_OrderID']);

    if ($order['OrderStatus'] != 'pending') {
        echo "Cannot update an order item in a non-pending order.\n";
        return false;  // Prevent update if the order is not pending
    }

    // Update the quantity of an existing order item
    $stmt = $this->connection->prepare("
        UPDATE OrderItem 
        SET Quantity = :quantity 
        WHERE id = :orderItemId
    ");
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
    $stmt->execute();

    echo "Order item updated successfully.\n";
    // **Update total order amount**
    $this->updateTotalOrderAmount($orderItem['Orders_OrderID']);
}

// Delete an order item only if the order is pending
public function deleteOrderItem($orderItemId) {
    // Check if the order associated with the order item is pending
    $stmt = $this->connection->prepare("SELECT Orders_OrderID FROM OrderItem WHERE id = :orderItemId");
    $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
    $stmt->execute();
    $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderItem) {
        echo "Order item not found.\n";
        return false;
    }

    $orderDao = new OrderDao();
    $order = $orderDao->getOrderById($orderItem['Orders_OrderID']);

    if ($order['OrderStatus'] != 'pending') {
        echo "Cannot delete an order item in a non-pending order.\n";
        return false;  // Prevent deletion if the order is not pending
    }

    // Delete the specified order item
    $stmt = $this->connection->prepare("
        DELETE FROM OrderItem WHERE id = :orderItemId
    ");
    $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
    $stmt->execute();

    echo "Order item deleted successfully.\n";
     // **Update total order amount**
     $this->updateTotalOrderAmount($orderItem['Orders_OrderID']);
}


private function updateTotalOrderAmount($orderId) {
    // Sum all order item subtotals
    $stmt = $this->connection->prepare("
        SELECT SUM(Subtotal) AS totalAmount FROM OrderItem WHERE Orders_OrderID = :orderId
    ");
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalAmount = $result['totalAmount'] ?? 0; // Default to 0 if no items

    // Update total amount in the Orders table
    $stmt = $this->connection->prepare("
        UPDATE Orders 
        SET TotalAmount = :totalAmount 
        WHERE id = :orderId
    ");
    $stmt->bindParam(':totalAmount', $totalAmount, PDO::PARAM_STR);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();
}


 
}*/

/************************************************************ */


require_once 'BaseDao.php';

class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("OrderItem");
    }


// Get all order items (uses BaseDao's getAll method)
public function getAllOrderItems() {
    return $this->getAll();  // Inherits the getAll() from BaseDao
}


// Get an order item by ID (uses BaseDao's getById method)
public function getOrderItemById($id) {
    return $this->getById($id);  // Inherits the getById() from BaseDao
}

// Get order items by OrderID - for specific order
public function getOrderItemsByOrderId($orderId) {
$stmt = $this->connection->prepare("SELECT * FROM " . $this->table . " WHERE Orders_OrderID = :orderId");
$stmt->bindParam(':orderId', $orderId);
$stmt->execute();
return $stmt->fetchAll(); 
}


// Insert a new order item (No logic, just insert)
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
    return $this->update($id, $orderItem);  // BaseDao's update method
}


// Delete an order item (No checks or logic, just delete)
public function deleteOrderItem($orderItemId) {
        $stmt = $this->connection->prepare("
            DELETE FROM OrderItem WHERE id = :orderItemId
        ");
        $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>
