<?php
require_once 'UserService.php';

class TestUserService {

    private $userService;

    public function __construct() {
        
        $this->userService = new UserService();
    }

    // Test create user - WORKS!
    public function testCreateUser() {
        echo "Running testCreateUser...\n";

        $data = [
            'FirstName' => 'John',
            'LastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'Password' => 'password123',
            'UserType' => 'customer'
        ];

        
        try {
            $result = $this->userService->createUser($data);
            echo $result ? "User created successfully.\n" : "User creation failed.\n";
        } catch (Exception $e) {
            echo "Test failed with error: " . $e->getMessage() . "\n";
        }
    }

    // Test create user when email already exists - WORKS!
    public function testCreateUserEmailExists() {
        echo "Running testCreateUserEmailExists...\n";

        $data = [
            'FirstName' => 'Jane',
            'LastName' => 'Doe',
            'email' => 'john.doe@example.com',  // Same email as existing user
            'Password' => 'password123',
            'UserType' => 'customer'
        ];

    
        try {
            $this->userService->createUser($data);
            echo "Test failed: Expected exception for existing email\n";
        } catch (Exception $e) {
            echo "Test passed with error: " . $e->getMessage() . "\n";
        }
    }

    // Test email validation -WORKS!
    public function testInvalidEmail() {
        echo "Running testInvalidEmail...\n";

        $data = [
            'FirstName' => 'Alice',
            'LastName' => 'Smith',
            'email' => 'aminahaha@hotmail.co', //OUTPUT: NO MX RECORDS FOUND OFR THE DOMAIN : hotmail.co
            'Password' => 'password123',
            'UserType' => 'customer'
        ];

        
        try {
            $this->userService->createUser($data);
            echo "Test failed: Expected exception for invalid email\n";
        } catch (Exception $e) {
            echo "Test passed with error: " . $e->getMessage() . "\n";
        }
    }

    // Test password hashing - WORKS!
    public function testPasswordHashing() {
        echo "Running testPasswordHashing...\n";

        $data = [
            'FirstName' => 'Bob',
            'LastName' => 'Johnson',
            'email' => 'bob.johnson@example.com',
            'Password' => 'mypassword',
            'UserType' => 'customer'
        ];

        try {
            $result = $this->userService->createUser($data);
            if ($result) {
                echo "Password hashed successfully.\n";
            } else {
                echo "Test failed: Password hashing failed.\n";
            }
        } catch (Exception $e) {
            echo "Test failed with error: " . $e->getMessage() . "\n";
        }
    }

    // Test login with correct credentials -    WORKS!
    public function testLoginSuccess() {
        echo "Running testLoginSuccess...\n";

        $email = 'john.doe@example.com';
        $password = 'password123';

        try {
            $user = $this->userService->login($email, $password);
            echo "Login successful. User: " . print_r($user, true) . "\n";
        } catch (Exception $e) {
            echo "Test failed with error: " . $e->getMessage() . "\n";
        }
    }

    // Test login with incorrect credentials - WORKS!
    public function testLoginFailure() {
        echo "Running testLoginFailure...\n";

        $email = 'john.doe@example.com';
        $password = 'wrongpassword';

        try {
            $user = $this->userService->login($email, $password);
            echo "Test failed: Expected exception for invalid login.\n";
        } catch (Exception $e) {
            echo "Test passed with error: " . $e->getMessage() . "\n";
        }
    }
}

// Run tests
$testUserService = new TestUserService();
//$testUserService->testCreateUser();
//$testUserService->testCreateUserEmailExists();
$testUserService->testInvalidEmail();
//$testUserService->testPasswordHashing();
//$testUserService->testLoginSuccess();
//$testUserService->testLoginFailure();

?>