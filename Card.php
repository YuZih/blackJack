<?php

// 聲明嚴格類型檢查
declare (strict_types = 1);
// 定義命名空間
namespace App;

// 定義Card類
class Card
{
    // 私有屬性，表示花色
    private string $suit;
    // 私有屬性，表示點數
    private string $value;

    // 構造函數，初始化花色和點數
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    // 獲取花色的方法
    public function getSuit(): string
    {
        return $this->suit;
    }

    // 獲取點數的方法
    public function getValue(): string
    {
        return $this->value;
    }

    // 獲取卡牌分數的方法
    public function getPoints(): int
    {
        // 如果是K、Q、J，返回10分
        if (in_array($this->value, ['K', 'Q', 'J'])) {
            return 10;
        // 如果是A，返回11分
        } elseif ($this->value === 'A') {
            return 11;
        // 其他情況，返回點數對應的整數值
        } else {
            return (int) $this->value;
        }
    }

    // 將卡牌轉換為字符串的方法
    public function __toString(): string
    {
        // 返回點數和花色的組合
        return $this->value . $this->suit;
    }
}
