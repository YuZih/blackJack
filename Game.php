<?php

// 聲明嚴格類型檢查
declare (strict_types = 1);

// 定義命名空間
namespace App;

// 定義遊戲類
class Game
{
    private Deck $deck;
    private Hand $playerHand;
    private Hand $dealerHand;

    public function __construct()
    {
        $this->deck = new Deck();
        $this->playerHand = new Hand();
        $this->dealerHand = new Hand();
    }

    public function start(): void
    {
        $this->playerHand->addCard($this->deck->drawCard());
        $this->playerHand->addCard($this->deck->drawCard());
        $this->dealerHand->addCard($this->deck->drawCard());
        $this->dealerHand->addCard($this->deck->drawCard());
    }

    public function playerHit(): string
    {
        $this->playerHand->addCard($this->deck->drawCard());

        if ($this->playerHand->getTotalPoints() > 21) {
            return "You bust with hand: {$this->playerHand} (Total: {$this->playerHand->getTotalPoints()})\n";
        }

        return "Hit! Your hand: {$this->playerHand} (Total: {$this->playerHand->getTotalPoints()})";
    }

    public function dealerTurn(): string
    {
        while ($this->dealerHand->getTotalPoints() < 17) {
            $this->dealerHand->addCard($this->deck->drawCard());
        }

        return "Dealer's hand: {$this->dealerHand} (Total: {$this->dealerHand->getTotalPoints()})\n";
    }

    public function determineWinner(): string
    {
        $playerPoints = $this->playerHand->getTotalPoints();
        $dealerPoints = $this->dealerHand->getTotalPoints();

        if ($playerPoints > 21) {
            return "You lose!";
        } elseif ($dealerPoints > 21 || $playerPoints > $dealerPoints) {
            return "You win!";
        } elseif ($playerPoints < $dealerPoints) {
            return "You lose!";
        }

        return "It's a tie!\n";
    }

    public function getGameState(): string
    {
        return "Your hand: {$this->playerHand} (Total: {$this->playerHand->getTotalPoints()})\n" .
            "Dealer's hand: {$this->dealerHand} (Total: {$this->dealerHand->getTotalPoints()})";
    }

    public function isBust(): bool
    {
        return $this->playerHand->getTotalPoints() > 21;
    }
}
