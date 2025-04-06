
<?php

require_once 'BaseDao.php';
class PaymentDao extends BaseDao {
    public function __construct() {
        parent::__construct("Payments");
    }


    // Create a new payment
    public function createPayment($orderId, $amountPaid) {
        $stmt = $this->connection->prepare("
            INSERT INTO Payments (Orders_OrderID, AmountPaid, PaymentStatus) 
            VALUES (:orderId, :amountPaid, 'pending')
        ");
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':amountPaid', $amountPaid, PDO::PARAM_STR);
        return $stmt->execute();
    }


    // Get payment details by Order ID
    public function getPaymentByOrderId($orderId) {
        $stmt = $this->connection->prepare("SELECT * FROM Payments WHERE Orders_OrderID = :orderId");
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Update payment status (e.g., mark as completed)
    public function updatePaymentStatus($orderId, $newStatus) {
        $stmt = $this->connection->prepare("
            UPDATE Payments 
            SET PaymentStatus = :newStatus 
            WHERE Orders_OrderID = :orderId
        ");
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    // Update payment by ID (uses BaseDao's update function)
    public function updatePayment($id, $paymentData) {
        return $this->update($id, $paymentData);  // Uses BaseDao's update method
    }

    // Get all payments (uses BaseDao's getAll method)
    public function getAll() {
        return $this->getAll(); 
    }

      // Get payment details by Payment ID (uses BaseDao's getById method)
      public function getById($id)
       {
        return $this->getById($id);  // Calls BaseDao's getById method
        }


    //DELETE PAYMENT BY ID - USE BASE DAO'S FUNCTION
    public function delete($id)
    {
        return $this->delete($id);
    }


    // Delete a payment for an order
    public function deletePaymentByOrderId($orderId) {
        $stmt = $this->connection->prepare("DELETE FROM Payments WHERE Orders_OrderID = :orderId");
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>
