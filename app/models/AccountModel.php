<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";

    public function __construct($db)
    {
        $this->conn = $db;
        $this->validateTable();
    }

    private function validateTable()
    {
        try {
            $query = "DESCRIBE " . $this->table_name;
            $this->conn->query($query);
        } catch (PDOException $e) {
            error_log("Account table not found: " . $e->getMessage());
            // Try to create the table
            $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                fullname VARCHAR(100) NOT NULL,
                role VARCHAR(20) DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($query);
        }
    }

    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }    public function save($username, $fullname, $password, $role="user")
    {
        error_log("Attempting to save user - username: $username, fullname: $fullname, role: $role");
        
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO " . $this->table_name . " (username, fullname, password, role) VALUES (:username, :fullname, :password, :role)";
            error_log("SQL Query: $query");
            
            $stmt = $this->conn->prepare($query);
            $fullname = htmlspecialchars(strip_tags($fullname));
            $username = htmlspecialchars(strip_tags($username));
            
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            
            $result = $stmt->execute();
            
            if ($result) {
                $this->conn->commit();
                error_log("User saved successfully");
                return true;
            } else {
                $this->conn->rollBack();
                error_log("Failed to save user. Error info: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Database error while saving user: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAllUsers($page = 1, $limit = 10, $search = '')
    {
        try {
            // Calculate offset
            $offset = ($page - 1) * $limit;
            
            // Base query
            $query = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $this->table_name;
            
            // Add search condition if search term is provided
            if (!empty($search)) {
                $query .= " WHERE username LIKE :search OR fullname LIKE :search";
            }
            
            // Add order by and limit
            $query .= " ORDER BY created_at DESC LIMIT :offset, :limit";
            
            $stmt = $this->conn->prepare($query);
            
            // Bind search parameter if search term is provided
            if (!empty($search)) {
                $searchTerm = "%{$search}%";
                $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            }
            
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Get total records
            $totalStmt = $this->conn->query("SELECT FOUND_ROWS()");
            $total = $totalStmt->fetchColumn();
            
            // Calculate total pages
            $totalPages = ceil($total / $limit);
            
            return [
                'users' => $stmt->fetchAll(PDO::FETCH_OBJ),
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'totalPages' => $totalPages,
                    'limit' => $limit
                ]
            ];
        } catch (PDOException $e) {
            error_log("Error getting users: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateUser($id, $fullname, $role)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET fullname = :fullname, role = :role WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $fullname = htmlspecialchars(strip_tags($fullname));
            $role = htmlspecialchars(strip_tags($role));
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteUser($id)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND role != 'admin'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            throw $e;
        }
    }

    public function resetPassword($id, $newPassword)
    {
        try {
            $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error resetting password: " . $e->getMessage());
            throw $e;
        }
    }
}