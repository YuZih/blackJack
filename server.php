<?php

// 聲明嚴格類型檢查
declare(strict_types=1);

// 引入 Composer 自動加載文件
require_once __DIR__ . '/vendor/autoload.php';
// 引入 Game 和 RoomManager 類別

// 引入 Swoole WebSocket Server 類別
use Swoole\WebSocket\Server;
// 引入 RoomManager 類別
use App\RoomManager;

// 創建 WebSocket 服務器實例，監聽所有 IP 地址的 9502 端口
$server = new Server("0.0.0.0", 9502);

// 創建 RoomManager 實例來管理遊戲房間
$roomManager = new RoomManager();

// 當服務器啟動時觸發此事件
$server->on("start", function () {
    echo "Swoole WebSocket Server is started at ws://0.0.0.0:9502\n";
});

// 當新的 WebSocket 連接建立時觸發此事件
$server->on('open', function (Server $server, $request) use ($roomManager) {
    $clientId = (string)$request->fd;
    // 為新連接的客戶端創建一個新遊戲
    $roomManager->createGame($clientId);
    // 向客戶端發送歡迎消息
    $server->push($request->fd, "Welcome to Blackjack! A new game has started.");
});

// 當服務器收到客戶端消息時觸發此事件
$server->on('message', function (Server $server, $frame) use ($roomManager) {
    $clientId = (string)$frame->fd;
    // 獲取客戶端對應的遊戲實例
    $game = $roomManager->getGame($clientId);

    // 如果沒有找到活躍的遊戲，發送錯誤消息並返回
    if (!$game) {
        $server->push($frame->fd, "No active game found.");
        return;
    }

    // 處理客戶端發送的消息
    $message = strtolower(trim($frame->data));

    // 根據客戶端的指令執行相應的遊戲操作
    if ($message === 'hit') {
        $response = $game->playerHit();
    } elseif ($message === 'stand') {
        $response = $game->dealerTurn() . $game->determineWinner();
        // 遊戲結束後移除遊戲實例
        $roomManager->removeGame($clientId);
    } else {
        $response = "Invalid command. Type 'hit' or 'stand'.";
    }

    // 將遊戲操作的結果發送回客戶端
    $server->push($frame->fd, $response);
});

// 當 WebSocket 連接關閉時觸發此事件
$server->on('close', function ($server, $fd) use ($roomManager) {
    $clientId = (string)$fd;
    // 移除關閉連接的客戶端對應的遊戲實例
    $roomManager->removeGame($clientId);
    echo "Client {$fd} closed connection\n";
});

// 啟動 WebSocket 服務器
$server->start();
