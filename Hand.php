<?php

// 聲明嚴格類型檢查
declare (strict_types = 1);
// 定義命名空間
namespace App;
// 定義Hand類
class Hand
{
    // 私有屬性，用於存儲卡牌
    private array $cards = [];

    // 添加卡牌的方法
    public function addCard(Card $card): void
    {
        // 將卡牌添加到cards數組中
        $this->cards[] = $card;
    }

    // 計算總點數的方法
    public function getTotalPoints(): int
    {
        // 初始化總點數
        $totalPoints = 0;
        // 初始化A的數量
        $aceCount = 0;

        // 遍歷所有卡牌
        foreach ($this->cards as $card) {
            // 累加卡牌點數
            $totalPoints += $card->getPoints();
            // 如果是A，則增加A的計數
            if ($card->getValue() === 'A') {
                $aceCount++;
            }
        }

        // 處理A的特殊情況：如果總點數超過21且還有A，則將A的值從11減為1
        while ($totalPoints > 21 && $aceCount > 0) {
            $totalPoints -= 10;
            $aceCount--;
        }

        // 返回最終的總點數
        return $totalPoints;
    }

    // 將手牌轉換為字符串的方法
    public function __toString(): string
    {
        // 初始化空字符串
        $hand = '';
        // 遍歷所有卡牌
        foreach ($this->cards as $card) {
            // 將每張卡牌添加到字符串中，並在後面加上空格
            $hand .= $card . ' ';
        }
        // 去除首尾空格並返回結果
        return trim($hand);
    }
}
