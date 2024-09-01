<?php

// 聲明嚴格類型檢查
declare(strict_types=1);

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
            return "Player busts with hand: {$this->playerHand} (Total: {$this->playerHand->getTotalPoints()})\n";
        }

        return "Player's hand: {$this->playerHand} (Total: {$this->playerHand->getTotalPoints()})\n";
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
            return "Dealer wins!\n";
        } elseif ($dealerPoints > 21 || $playerPoints > $dealerPoints) {
            return "Player wins!\n";
        } elseif ($playerPoints < $dealerPoints) {
            return "Dealer wins!\n";
        }

        return "It's a tie!\n";
    }

    public function getGameState(): string
    {
        return "Player's hand: {$this->playerHand} (Total: {$this->playerHand->getTotalPoints()})\n" .
               "Dealer's hand: {$this->dealerHand} (Total: {$this->dealerHand->getTotalPoints()})\n";
    }
}