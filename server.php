<?php

// 聲明嚴格類型檢查
declare (strict_types = 1);

// 引入 Composer 自動加載文件
require_once __DIR__ . '/vendor/autoload.php';

// 引入 Swoole WebSocket Server 類別
use App\DBManager;
// 引入 RoomManager 類別
use App\RoomManager;
// 引入 DBManager 類別
use Swoole\WebSocket\Server;

// 創建 WebSocket 服務器實例，監聽所有 IP 地址的 9502 端口
$server = new Server("0.0.0.0", 9502);

// 創建 RoomManager 實例來管理遊戲房間
$roomManager = new RoomManager();

// 獲取 DBManager 單例實例
$server->db = DBManager::getInstance();

// 當服務器啟動時觸發此事件
$server->on("start", function () {
    echo "Swoole WebSocket Server is started at ws://0.0.0.0:9502\n";
});

// 當新的 WebSocket 連接建立時觸發此事件
$server->on('open', function (Server $server, $request) {
    $clientId = (string) $request->fd;
    echo "{$clientId}";
    $server->push($request->fd, "歡迎！請使用以下指令進行註冊或登入：\n1. 註冊：'register:username:email:password'\n2. 登入：'login:username:password'");
});

// 當服務器收到客戶端消息時觸發此事件
$server->on('message', function (Server $server, $frame) use ($roomManager) {
    $clientId = (string) $frame->fd;
    $message = trim($frame->data);
    echo $message;
    $gameActionList = ['hit', 'stand'];

    // 處理註冊請求
    if (strpos($message, 'register:') === 0) {
        $parts = explode(':', $message);
        if (count($parts) === 4) {
            $username = $parts[1];
            $email = $parts[2];
            $password = $parts[3];

            // 使用 DBManager 創建新用戶
            $newUserId = $server->db->createUser($username, $email, $password);
            if ($newUserId) {
                $server->push($frame->fd, "註冊成功。請使用 'login:username:password' 登入");
            } else {
                $server->push($frame->fd, "註冊失敗。請重試。");
            }
        } else {
            $server->push($frame->fd, "無效的註冊格式。請使用 'register:username:email:password'");
        }
    }

    // 處理登入請求
    if (strpos($message, 'login:') === 0) {
        $parts = explode(':', $message);
        if (count($parts) === 3) {
            $username = $parts[1];
            $password = $parts[2];

            // 使用 DBManager 驗證用戶
            $userId = $server->db->verifyUser($username, $password);
            if ($userId) {
                if ($roomManager->isUserLogged($userId)) {
                    $server->push($frame->fd, "已登入過，您無法同時登入不同頁面！");
                    return;
                }
                // 開啟遊戲並顯示目前牌局
                $response = $roomManager->createGame($clientId, $userId);
                $server->push($frame->fd, $response);
            } else {
                $server->push($frame->fd, "登入失敗。請重試。");
            }
        } else {
            $server->push($frame->fd, "無效的登入格式。請使用 'login:username:password'");
        }
    }

    // 處理遊戲請求
    $message = strtolower(trim($frame->data)); // 將玩家輸入的字串去頭去尾空格並轉換成小寫

    if (in_array($message, $gameActionList)) {
        $response = $roomManager->action($clientId, $message);
        $server->push($frame->fd, $response);
    }
});

// 當 WebSocket 連接關閉時觸發此事件
$server->on('close', function ($server, $fd) use ($roomManager) {
    $clientId = (string) $fd;
    // 移除關閉連接的客戶端對應的遊戲實例
    $roomManager->removeGame($clientId);
    echo "Client {$fd} closed connection\n";
    // todo: 移除登入中的玩家列表
});

// 啟動 WebSocket 服務器
$server->start();
