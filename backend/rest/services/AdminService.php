
<?php

require_once __DIR__ . '/../dao/OrdersDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . '/../dao/PaymentsDao.php';
require_once __DIR__. '/../dao/UserDao.php';


class AdminService extends BaseService {

    private $userDao;
    private $orderDao;
    private $productDao;
    private $paymentDao;

    public function __construct() {
        
        $this->userDao = new UserDao();
        $this->orderDao = new OrderDao();
        $this->productDao = new ProductDao();
        $this->paymentDao = new PaymentDao();

      
    }

    public function getDashboardStats(): array {
        return [
            'pendingOrders'   => $this->orderDao->countOrdersByStatus('pending'),
            'totalRevenue'    => $this->paymentDao->getTotalCompletedPayments(),
            'totalOrders'     => $this->orderDao->countAllOrders(),
            'totalProducts'   => $this->productDao->countAll(),
            'normalUsers'     => $this->userDao->countUsersByType('user'),
            'adminUsers'      => $this->userDao->countUsersByType('admin'),
            'totalUsers'      => $this->userDao->countAll(),
        ];
    }
}
