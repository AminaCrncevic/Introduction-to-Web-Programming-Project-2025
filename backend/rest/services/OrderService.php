<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/OrdersDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class OrderService extends BaseService {
    protected $orderDao;
    protected $orderItemDao;
    protected $productDao;

    public function __construct() {
        $this->orderDao = new OrderDao();
        $this->orderItemDao = new OrderItemDao();
        $this->productDao = new ProductDao();
        parent::__construct($this->orderDao);
    }

    // Ensure a pending order exists or create one -WORKS!
    public function getOrCreatePendingOrder($userId) {
        $orders = $this->orderDao->getOrdersByUserId($userId);
        foreach ($orders as $order) {
            if ($order['OrderStatus'] === 'pending') {
                return $order;
            }
        }
        // Create a new pending order
       $order = [
            "Users_UserID" => $userId,
            "OrderStatus" => "pending"
        ];
        $newOrderId = $this->orderDao->addOrder1($order);
        
    $newOrder = $this->orderDao->getOrderById($newOrderId);
    if (!$newOrder) {
        throw new Exception("Failed to create a new pending order.");
    }
    return $newOrder;

    }

    
    public function addItemToOrder($userId, $productId, $quantity = 1) { 
        $order = $this->getOrCreatePendingOrder($userId);
        if (!$order || !isset($order['id'])) {
            throw new Exception("Pending order could not be retrieved.");
        }
        $orderId = (int)$order['id'];  
        $existingItem = $this->orderItemDao->getItemByOrderAndProduct($orderId, $productId);
        if ($existingItem) {
            
            $newQty = $existingItem['Quantity'] + $quantity;
            $this->orderItemDao->updateQuantity($existingItem['id'], $newQty);
        } else {
            
            $orderItemData = [
                'Orders_OrderID' => $orderId,
                'Products_ProductID' => $productId,
                'Quantity' => $quantity
            ];
            $this->orderItemDao->insertOrderItem($orderItemData);
        }
        $this->recalculateTotal($orderId);
    }
    

    public function removeItemFromOrder($orderItemId, $userId) { 
        $item = $this->orderItemDao->getById($orderItemId);
        if (!$item) throw new Exception("Item not found.");
        $order = $this->orderDao->getOrderById($item['Orders_OrderID']);
        if ($order['Users_UserID'] != $userId) {
            throw new Exception("Unauthorized.");
        }
        if ($order['OrderStatus'] === 'completed') {
            throw new Exception("Cannot modify a completed order.");
        }
        $this->orderItemDao->delete($orderItemId);
        $this->recalculateTotal($order['id']);
    }
    

    // Update item quantity in order
    public function updateItemQuantity($orderItemId, $newQuantity, $userId) { //WORKS!
        if ($newQuantity <= 0) {
            throw new Exception("Quantity must be greater than zero.");
        }
        $item = $this->orderItemDao->getById($orderItemId);
        if (!$item) throw new Exception("Item not found.");

        $order = $this->orderDao->getOrderById($item['Orders_OrderID']);
        if ($order['Users_UserID'] != $userId || $order['OrderStatus'] !== 'pending') {
            throw new Exception("Unauthorized or cannot modify a completed order.");
        }
        $this->orderItemDao->updateQuantity($orderItemId, $newQuantity);
        $this->recalculateTotal($order['id']);
    }



    // Get all items in user's pending order WORKS!
    public function getPendingOrderItems($userId) {
        $order = $this->getOrCreatePendingOrder($userId);
        
        $items = $this->orderItemDao->getOrderItemsByOrderId($order['id']);

        $enrichedItems = [];
        foreach ($items as $item) {
            $product = $this->productDao->getById($item['Products_ProductID']);
            $enrichedItems[] = [
                'id' => $item['id'],
                'orderId' => $item['Orders_OrderID'],
                'productId' => $item['Products_ProductID'],
                'quantity' => $item['Quantity'],
                'productName' => $product['ProductName'],
                'productPrice' => $product['ProductPrice'],
                'productImage' => $product['ProductImage'], 
                'subtotal' => $product['ProductPrice'] * $item['Quantity']
            ];
        }

        return $enrichedItems;
    }



    // Finalize order (to be used after payment later) //WORKS!
    public function completeOrder($orderId, $userId) {
        $order = $this->orderDao->getOrderById($orderId);
        if (!$order || $order['Users_UserID'] != $userId) {
            throw new Exception("Unauthorized.");
        }

        if ($order['OrderStatus'] === 'completed') {
            throw new Exception("Order already completed.");
        }

        // Ensure payment is completed
    $paymentService = new PaymentService();
    $paymentService->markPaymentAsCompleted1($orderId, $order['TotalAmount']); // Mark the payment as completed
    

        $this->orderDao->updateOrder($orderId, ["OrderStatus" => "completed"]);
        
    $this->getOrCreatePendingOrder($userId);  // Create new pending order if none exists after order for specific user is finalized-completed
    
    // Create a new pending payment for the user after order completion
    $paymentService->createPendingPaymentForUser($userId);

}




    // Recalculate order total -WORKS!
    private function recalculateTotal($orderId) {
        $order = $this->orderDao->getOrderById($orderId);
    // Only recalculate for pending orders
    if ($order['OrderStatus'] !== 'pending') {
        return; 
    }
        $items = $this->orderItemDao->getOrderItemsByOrderId($orderId);
        $total = 0;
        foreach ($items as $item) {
         
          $total += $item['Price'] * $item['Quantity']; 
        }

        $this->orderDao->updateOrder($orderId, ['TotalAmount' => $total]);
    }


    /**************************************** */

    public function getOrdersByUserId($userId) {
        
        $orders = $this->orderDao->getOrdersByUserId($userId);
    
        
        if (empty($orders)) {
            throw new Exception("No orders found for user ID: " . $userId);
        }
    
        return $orders;
    }
    
/********************************************** */

public function getOrderById($id) {
$order = $this->orderDao->getOrderById($id);
if (!$order) {
    throw new Exception("Order not found.");
}
return $order;

}
/********************************* */


public function updateOrdersAfterProductPriceChange($productId) {
    
    $orderItems = $this->orderItemDao->getOrderItemsByProductId($productId);

    foreach ($orderItems as $item) {
        $order = $this->orderDao->getOrderById($item['Orders_OrderID']);
        if ($order['OrderStatus'] !== 'pending') {
            continue; 
        }


        $product = $this->productDao->getById($productId);

        $this->orderItemDao->updateOrderItemPrice($item['id'], $product['ProductPrice']);

        $this->recalculateTotal($order['id']);
    }
}


}

?>