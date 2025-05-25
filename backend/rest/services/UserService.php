<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../services/WishlistService.php';  



class UserService extends BaseService {
    /** @var UserDao */
    protected $dao;

       /** @var WishlistService */
       protected $wishlistService;

       protected $orderService;

         /** @var PaymentService */
    protected $paymentService;

    public function __construct() {
        $this->dao = new UserDao();
        $this->wishlistService = new WishlistService();  
        $this->orderService = new OrderService();
        $this->paymentService = new PaymentService();    
        parent::__construct($this->dao);
    }

    // Create new user with password hashing and validation
    public function createUser($data) {
         
    $this->dao->beginTransaction();
        try{ 

        if (empty($data['FirstName']) || empty($data['LastName']) || empty($data['email']) || empty($data['Password'])) {
            throw new Exception("Missing required user fields.");
        }


        // Check if email already exists
        $existing = $this->dao->getByEmail($data['email']);
        if ($existing) {
            throw new Exception("A user with this email already exists");
        }

        // Validate email formqat
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Validate email TLD
        if (!$this->validateEmailTLD($data['email'])) {
            throw new Exception("Invalid email TLD.");
        }

        // Validate MX records
        $domain = substr(strrchr($data['email'], "@"), 1);
        if (!$this->validateMXRecords($domain)) {
            throw new Exception("No MX records found for the domain: $domain.");
        }

        // Hash the password before storing
        $data['Password'] = password_hash($data['Password'], PASSWORD_DEFAULT);

        // Set user type if not provided
        if (empty($data['UserType'])) {
            $data['UserType'] = 'user';
        }

        // Add the user to the database
        $userId = $this->dao->addUser1($data);

          // Check if the user ID is valid
    if ($userId <= 0) {
        throw new Exception("Failed to create user.");
    }
      // Check if user ID exists in the users table
      $userCheck = $this->dao->getUserById($userId);
      if (!$userCheck) {
          throw new Exception("User does not exist in the database after creation.");
      }

        //  create the user's wishlist
        $this->wishlistService->createWishlistForUser($userId);
        //Create pending order for user upon account creation
        $pendingOrder = $this->orderService->getOrCreatePendingOrder($userId);  // This will create the pending order if it doesn't exist
           //  create a pending payment for the user
           if ($pendingOrder) {
            $this->paymentService->createPendingPaymentForOrder($pendingOrder['id'], $userId);
        } else {
            throw new Exception("Failed to create a pending order for the user.");
        }
        
        $this->dao->commit();

        return $userId;

        
    }catch (Exception $e) {
        
        $this->dao->rollBack();
        throw $e; 
    }}

    // Authenticate user (for login)
    public function login($email, $password) {
        $user = $this->dao->getByEmail($email);
        if (!$user || !password_verify($password, $user['Password'])) {
            throw new Exception("Invalid email or password.");
        }
        return $user;
    }



    

    // Get a single user by ID
    public function getUser($id) {
        $user = $this->dao->getUserById($id);
    if ($user) {
        return $user;  // If user exists, return the user details
    } else {
        throw new Exception("User not found", 404);  // If user does not exist, throw an exception with 404
    }
        
    }




    // Get all users
    public function getAllUsers() {
        return $this->dao->getAllUsers();
    }

    // Update user info
    public function updateUser($id, $data) {
        if (!empty($data['Password'])) {
            $data['Password'] = password_hash($data['Password'], PASSWORD_DEFAULT);
        }
        return $this->dao->updateUser($id, $data);
    }

    // Delete a user
    public function deleteUser($id) {
        $user = $this->dao->getUserById($id);

        if (!$user) {
            throw new Exception("User with ID $id does not exist.");
        }
        return $this->dao->deleteUser($id);
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


?>