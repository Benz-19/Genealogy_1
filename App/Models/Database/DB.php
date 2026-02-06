<?php
namespace App\Models\Database;

use PDO;
use Exception;
use PDOException;

class DB{

    private $conn;

    public function __construct()
    {
       $db_config_file_path = __DIR__ .'/../../../config/database.php';
       if(!file_exists($db_config_file_path)){
         throw new \Exception('Database configuration file does not exists...');
         error_log('Error: Ensure the database config file exists in the config directory');
       }

       $db_config = require $db_config_file_path;

       if(empty($db_config)){
         throw new \Exception('Database configuration file does not contain any value...');
         error_log('Error: Database config file return nothing. Review the file.');
       }

       try{
        $dsn = "mysql:host={$db_config['db_host']};dbname={$db_config['db_name']};port={$db_config['port']};charset={$db_config['charset']};";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $this->conn = new PDO($dsn, $db_config['db_username'], $db_config['db_password'], $options);
       }catch(PDOException $error){
        error_log('Database connection error. ErrorType: ' . $error->getMessage()); 
       }catch(Exception $error){
        error_log('Something went wrong in the DB class. ErrorType: ' . $error->getMessage());
       };
    }

    // verifies db connection
    public function verifyConnection(){
        if(!$this->conn){
            $this->__construct();
        }
    }

    
    // verifies and returns a connection to the db
    public function connection()
    {
        $this->verifyConnection();
        return $this->conn;
    }

    // closes the db connection
    public function closeConnection()
    {
        $this->conn = null;
    }


    /**
     * Responsible for executing all SQL commands (SELECT, UPDATE, DELETE, etc...)
     * based on the query and params specified
     * @param string $query stores the SQL query to be executed
     * @param array $params is an associative array that stores the prepared statement
     * @param return the retutn type is an array|null
     */
    public function execute(string $query, $params = [])
    {
        $this->verifyConnection();

        try{
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        }catch(PDOException $error){
         error_log('Failed to execute query at DB::execute. ErrorType: ' . $error->getMessage());   
       }catch(Exception $error){
        error_log('Something went wrong in the DB class. ErrorType: ' . $error->getMessage());
       };
    }


    /**
     * Responsible for retreiving a single db data based on the query and params specified
     * @param string $query stores the SQL query to be executed
     * @param array $params is an associative array that stores the prepared statement
     * @param return the retutn type is an array|null
     */
    public function fetchSingleData(string $query, $params = []){
        
        try {
            $this->verifyConnection();
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res ?: null;
        } catch (PDOException $error) {
         error_log('Failed to execute query at DB::fetchAllData. ErrorType: ' . $error->getMessage());
       }catch(Exception $error){
        error_log('Error: Something went wrong at DB::fetchAllData. ErrorType: ' . $error->getMessage());
       };
    }

    /**
     * Responsible for retreiving all db data based on the query and params specified
     * @param string $query stores the SQL query to be executed
     * @param array $params is an associative array that stores the prepared statement
     * @param return the retutn type is an array|null
     */
    public function fetchAllData(string $query, $params = []){
        
        try {
            $this->verifyConnection();
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res ?: null;
        } catch (PDOException $error) {
         error_log('Failed to execute query at DB::fetchAllData. ErrorType: ' . $error->getMessage());
       }catch(Exception $error){
        error_log('Error: Something went wrong at DB::fetchAllData. ErrorType: ' . $error->getMessage());
       };
    }



    /**
     * Responsible for retrieving a single column value (e.g. COUNT, SUM)
     * @param string $query stores the SQL query to be executed
     * @param array $params is an associative array that stores the prepared statement
     * @return mixed|null returns the value of the column or null if no result
     */
    public function fetchColumn(string $query, $params = []){
        try{
            $this->verifyConnection();

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $res = $stmt->fetchColumn();
            return $res !== false ? $res : null;
        }catch(PDOException $error){
         error_log('Failed to execute query at DB::fetchColumn. ErrorType: ' . $error->getMessage());
        }catch(Exception $error){
        error_log('Error: Something went wrong at DB::fetchColumn. ErrorType: ' . $error->getMessage());
       };
    }


    public function fetchAllColumn(string $query, array $params = []): array 
{
    try {
        $this->verifyConnection();
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        // This returns ALL rows as a flat array like [ 'CODE1', 'CODE2', 'CODE3' ]
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
    } catch (PDOException $error) {
        error_log('DB::fetchAllColumn Error: ' . $error->getMessage());
        return [];
    }
}

    // Starts a transaction
    public function beginTransaction() {
        $this->verifyConnection();
        return $this->conn->beginTransaction();
    }

    // Commits the changes
    public function commit() {
        return $this->conn->commit();
    }

    // Undoes changes if something fails
    public function rollBack() {
        return $this->conn->rollBack();
    }

    // Getting the ID of the user just inserted
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}