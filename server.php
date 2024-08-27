<?php
// 引入必要的Swoole和App命名空間
use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use App\Game;

// 引入Composer自動加載文件
require __DIR__ . '/vendor/autoload.php';

// 創建WebSocket服務器實例，監聽所有網絡接口的9502端口
$server = new Server("0.0.0.0", 9502);

// 服務器啟動時的回調函數
$server->on("start", function (Server $server) {
    echo "Swoole WebSocket server started at ws://127.0.0.1:9502\n";
});

// 新的WebSocket連接建立時的回調函數
$server->on('open', function (Server $server, Request $request) {
    echo "Connection opened: {$request->fd}\n";
});

// 接收到WebSocket消息時的回調函數
$server->on('message', function (Server $server, Frame $frame) {
    // 使用static關鍵字確保$game在多次調用之間保持狀態
    static $game;

    // 解析接收到的JSON數據
    $data = json_decode($frame->data, true);
    $command = $data['command'] ?? '';

    // 根據命令執行相應的遊戲操作
    if ($command === 'start') {
        $game = new Game();
        $response = $game->start();
    } elseif ($command === 'hit' && isset($game)) {
        $response = $game->playerHit();
    } elseif ($command === 'stand' && isset($game)) {
        $response = $game->playerStand();
    } else {
        $response = ['error' => 'Invalid command or game not started.'];
    }

    // 將響應發送回客戶端
    $server->push($frame->fd, json_encode($response));
});

// WebSocket連接關閉時的回調函數
$server->on('close', function (Server $server, $fd) {
    echo "Connection closed: {$fd}\n";
});

// 啟動WebSocket服務器
$server->start();
