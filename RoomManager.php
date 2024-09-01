<?php
// 聲明嚴格類型檢查
declare(strict_types=1);
// 定義命名空間
namespace App;

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

    /**
     * 為指定客戶端創建一個新的遊戲實例
     *
     * @param string $clientId 客戶端ID
     * @return Game 新創建的遊戲實例
     */
    public function createGame(string $clientId): Game
    {
        // 創建新的遊戲實例
        $game = new Game();
        // 將新遊戲與客戶端ID關聯並存儲
        $this->games[$clientId] = $game;
        // 開始遊戲
        $game->start();
        // 返回新創建的遊戲實例
        return $game;
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
        return $this->games[$clientId] ?? null;
    }

    /**
     * 移除指定客戶端的遊戲實例
     *
     * @param string $clientId 客戶端ID
     */
    public function removeGame(string $clientId): void
    {
        // 從games數組中移除指定客戶端的遊戲實例
        unset($this->games[$clientId]);
    }
}
