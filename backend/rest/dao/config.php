
<?php
/*
class Database {
   private static $host = 'localhost:330';
   private static $dbName = 'web_flower_shop';
   private static $username = 'root';
   private static $password = '1234567890';
   private static $connection = null;


   public static function connect() {
       if (self::$connection === null) {
           try {
               self::$connection = new PDO(
                   "mysql:host=" . self::$host . ";dbname=" . self::$dbName,
                   self::$username,
                   self::$password,
                   [
                       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                   ]
               );
           } catch (PDOException $e) {
               die("Connection failed: " . $e->getMessage());
           }
       }
       return self::$connection;
   }
}*/



// Set the reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));


class Config
{
   public static function DB_NAME()
   {
       return 'web_flower_shop'; 
   }
   public static function DB_PORT()
   {
       return 330;
   }
   public static function DB_USER()
   {
       return 'root';
   }
   public static function DB_PASSWORD()
   {
       return '1234567890';
   }
   public static function DB_HOST()
   {
       return '127.0.0.1';
   }


   public static function JWT_SECRET() {
       return 'f63dc91fc222a683e4fb207ec045ca08cc7a0eb91ad1c2689af75fbbb23a5f6f';
   }
}



