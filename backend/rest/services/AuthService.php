
<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
require_once 'OrderService.php';
require_once 'WishlistService.php';
require_once 'PaymentService.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService extends BaseService {
   private $auth_dao;
    /** @var WishlistService */
       protected $wishlistService;

       protected $orderService;
        protected $paymentService;
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao);
        $this->wishlistService = new WishlistService();  
        $this->orderService = new OrderService();
        $this->paymentService = new PaymentService();
   }


   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }



   
   public function register($entity) {  
       if (empty($entity['firstName']) || empty($entity['lastName']) || empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Email and password are required.'];
       }
       $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
       if($email_exists){
           return ['success' => false, 'error' => 'Email already registered.'];
       }
/************************************************************** */
      
        if (!filter_var($entity['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        /************* */
        
        if (!$this->validateEmailTLD($entity['email'])) {
            throw new Exception("Invalid email TLD.");
        }
        /********************** */
       
        $domain = substr(strrchr($entity['email'], "@"), 1);
        if (!$this->validateMXRecords($domain)) {
            throw new Exception("No MX records found for the domain: $domain.");
        }
        /************************************************************ */
       if (empty($entity['userType'])) {
            $entity['userType'] = 'user';
        }
       $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);
   
        $created_user = parent::add($entity);  
        if (!$created_user || empty($created_user['id'])) {
            throw new Exception("Failed to create user.");
        }

        $userId = $created_user['id'];
       
        $this->wishlistService->createWishlistForUser($userId);

      
        $pendingOrder = $this->orderService->getOrCreatePendingOrder($userId);
        if (!$pendingOrder || empty($pendingOrder['id'])) {
            throw new Exception("Failed to create pending order for the user.");
        }
     
        $this->paymentService->createPendingPaymentForOrder($pendingOrder['id'], $userId);

       unset($created_user['password']);
       return ['success' => true, 'data' => $entity];             
   }






   public function login($entity) {  
       if (empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Email and password are required.'];
       }
       $user = $this->auth_dao->get_user_by_email($entity['email']);


       if(!$user){
           return ['success' => false, 'error' => 'Invalid email or password.'];
       }

       if(!$user || !password_verify($entity['password'], $user['Password']))
           return ['success' => false, 'error' => 'Invalid email or password.'];

       unset($user['Password']);
       $jwt_payload = [
           'user' => $user,
           'iat' => time(),
           // If this parameter is not set, JWT will be valid for life. This is not a good approach
           'exp' => time() + (60 * 60 * 24) // valid for day
       ];
       $token = JWT::encode(
           $jwt_payload,
           Config::JWT_SECRET(),
           'HS256'
       );
       return ['success' => true, 'data' => array_merge($user, ['token' => $token])];             
   }


   // Function to validate email TLD
  function validateEmailTLD($email) {
    $url = 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt'; // Fetching from remote url
    $tlds = file($url, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    array_shift($tlds); // Remove the first line

    $validTLDs = array_map('strtolower', $tlds);
    $partition = explode('@', $email);
    $domainPart = explode('.', $partition[1]);
    $tld = strtolower(end($domainPart));

    return in_array($tld, $validTLDs);
}



//Validate MX records
public function validateMXRecords($domain) {
    if (getmxrr($domain, $mx_records)) {
        return count($mx_records) > 0;
    }
    return false;
}





}
