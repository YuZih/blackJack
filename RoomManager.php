<?php
// 聲明嚴格類型檢查
declare (strict_types = 1);
// 定義命名空間
namespace App;

// 引入 Data Structures 擴展包
if (!extension_loaded('ds')) {
    dl('ds.so');
}

use Ds\Set;

/**
 * RoomManager 類負責管理多個遊戲實例
 */
class RoomManager
{
    /**
     * 儲存所有活躍的遊戲實例
     * 鍵為客戶端ID，值為對應的Game對象
     */
    private array $games = [];
    private Set $loggedInUsers;

    public function __construct()
    {
        $this->loggedInUsers = new Set();
    }

    /**
     * 為指定客戶端創建一個新的遊戲實例
     *
     * @param string $clientId 客戶端ID
     * @return Game 新創建的遊戲實例
     */
    public function createGame(string $clientId, int $userId): string
    {
        // 創建新的遊戲實例
        $game = new Game();
        // 將新遊戲與客戶端ID關聯並存儲
        $this->games[$clientId] = ['game' => $game, 'userId' => $userId];
        // 紀錄登入的玩家有哪些
        $this->loggedInUsers->add($userId);
        // 開始遊戲
        $game->start();
        // 返回新創建的遊戲實例
        return "登入成功。新遊戲已開始。 " . PHP_EOL . $this->getCurrentState($clientId);
    }

    /**
     * 獲取指定客戶端的當前遊戲狀態
     *
     * @param string $clientId 客戶端ID
     * @return string 當前遊戲狀態的描述
     */
    public function getCurrentState(string $clientId): string
    {
        $game = $this->getGame($clientId);
        if ($game === null) {
            return "沒有找到活躍的遊戲。請先創建或加入一個遊戲。";
        }
        return $game->getGameState();
    }

    /**
     * 獲取指定客戶端的遊戲實例
     *
     * @param string $clientId 客戶端ID
     * @return Game|null 如果存在則返回Game對象，否則返回null
     */
    public function getGame(string $clientId): ?Game
    {
        // 如果存在對應的遊戲實例則返回，否則返回null
        return $this->games[$clientId]['game'] ?? null;
    }

    /**
     * 移除指定客戶端的遊戲實例
     *
     * @param string $clientId 客戶端ID
     */
    public function removeGame(string $clientId): void
    {
        // 從games數組中移除指定客戶端的遊戲實例與玩家登入紀錄
        if (isset($this->games[$clientId])) {
            $userId = $this->games[$clientId]['userId'];
            // 將玩家從loggedInUsers中移除
            $this->loggedInUsers->remove($userId);
            unset($this->games[$clientId]);
        }
    }

    /**
     * 執行遊戲操作
     *
     * @param string $clientId 客戶端ID
     * @param string $message 玩家的操作指令
     * @return string 操作結果的描述
     */
    public function action(string $clientId, string $message): string
    {
        $game = $this->getGame($clientId);
        if ($game === null) {
            return "沒有找到活躍的遊戲。請先創建或加入一個遊戲。";
        }

        switch ($message) {
            case 'hit':
                $response = $game->playerHit();
                if ($game->isBust()) {
                    $response .= " 你爆牌了。遊戲結束。";
                    $this->removeGame($clientId);
                }
                break;
            case 'stand':
                $response = $game->dealerTurn() . $game->determineWinner();
                $this->removeGame($clientId);
                break;
            default:
                $response = "請輸入 'hit' 或 'stand'。";
        }

        return $response;
    }

    /**
     * 檢查用戶是否已登入
     *
     * @param int $userId 用戶ID
     * @return bool 用戶是否已登入
     */
    public function isUserLogged(int $userId): bool
    {
        return $this->loggedInUsers->contains($userId);
    }
}
