
<?php


// Set the reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));


class Config

{
    /*
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
   }*/



 public static function DB_NAME() {
       return Config::get_env("DB_NAME", "web_flower_shop");
   }
   public static function DB_PORT() {
       return Config::get_env("DB_PORT", 330);
   }
   public static function DB_USER() {
       return Config::get_env("DB_USER", 'root');
   }
   public static function DB_PASSWORD() {
       return Config::get_env("DB_PASSWORD", '1234567890');
   }
   public static function DB_HOST() {
       return Config::get_env("DB_HOST", '127.0.0.1');
   }
   public static function JWT_SECRET() {
       return Config::get_env("JWT_SECRET", 'f63dc91fc222a683e4fb207ec045ca08cc7a0eb91ad1c2689af75fbbb23a5f6f');
   }
   public static function OPENAI_API_KEY() {
        return Config::get_env("OPENAI_API_KEY",'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'); // your actual OpenAI key
    }
   public static function get_env($name, $default){
       return isset($_ENV[$name]) && trim($_ENV[$name]) != "" ? $_ENV[$name] : $default;
   }

}



