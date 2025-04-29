<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/OrdersDao.php';

class OrderItemService extends BaseService {
    protected $dao;
    protected $orderDao;

    public function __construct() {
        $this->dao = new OrderItemDao();
        $this->orderDao = new OrderDao();
        parent::__construct($this->dao);
    }

    // Get all items for a specific order
    public function getItemsByOrderId($orderId) {
        return $this->dao->getOrderItemsByOrderId($orderId);
    }

    // Add item to a user's pending order (cart)
    public function addItemToCart($userId, $productId, $quantity) {
        $pendingOrder = $this->getOrCreatePendingOrder($userId);

        $itemData = [
            "Orders_OrderID" => $pendingOrder['id'],
            "Products_ProductID" => $productId,
            "Quantity" => $quantity
        ];

        $this->dao->insertOrderItem($itemData);
    }

    // Update quantity of an item
    public function updateCartItem($orderItemId, $newQuantity) {
        if ($newQuantity < 1) {
            throw new Exception("Quantity must be at least 1.");
        }
        $this->dao->updateOrderItem($orderItemId, ["Quantity" => $newQuantity]);
    }

    // Delete item from cart
    public function removeItemFromCart($orderItemId) {
        $this->dao->deleteOrderItem($orderItemId);
    }

    // Get or create pending order for user
    private function getOrCreatePendingOrder($userId) {
        $orders = $this->orderDao->getOrdersByUserId($userId);
        foreach ($orders as $order) {
            if ($order['OrderStatus'] === 'pending') {
                return $order;
            }
        }

        // No pending order found, create one
        $newOrder = [
            "Users_UserID" => $userId,
            "OrderStatus" => "pending"
        ];
        $newOrderId = $this->orderDao->addOrder($newOrder);
        return $this->orderDao->getOrderById($newOrderId);
    }
}
