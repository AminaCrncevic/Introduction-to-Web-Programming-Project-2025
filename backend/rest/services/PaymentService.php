<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/PaymentsDao.php';
require_once __DIR__ . '/../dao/OrdersDao.php';

class PaymentService extends BaseService {
    protected $paymentDao;
    protected $orderDao;

    public function __construct() {
        $this->paymentDao = new PaymentDao();
        $this->orderDao = new OrderDao();
        parent::__construct($this->paymentDao);  

    }


    // Get the pending payment for a user - WORKS!
    public function getPendingPaymentForUser($userId) {
        
        $order = $this->orderDao->getPendingOrderByUserId($userId); // Fetch pending order
        if (!$order) {
            throw new Exception("No pending order found for this user.");
        }
        $payment = $this->paymentDao->getPaymentByOrderId($order['id']);  // Fetch payment by order ID
        if (!$payment) {
            throw new Exception("No pending payment found for this user.");
        }
        // Check if payment status is  'pending'
    if ($payment['PaymentStatus'] !== 'pending') {
        throw new Exception("No pending payment found. Current payment status: " . $payment['PaymentStatus']);
    }
        return $payment;
    }





    // Mark the order as paid and update the payment status to 'completed'-WORKS!
    public function markPaymentAsCompleted1($orderId) {
        
        $order = $this->orderDao->getOrderById($orderId);
        if (!$order) {
            throw new Exception("Order not found.");
        }
        
        $payment = $this->paymentDao->getPaymentByOrderId($orderId);
        if (!$payment) {
            throw new Exception("Payment not found for this order.");
        }
        
        if ($payment['PaymentStatus'] !== 'pending') {
            throw new Exception("Payment is not in a pending state.");
        }
        
        $totalAmount = $order['TotalAmount'];  
        
        $this->paymentDao->updatePaymentAmount($payment['id'], $totalAmount);
        $this->paymentDao->updatePaymentStatusByPaymentID($payment['id'], 'completed');
    }





    // Create a pending payment for a given order - WORKS! 
    public function createPendingPaymentForOrder($orderId, $userId) {
        
        $existingPayment = $this->paymentDao->getPaymentByOrderId($orderId);
        if ($existingPayment) {
            throw new Exception("A pending payment already exists for this order.");
        }
        
        $amountPaid = 0.00;
        $this->paymentDao->createPayment($orderId, $amountPaid);
    }


    // Create new pending payment after user creation
    public function createPendingPaymentForUser($userId) {
         
        $orderService = new OrderService();
        $order = $orderService->getOrCreatePendingOrder($userId);
        
        $paymentDao = new PaymentDao();
        return $paymentDao->createPayment($order['id'], 0.00); 
    }



   


    public function getPaymentByOrderId($orderId) {
        return $this->paymentDao->getPaymentByOrderId($orderId); 
    }




     //update the payment status to 'completed'
     public function updatePaymentStatusToCompleted($orderId) {
            
            $payment = $this->getPaymentByOrderId($orderId);
            if (!$payment) {
                throw new Exception("Payment not found for this order.");
            }
    
            if ($payment['PaymentStatus'] === 'completed') {
                throw new Exception("Payment is already completed.");
            }
            
            $this->paymentDao->updatePaymentStatusByPaymentID($payment['id'], 'completed');
        }



        /************************************************* */
        public function updatePaymentStatus($orderId, $newStatus) {
    $payment = $this->getPaymentByOrderId($orderId);
    if (!$payment) {
        throw new Exception("Payment not found for this order.");
    }

    if (!in_array($newStatus, ['pending', 'completed'])) {
        throw new Exception("Invalid payment status.");
    }

    $this->paymentDao->updatePaymentStatusByPaymentID($payment['id'], $newStatus);
}


        // Delete a payment by payment ID
    public function delete($id) {
    return $this->paymentDao->delete($id);
}

    
}

?>