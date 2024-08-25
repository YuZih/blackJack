<?php

declare (strict_types = 1);

namespace App;

require __DIR__ . '/vendor/autoload.php';

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
        echo "Welcome to Blackjack!\n";

        $this->playerHand->addCard($this->deck->drawCard());
        $this->playerHand->addCard($this->deck->drawCard());
        $this->dealerHand->addCard($this->deck->drawCard());
        $this->dealerHand->addCard($this->deck->drawCard());

        $this->showHands();
        $this->playerTurn();
        $this->dealerTurn();
        $this->determineWinner();
    }

    private function showHands(): void
    {
        echo "Player's hand: " . $this->playerHand . " (Total: " . $this->playerHand->getTotalPoints() . ")\n";
        echo "Dealer's hand: " . $this->dealerHand . " (Total: " . $this->dealerHand->getTotalPoints() . ")\n";
    }

    private function playerTurn(): void
    {
        while (true) {
            echo "Do you want to (h)it or (s)tand? ";
            $action = trim(fgets(STDIN));

            if ($action === 'h') {
                $this->playerHand->addCard($this->deck->drawCard());
                echo "Player's hand: " . $this->playerHand . " (Total: " . $this->playerHand->getTotalPoints() . ")\n";

                if ($this->playerHand->getTotalPoints() > 21) {
                    echo "Player busts!\n";
                    break;
                }
            } elseif ($action === 's') {
                break;
            }
        }
    }

    private function dealerTurn(): void
    {
        while ($this->dealerHand->getTotalPoints() < 17) {
            $this->dealerHand->addCard($this->deck->drawCard());
        }

        echo "Dealer's hand: " . $this->dealerHand . " (Total: " . $this->dealerHand->getTotalPoints() . ")\n";
    }

    private function determineWinner(): void
    {
        $playerPoints = $this->playerHand->getTotalPoints();
        $dealerPoints = $this->dealerHand->getTotalPoints();

        if ($playerPoints > 21) {
            echo "Dealer wins!\n";
        } elseif ($dealerPoints > 21 || $playerPoints > $dealerPoints) {
            echo "Player wins!\n";
        } elseif ($playerPoints < $dealerPoints) {
            echo "Dealer wins!\n";
        } else {
            echo "It's a tie!\n";
        }
    }
}

// 創建遊戲實例並啟動遊戲
$game = new Game();
$game->start();
