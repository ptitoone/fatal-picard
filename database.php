<?php 

class Database
{
    # REDACTED CREDS
    private static $dbHost = "localhost";
    private static $dbName = "XXX";
    private static $dbUser = "XXX";
    private static $dbUserPassword = "XXX";
    
    private static $connection = NULL;
    
    public static function connect(){
        
        try
        {
            self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName,self::$dbUser,self::$dbUserPassword);
        }
            catch(PDOExpetion $e)
        {
            die($e->getMessage());
        }
        
       return self::$connection;
        
    }
    
    public static function disconect(){
        
        self::$connection = NULL;
    } 
}

?>
