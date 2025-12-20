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

        $sql = "UPDATE $table SET $toString";
        if ($where !== null) {
            $sql .= " WHERE $where";
        }

        try {

            $this->conn->beginTransaction();


            $stmt = $this->conn->prepare($sql);

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


    public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null, $offset = null)
    {
        if (!$this->tableExists($table)) return false;

        $sql = "SELECT $rows FROM $table";

        if ($join  !== null)  $sql  .= " $join";
        if ($where  !== null) $sql  .= " WHERE $where";
        if ($order !== null)  $sql  .= " ORDER BY $order";
        if ($limit !== null || $offset !== null)  $sql  .= " LIMIT $offset $limit";

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

    public function selectAll($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null, $offset = null)
    {
        if (!$this->tableExists($table)) return false;

        $sql = "SELECT $rows FROM $table";

        if ($join  !== null)  $sql  .= " $join";
        if ($where  !== null) $sql  .= " WHERE $where";
        if ($order !== null)  $sql  .= " ORDER BY $order";

        if ($limit !== null) {
            $sql .= " LIMIT $limit";

            if ($offset !== null) {
                $sql .= " OFFSET $offset";
            }
        }

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
    public function paginator($table, $pageNo, $limit)
    {
        if (!$this->tableExists($table)) return false;

        if (empty($pageNo)) {
            $this->errors[] = 'Page no is required';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM $table");
        $stmt->execute();
        $totalRows =   $stmt->fetch()['total'];
        $totalPages = ceil($totalRows / $limit);

        echo '<nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">';

        if ($pageNo > 1) {
            echo ' <li class="page-item">
                <a href="?page=' . ($pageNo - 1) . '" class="page-link">Previous</a>
            </li>';
        } else {
            echo '<li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $pageNo) ? 'active' : '';
            echo "<li class='page-item'><a class='page-link " . $activeClass . "' href='?page=" . $i . "'>$i</a></li>";
        }


        if ($pageNo < $totalPages) {
            echo ' <li class="page-item">
                <a href="?page=' . ($pageNo + 1) . '" class="page-link">Next</a>
            </li>';
        } else {
            echo '<li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>';
        }

        echo '</ul>
          </nav>';
    }
} // Class ends here