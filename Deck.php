<?php

// 聲明嚴格類型檢查
declare (strict_types = 1);
// 定義命名空間
namespace App;
// 定義Deck類
class Deck
{
    // 私有屬性，用於存儲卡牌
    private array $cards = [];

    // 構造函數
    public function __construct()
    {
        // 定義花色數組
        $suits = ['♠', '♥', '♣', '♦'];
        // 定義點數數組
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        // 遍歷花色
        foreach ($suits as $suit) {
            // 遍歷點數
            foreach ($values as $value) {
                // 創建新卡牌並添加到卡組中
                $this->cards[] = new Card($suit, $value);
            }
        }

        // 洗牌（Fisher-Yates洗牌算法）
        shuffle($this->cards);
    }

    // 抽牌方法
    public function drawCard(): Card
    {
        // 從卡組中移除並返回最後一張卡
        return array_pop($this->cards);
    }

    // 獲取剩餘卡牌數量的方法
    public function cardsRemaining(): int
    {
        // 返回卡組中剩餘的卡牌數量
        return count($this->cards);
    }
}
