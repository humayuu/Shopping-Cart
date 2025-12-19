<?php

// Class for Database operations

class Database extends Auth
{
    public $result = [];


    /**
     * Function for insert Data into Database
     */
    public function save($table, $params = [], $redirect)
    {
        if (!$this->tableExists($table)) return false;

        $columns = implode(', ', array_keys($params));
        $placeHolders = ':' . implode(', :', array_keys($params));
        $value = implode(', ', $params);

        try {

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO $table ($columns) VALUES ($placeHolders)");
            $result = $stmt->execute($params);

            if ($result) {
                $this->conn->commit();
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->errors[] = 'Error in insert data ' . $e->getMessage();
            return false;
        }
    }



    /**
     * Function for Update Data into Database
     */

    public function update($table, $params = [], $where = null, $redirect)
    {
        if (!$this->tableExists($table)) return false;

        $setClauses = [];

        foreach (array_keys($params) as $columns) {
            $setClauses[] = "$columns = :$columns";
        }

        $toString = implode(', ', $setClauses);

        try {

            $this->conn->beginTransaction();


            $stmt = $this->conn->prepare("UPDATE $table SET $toString");
            if ($where !== null) {
                $stmt .= " WHERE $where";
            }
            $result = $stmt->execute($params);

            if ($result) {
                $this->conn->commit();
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->errors[] = 'Error in update data ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for Fetch single Data from Database
     */


    public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) return false;

        $sql = "SELECT $rows FROM $table";

        if ($join  !== null)  $sql  .= " $join";
        if ($where  !== null) $sql  .= " WHERE $where";
        if ($order !== null)  $sql  .= " ORDER BY $order";
        if ($limit !== null)  $sql  .= " LIMIT $limit";

        try {

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $this->result = $stmt->fetch(); // Single Row

            return $this->result;
        } catch (Exception $e) {
            $this->errors[] = 'Error in fetch All Data ' . $e->getMessage();
            return false;
        }
    }


    /**
     * Function for Fetch all Data from Database
     */

    public function selectAll($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) return false;

        $sql = "SELECT $rows FROM $table";

        if ($join  !== null)  $sql  .= " $join";
        if ($where  !== null) $sql  .= " WHERE $where";
        if ($order !== null)  $sql  .= " ORDER BY $order";
        if ($limit !== null)  $sql  .= " LIMIT $limit";

        try {

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $this->result = $stmt->fetchAll();

            return $this->result;
        } catch (Exception $e) {
            $this->errors[] = 'Error in fetch All Data ' . $e->getMessage();
            return false;
        }
    }



    /**
     * Function for Delete Data into Database
     */
    public function delete($table, $where = null, $redirect)
    {
        if (!$this->tableExists($table)) return false;

        $sql = "DELETE FROM $table";
        if ($where !== null) $sql .= " WHERE $where";

        try {

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare($sql);

            $result = $stmt->execute();

            if ($result) {
                $this->conn->commit();

                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->errors[] = 'Error in Delete data ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for file Upload
     */

    public function file($file, $uploadDir)
    {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedExtension = ['jpg', 'jpeg', 'png'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        if (isset($_FILES[$file]) && $_FILES[$file]['error'] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION));
            $size = $_FILES[$file]['size'];
            $tmpFile = $_FILES[$file]['tmp_name'];

            if (!in_array($extension, $allowedExtension)) {
                $this->errors[] = 'Extension not allowed';
                return false;
            }

            if ($size > $maxFileSize) {
                $this->errors[] = 'File size is too large max file size is 2MB';
                return false;
            }

            $fileName = uniqid($file . '_') . time() . '.' . $extension;

            if (!move_uploaded_file($tmpFile, $uploadDir . $fileName)) {
                $this->errors[] = 'Error in file upload';
                return false;
            }

            $result = $uploadDir . $fileName;

            return $result;
        }
    }


    /**
     * Function for Validations 
     */
    public function validate($fields = [])
    {
        if (empty($fields)) {
            $this->errors[] = 'All fields are required';
            return false;
        }

        foreach ($fields as $field) {
            if (empty($field)) {
                $this->errors[] = 'All fields are required';
                return false;
            }
        }

        return true;
    }


    /**
     *  Function for Pagination
     */
    public function pagination() {}
} // Class ends here