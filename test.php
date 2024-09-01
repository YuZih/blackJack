<?php

// 使用 Swoole 協程
Swoole\Coroutine\run(function () {
    // 創建 WebSocket 客戶端
    $client = new Swoole\Coroutine\Http\Client('127.0.0.1', 9502);
    $client->set(['websocket_mask' => true]);

    // 發起 WebSocket 連接
    $client->upgrade('/');

    if ($client->errCode != 0) {
        die("WebSocket connection failed: " . swoole_strerror($client->errCode) . "\n");
    }

    echo "Connected to WebSocket server at 127.0.0.1:9502\n";

    // 創建一個協程來處理從 WebSocket 接收到的消息
    Swoole\Coroutine::create(function () use ($client) {
        while (true) {
            $data = $client->recv();
            if ($data === false) {
                echo "Error receiving data: " . swoole_strerror($client->errCode) . "\n";
                break;
            } elseif ($data === '') {
                echo "Server closed the connection.\n";
                break;
            } else {
                echo "Received: $data\n";
            }
        }
    });

    // 創建一個協程來處理從 Terminal 發送的消息
    Swoole\Coroutine::create(function () use ($client) {
        while (true) {
            $message = trim(fgets(STDIN)); // 從終端讀取輸入
            if ($message === 'exit') {
                $client->close();
                echo "Disconnected from WebSocket server.\n";
                break;
            }
            if ($message === 'login') {
                $client->push('login:testUser1:testUser1');
            } else {
                $client->push($message); // 發送消息到 WebSocket 伺服器
            }

        }
    });
});
