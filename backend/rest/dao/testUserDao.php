<?php
require_once 'UserDao.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Instantiate UserDao
$userDao = new UserDao();

try{ 

// Insert a new user (Customer) - WORKS!
echo "\nInserting a new user...\n";
$userDao->insert([
   'FirstName' => 'Amina3',
   'LastName' => 'Crncevic',
   'Email' => 'amina3.crncevic@stu.ibu.edu.ba',
   'Password' => password_hash('amina2password', PASSWORD_DEFAULT),  // Hashing the password for security
   'UserType' => 'customer'
]);
echo "User inserted successfully!\n";


// Fetch all users -    WORKS!
echo "\nFetching all users from the database...\n";
$users = $userDao->getAll();
echo "Users in the database:\n";
print_r($users);


// Fetch a user by ID - WORKS!
echo "\nFetching user with specific ID...\n";
$user = $userDao->getById(2); 
    if ($user) {
        print_r($user);
    } else {
        echo "User not found!\n";
    }


// Update a user's information (example: changing email and name) - WORKS!
echo "\nUpdating user...\n";
$userDao->update(7, [
   'FirstName' => 'Johnathan',
   'LastName' => 'Doe',
   'Email' => 'johnathan@example.com'
]);
echo "User updated successfully!\n";    



//Fetch the updated user - WORKS!
$updatedUser = $userDao->getById(7);
echo "\nUpdated user information:\n";
print_r($updatedUser);



// Delete a user by ID - WORKS!
echo "\nDeleting user...\n";
$userDao->delete(9);
echo "User deleted successfully!\n";
   // Verify the deletion
   $deletedUser = $userDao->getById(9);
   if (!$deletedUser) {
       echo "User successfully deleted and no longer exists in the database.\n";
   } else {
       echo "Failed to delete the user!\n";
   }

} catch (Exception $e) {
   echo "Error: " . $e->getMessage();
}

?>