<?php

require_once 'PaymentsDao.php';

class TestPaymentsDao {
    private $paymentDao;

    public function __construct() {
        $this->paymentDao = new PaymentDao();
    }

    // Test creating a payment - WORKS!
    public function testCreatePayment() {
        echo "Testing Create Payment...\n";
        $result = $this->paymentDao->createPayment(5, 101.50); 
        if ($result) {
            echo "Payment created successfully.\n";
        } else {
            echo "Failed to create payment.\n";
        }
    }

    // Test reading a payment by Order ID - WORKS!
    public function testGetPaymentByOrderId() {
        echo "Testing Get Payment by Order ID...\n";
        $payment = $this->paymentDao->getPaymentByOrderId(5); // Should return the payment with Order ID 5 - IT RETURNED - WORKS.
        if ($payment) {
            echo "Payment found: \n";
            print_r($payment);
        } else {
            echo "Payment not found for Order ID.\n";
        }
    }

    // Test updating a payment status - WORKS!
    public function testUpdatePaymentStatus() {
        echo "Testing Update Payment Status...\n";
        $result = $this->paymentDao->updatePaymentStatusByOrderID(5, 'completed'); // Order ID 1, Status 'completed' - WORKS.
        if ($result) {
            echo "Payment status updated to 'completed'.\n";
        } else {
            echo "Failed to update payment status.\n";
        }
    }

    // Test deleting a payment by Order ID - WORKS!
    public function testDeletePaymentByOrderId() {
        echo "Testing Delete Payment by Order ID...\n";
        $result = $this->paymentDao->deletePaymentByOrderId(5); // Should delete the payment with Order ID 5- IT DELETED- WORKS!
        if ($result) {
            echo "Payment deleted successfully.\n";
        } else {
            echo "Failed to delete payment.\n";
        }
    }

    //TEST BASE DAOS UPDATE FUNCTION -WORKS!
public function testBaseDaoUpdateFunction() {
         $paymentDao = new PaymentDao();
         $paymentId = 7; 
         $paymentData = ['PaymentStatus' => 'completed', 
         'AmountPaid' => 200.00];
         $paymentDao->updatePayment($paymentId, $paymentData);
         echo "Payment updated successfully for payment ID: $paymentId.";  
        }

//TEST BASE DAOS GET BY PAYMENT ID FUNCTION - WORKS!
 public function testBaseDaoGetByIdFunction() {
      $paymentDao = new PaymentDao();
       $paymentId = 6;
        $payment = $paymentDao->getById($paymentId);
         if ($payment) {
                echo "Payment found: \n";
                print_r($payment);
            } else {
                echo "Payment not found!";
            }
        }

 // TEST BASE DAO'S GET ALL PAYMENTS FUNCTION - WORKS!
public function testBaseDaoGetAllFunction() {
    $paymentDao = new PaymentDao();
    $payments = $paymentDao->getAll(); 
    if ($payments) {
        echo "All Payments found: \n";
        print_r($payments); 
    } else {
        echo "No payments found.\n";  
    }
}


//TEST BASE DAOS DELETE BY PAYMENT ID FUNCTION - WORKS!
public function testBaseDaoDeleteByIdFunction() {
    $paymentDao = new PaymentDao();
    $paymentId = 6; 
    $result = $paymentDao->delete($paymentId);  
    if ($result) {
        echo "Payment with ID $paymentId deleted successfully.\n";
    } else {
        echo "Failed to delete payment with ID $paymentId.\n";
    }
}


    // all tests
    public function runTests() {
    //    $this->testCreatePayment();
    //    $this->testGetPaymentByOrderId();
     //   $this->testUpdatePaymentStatus();
     //   $this->testDeletePaymentByOrderId();
      //  $this->testBaseDaoUpdateFunction();
     // $this->testBaseDaoGetByIdFunction();
    // $this->testBaseDaoGetAllFunction();
    //$this->testBaseDaoDeleteByIdFunction();
    }
}


$test = new TestPaymentsDao();
$test->runTests();


?>

