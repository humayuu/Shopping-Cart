<?php

// Class Start Here

class Auth
{

    private $conn = null;
    private $errors = [];

    // Function for Connection to Database
    public function __construct($host, $dbname, $user, $password)
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection error ' . $e->getMessage());
        }
    }


    // Function for check if the table is exists or not 
    protected function tableExists($table)
    {
        try {
            $tableInDB = $this->conn->query('SHOW TABLES LIKE ' . $this->conn->quote(`$table`));

            if ($tableInDB->rowCount() > 0) {
                return true;
            } else {
                $this->errors[] = 'Table ' . $table . ' is not exists in this Database.';
                return false;
            }
        } catch (Exception $e) {
            $this->errors[] = 'Error in finding table ' . $e->getMessage();
            return false;
        }
    }


    // Function for register new User
    public function register($table, $fullname, $email, $password, $confirmPassword, $redirect)
    {
        if (!$this->tableExists($table)) return false;
    }




























































    // Function for close connection to Database
    public function __destruct()
    {
        $this->conn = null;
    }
} // Class Ends here