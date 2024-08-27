<?php

// 聲明嚴格類型檢查
declare(strict_types=1);

// 定義命名空間
namespace App;

// 引入自動加載文件
require __DIR__ . '/vendor/autoload.php';

// 定義遊戲類
class Game
{
    // 私有屬性
    private Deck $deck;         // 牌組
    private Hand $playerHand;   // 玩家手牌
    private Hand $dealerHand;   // 莊家手牌
    private bool $gameOver;     // 遊戲是否結束

    // 構造函數
    public function __construct()
    {
        $this->deck = new Deck();
        $this->playerHand = new Hand();
        $this->dealerHand = new Hand();
        $this->gameOver = false;
    }

    // 開始遊戲方法
    public function start(): array
    {
        // 初始化牌組和手牌
        $this->deck = new Deck();
        $this->playerHand = new Hand();
        $this->dealerHand = new Hand();

        // 發牌
        $this->playerHand->addCard($this->deck->drawCard());
        $this->playerHand->addCard($this->deck->drawCard());
        $this->dealerHand->addCard($this->deck->drawCard());
        $this->dealerHand->addCard($this->deck->drawCard());

        // 返回遊戲狀態
        return $this->getGameState();
    }

    // 玩家要牌方法
    public function playerHit(): array
    {
        // 玩家抽一張牌
        $this->playerHand->addCard($this->deck->drawCard());

        // 檢查是否爆牌
        if ($this->playerHand->getTotalPoints() > 21) {
            $this->gameOver = true;
            return $this->getGameState() + ['result' => 'Player busts! Dealer wins!'];
        }

        // 返回遊戲狀態
        return $this->getGameState();
    }

    // 玩家停牌方法
    public function playerStand(): array
    {
        // 莊家按規則抽牌
        while ($this->dealerHand->getTotalPoints() < 17) {
            $this->dealerHand->addCard($this->deck->drawCard());
        }

        // 遊戲結束，判斷勝負
        $this->gameOver = true;
        return $this->getGameState() + ['result' => $this->determineWinner()];
    }

    // 判斷勝負方法
    private function determineWinner(): string
    {
        $playerPoints = $this->playerHand->getTotalPoints();
        $dealerPoints = $this->dealerHand->getTotalPoints();

        if ($playerPoints > 21) {
            return "Dealer wins!";
        } elseif ($dealerPoints > 21 || $playerPoints > $dealerPoints) {
            return "Player wins!";
        } elseif ($playerPoints < $dealerPoints) {
            return "Dealer wins!";
        } else {
            return "It's a tie!";
        }
    }

    // 獲取遊戲狀態方法
    public function getGameState(): array
    {
        return [
            'player_hand' => (string)$this->playerHand,
            'dealer_hand' => (string)$this->dealerHand,
            'player_points' => $this->playerHand->getTotalPoints(),
            'dealer_points' => $this->dealerHand->getTotalPoints(),
            'game_over' => $this->gameOver
        ];
    }
}

$game = new Game();
$game->start();