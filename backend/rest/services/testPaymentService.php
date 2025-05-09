
<?php



require_once 'OrderService.php';
require_once 'PaymentService.php';


$userId = 30;
$orderId = 34;

$orderService = new OrderService();
$paymentService = new PaymentService();

try {
    // Complete the order - WORKS
    echo "Completing order...\n";
    $orderService->completeOrder($orderId, $userId);

    // Check updated payment - WORKS
    echo "Getting updated payment...\n";
    $payment = $paymentService->getPaymentByOrderId($orderId);
    print_r($payment);


} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>



