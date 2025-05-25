<?php
require_once 'BaseDao.php';


class UserDao extends BaseDao {
    public function __construct() {
        // Specify the table name for UserDao
        parent::__construct("users"); //TABLE NAME, PASS 'users' table to base dao constructor
    }

    // Get user by email - EMAIL SHOULD BE UNIQUE - ATTEMPTING TO ADD NEW USER WITH SAME EMAIL RESULTS IN ERROR
    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(); //Fetches a single user by email
    }

    // Retrieve user by ID (inherited from BaseDao)
    public function getUserById($id) {
        return $this->getById($id);  // Uses BaseDao's getById method
    }

   
    // Example: Update user by ID
    public function updateUser($id, $data) {
        return $this->update($id, $data);
    }

    // Example: Delete user by ID
    public function deleteUser($id) {
        return $this->delete($id);
    }


    // Retrieve all users (inherited from BaseDao)
    public function getAllUsers() {
        return $this->getAll();  // Uses BaseDao's getAll method
    }

     // Add new user (inherited from BaseDao)
    public function addUser($user) {
        return $this->insert($user);  // Uses BaseDao's insert method
    }

    public function addUser1($data) {
        $stmt = $this->connection->prepare("INSERT INTO users (FirstName, LastName, email, Password, UserType) VALUES (:FirstName, :LastName, :email, :Password, :UserType)");
        $stmt->bindParam(':FirstName', $data['FirstName'], PDO::PARAM_STR);
        $stmt->bindParam(':LastName', $data['LastName'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':Password', $data['Password'], PDO::PARAM_STR);
        $stmt->bindParam(':UserType', $data['UserType'], PDO::PARAM_STR);
        $stmt->execute();
        
        // Return the last inserted ID, which is the user ID
        return $this->connection->lastInsertId();
    }
    


}
?>
