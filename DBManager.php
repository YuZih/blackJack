<?php
// 聲明嚴格類型檢查
declare (strict_types = 1);
// 定義命名空間
namespace App;

use mysqli;

class DBManager
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', 'a7750092', 'black_jack');
        if ($this->conn->connect_error) {
            die("連接失敗: " . $this->conn->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Create a new user
    public function createUser($username, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // 先進行密碼加密

        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            return $stmt->insert_id; // 返回新插入的用戶ID
        } else {
            return false; // 插入失敗
        }
    }

    // Get a user by ID
    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // 使用fetch_assoc返回關聯數組
    }

    // Get a user by username
    public function getUserByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // 使用fetch_assoc返回關聯數組
    }

    // Get all users
    public function getAllUsers()
    {
        $result = $this->conn->query("SELECT * FROM users");

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    // Update a user
    public function updateUser($id, $username, $email, $password = null)
    {
        if ($password) {
            $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sssi", $username, $email, $hashedPassword, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $email, $id);
        }

        return $stmt->execute();
    }

    // Delete a user
    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // 驗證用戶
    public function verifyUser($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user['id']; // 返回用戶ID，表示驗證成功
        } else {
            return false; // 驗證失敗
        }
    }

    // Destructor: Closes the database connection
    public function __destruct()
    {
        $this->conn->close();
    }
}

// $db = DBManager::getInstance();

// // 創建新用戶
// $newUserId = $db->createUser('testuser', 'test@example.com', 'password123');

// // 根據ID獲取用戶信息
// $user = $db->getUserById($newUserId);
// print_r($user);

// // 更新用戶信息
// $db->updateUser($newUserId, 'newusername', 'newemail@example.com', 'newpassword123');
// $user = $db->getUserById($newUserId);
// print_r($user);

// // 刪除用戶
// $db->deleteUser($newUserId);
// $user = $db->getUserById($newUserId);
// print_r($user);
