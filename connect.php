<?php
$servername = "localhost"; // 或者你的 MySQL 伺服器地址
$username = "root"; // 你的 MySQL 用戶名
$password = "a7750092"; // 你的 MySQL 密碼
$dbname = "black_jack"; // 你要連接的資料庫名稱

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// 在這裡可以執行 SQL 查詢
// 建立 users 表格
// $sql1 = "CREATE TABLE IF NOT EXISTS users (
//     id INT AUTO_INCREMENT PRIMARY KEY, -- 用戶唯一標識符，自動遞增
//     username VARCHAR(50) NOT NULL UNIQUE, -- 用戶名，不可為空且唯一
//     email VARCHAR(100) NOT NULL UNIQUE, -- 電子郵件，不可為空且唯一
//     password VARCHAR(255) NOT NULL, -- 密碼，不可為空
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- 創建時間，默認為當前時間戳
// )";

// if ($conn->query($sql1) === true) {
//     echo "表格 'users' 創建成功";
// } else {
//     echo "創建 users 表格時出錯: " . $conn->error;
// }

// // 建立 game_records 表格
// $sql1 = "CREATE TABLE IF NOT EXISTS game_records (
//     id INT AUTO_INCREMENT PRIMARY KEY, -- 遊戲記錄的唯一標識符，自動遞增
//     user_id INT NOT NULL, -- 關聯到 users 表的用戶 ID
//     score INT NOT NULL, -- 該局遊戲的得分
//     result ENUM('win', 'lose', 'draw') NOT NULL, -- 遊戲結果：勝、負或平局
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- 記錄創建時間，默認為當前時間戳
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- 外鍵約束，關聯到 users 表，當用戶被刪除時級聯刪除相關記錄
// )";

// if ($conn->query($sql1) === true) {
//     echo "表格 'game_records' 創建成功";
// } else {
//     echo "創建 game_records 表格時出錯: " . $conn->error;
// }

// // 建立 leaderboard 表格
// $sql3 = "CREATE TABLE IF NOT EXISTS leaderboard (
//     id INT AUTO_INCREMENT PRIMARY KEY, -- 排行榜記錄的唯一標識符，自動遞增
//     user_id INT NOT NULL, -- 關聯到 users 表的用戶 ID
//     total_score INT DEFAULT 0, -- 用戶的總得分，默認為 0
//     highest_score INT DEFAULT 0, -- 用戶的最高單局得分，默認為 0
//     games_played INT DEFAULT 0, -- 用戶已玩遊戲次數，默認為 0
//     last_played TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- 用戶最後一次遊戲的時間，默認為當前時間戳
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- 外鍵約束，關聯到 users 表，當用戶被刪除時級聯刪除相關記錄
// )";

// if ($conn->query($sql3) === true) {
//     echo "表格 'leaderboard' 創建成功";
// } else {
//     echo "創建 leaderboard 表格時出錯: " . $conn->error;
// }

// $id2 = 2;
// $user2 = 'user2';
// $pwd = 'user2';
// $email = 'user2@gamil.com';
// $sql = "INSERT INTO users (id, username, email, password) VALUES ($id2, $user2, $pwd, $email, $sql)";
// if ($conn->query($sql) === TRUE) {
//     echo "新記錄插入成功";
// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }

$id2 = 3;
$user2 = 'user3';
$pwd = 'user3';
$email = 'user3@gamil.com';
$sql = "INSERT INTO users (id, username, email, password) VALUES ($id2, '$user2', '$email', '$pwd')";
if ($conn->query($sql) === TRUE) {
    echo "新記錄插入成功";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 關閉連接
$conn->close();


// class dbManager{

//     private $conn;

//     public function __construct($conn) {
//         $this->conn = $conn;
//     }

//     public function getUser(int $id) {
//         $sql_get_user = "SELECT * FROM users WHERE id = {$id}";
//         return $this->conn->query($sql_get_user);
//     }
// }
