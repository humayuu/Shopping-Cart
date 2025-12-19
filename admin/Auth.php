<?php

// Class for User login System

class Auth
{

    protected $conn = null;
    protected $errors = [];

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
            $tableInDB = $this->conn->query('SHOW TABLES LIKE ' . $this->conn->quote($table));

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
    public function store($table, $fullname, $email, $password, $confirmPassword, $redirect)
    {
        if (!$this->tableExists($table)) return false;


        // Validations
        if (empty($fullname) || empty($email) || empty($password) || empty($confirmPassword)) {
            $this->errors[] = 'All fields are required';
            return false;
        } elseif (strlen($fullname) < 5 || strlen($fullname) > 100) {
            $this->errors[] = 'Fullname in between 5 to 100 characters';
            return false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Invalid email address';
            return false;
        } elseif (strlen($password) < 8) {
            $this->errors[] = 'Password must be in 8 characters';
            return false;
        } elseif ($password !== $confirmPassword) {
            $this->errors[] = 'Password and confirm password must be matched';
            return false;
        }

        // Check if user is already exists
        $sql = $this->conn->prepare("SELECT * FROM `$table` WHERE user_email = :user_email");
        $sql->bindParam(':user_email', $email);
        $sql->execute();
        $user = $sql->fetch();

        if ($user) {
            $this->errors[] = 'User with this email is already exists.';
            return false;
        }

        // Create Hash Password
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        // Register New User
        try {

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO `$table` (user_fullname, user_email, user_password) VALUES (:user_fullname, :user_email, :user_password)");
            $stmt->bindParam(':user_fullname', $fullname);
            $stmt->bindParam(':user_email', $email);
            $stmt->bindParam(':user_password', $hashPassword);
            $result = $stmt->execute();

            if ($result) {
                $this->conn->commit();
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->errors[] = 'Error in register new user ' . $e->getMessage();
            return false;
        }
    }


    // Function for user loggedIn 
    public function attempt($table, $email, $password, $redirect)
    {
        if (!$this->tableExists($table)) return false;

        // Validations 
        if (empty($email) || empty($password)) {
            $this->errors[] = 'All fields are required.';
            return false;
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE user_email = :user_email");
            $stmt->bindParam(':user_email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            if (!$user) {
                $this->errors[] = 'Invalid Email or Password';
                return false;
            }

            if (!password_verify($password, $user['user_password'])) {
                $this->errors[] = 'Invalid Email or Password';
                return false;
            }


            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Store user data to Session variable
            $_SESSION['loggedIn']     = true;
            $_SESSION['userId']       = $user['id'];
            $_SESSION['userFullname'] = $user['user_fullname'];
            $_SESSION['userEmail']    = $user['user_email'];

            header('Location: ' . $redirect);
            exit;
        } catch (Exception $e) {
            $this->errors[] = 'Error in login user ' . $e->getMessage();
            return false;
        }
    }

    // Function for user logout
    public function logout($redirect)
    {
        try {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_unset();

            if (session_destroy()) {
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->errors[] = 'Error in logout user ' . $e->getMessage();
            return false;
        }
    }


    // Function Authenticate user
    public function checkUser($redirect)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
            header('Location: ' . $redirect);
            exit;
        }
    }


    // Function Authenticate user
    public function loggedIn($redirect)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['loggedIn']) == true) {
            header('Location: ' . $redirect);
            exit;
        }
    }



    // Function for get errors
    public function getErrors()
    {
        return $this->errors;
    }

    // Function for close connection to Database
    public function __destruct()
    {
        $this->conn = null;
    }
} // Class Ends here