<?php
  ini_set('memory_limit', '256M');

  session_start();
  $_SESSION["dominoLog"]  = [];

  class Domino
  {
    private $tiles          = [];
    private $PlayerOne      = [];
    private $PlayerTwo      = [];
    private $dominoLogs    = [];
    private $bottomPile     = [];
    private $bottomPileEnds = [];
    private $players        = ['PlayerOne', 'PlayerTwo'];
    private $currentPlayer;
    private $end            = false;

    /**
     * Create 28 initial tiles and mixed them
     */
    public function createTiles()
    {
        for ($x = 0; $x <= 6; $x++) {
            for ($y = $x; $y <= 6; $y++) {
                $this->tiles[] = [$x, $y];
            }
        }

        shuffle($this->tiles);
        $initialTiles = $this->tiles;

        $this->dominoLogs[]  = 'Created Tiles Pile';

        require './views/tiles.php';
    }

    /**
     * Shuffle each player 7 tiles
     */
    public function shuffleTiles()
    {
        shuffle($this->tiles);

        $this->PlayerOne = array_splice($this->tiles, 0, 7);
        $this->PlayerTwo = array_splice($this->tiles, 0, 7);

        $playersTiles = [$this->PlayerOne, $this->PlayerTwo];

        $this->dominoLogs[]  = 'Playes Tiles Selected';

        require './views/playerTiles.php';
    }

    /**
     * Start the game!
     */
    public function playGame()
    {
        $this->firstMove();

        // play until one of the players has no more tiles in his hand and win the game
        $this->playRound();
    }

     /**
     * First move is different
     * We select a piece from the remaining pieces to be the first one to start the game
     */
    private function firstMove()
    {
        $firstTile            = $this->firstTile();
        $this->currentPlayer  = $this->firstPlayer($firstTile);
        $this->dominoLogs[]  = 'The first Player to move is: ' . $this->currentPlayer;
        $this->dominoLogs[]  = 'The first tile is: ' . $this->getTileName($firstTile);
        $this->bottomPileEnds = $firstTile;
        $this->bottomPile[]   = $firstTile;
        
        $this->removeTilePlayer($firstTile);
        $this->updateView("PlayerOne",$firstTile);
    }

    /**
     * We remove from the actual player the tile played
     * 
     */
    private function removeTilePlayer($tile) {
        $actualPlayer = $this->currentPlayer;
        $index = array_search($tile, $this->$actualPlayer);
        array_splice($this->$actualPlayer, $index, 1);
    }

    /**
     * For the current player checks to see if one of the tiles from his hand can be used in the bottom pile
     * If no, the user must draw another tile until it has one tile that is can be used
     * After using a tile - if it is the last tile he will win the game, if not - his turn ends and the other player can play his turn
     */
    private function playRound()
    {
        while (current($this->players) !== $this->currentPlayer) next($this->players);

        if ($this->currentPlayer === "PlayerOne") {
            $this->currentPlayer = next($this->players);
        } elseif ($this->currentPlayer === "PlayerTwo") {
            $this->currentPlayer = prev($this->players);
        }

        $this->dominoLogs[]  = 'Next Player to move is: ' . $this->currentPlayer;

        $currentPlayer  = $this->currentPlayer;
        $bottomPileEnds = $this->bottomPileEnds;
        $endTurn        = true;
        $tiles          = $this->$currentPlayer;

        foreach ($tiles as $key => $value) {
            $endTurn = $this->checkTile($value, $bottomPileEnds, $currentPlayer, $key);
            if ($endTurn) {
                break;
            }
        }

        if (!$endTurn && !empty($this->tiles)) {
            // draw a tile and 'put it in hand'. Same player can continue the game
            $draw                 = array_splice($this->tiles, 0, 1);
            $this->dominoLogs[]   = $currentPlayer . ' draws the tile ' . $this->getTileName($draw[0]);
            $this->$currentPlayer = array_merge($this->$currentPlayer, $draw);

            $this->updateView("drawTile",[$currentPlayer,$draw]);
        } 

        // If current player has his hand empty -> he is the winner
        if (!empty($this->$currentPlayer)) {
            $this->currentPlayer = $currentPlayer;
            $this->playRound();
        } else {
            $this->end          = true;
            $this->dominoLogs[] = "Game Over. Player " . $this->currentPlayer . " won the game";
    
            $this->updateView("winner", []);
        }
    }

    /**
     * Checks a Tile against the played pile and add it if it can be use
     *
     * @param $value
     * @param $bottomPileEnds
     * @param $currentPlayer
     * @param $key
     *
     * @return bool
     */
    private function checkTile($value, $bottomPileEnds, $currentPlayer, $key)
    {
        if ($value[1] == $bottomPileEnds[0]) {
            // Card will go on the left of the pile
            $this->bottomPile     = array_merge([$value], $this->bottomPile);
            $this->bottomPileEnds = [$value[0], $bottomPileEnds[1]];
            $this->playTile($value, $currentPlayer, $key, 'left');
            $endTurn = true;
        } elseif ($value[1] == $bottomPileEnds[1]) {
            // Card will go on the right at the pile but flipped
            $this->bottomPile     = array_merge($this->bottomPile, [array_reverse($value)]);
            $this->bottomPileEnds = [$bottomPileEnds[0], $value[0]];
            $this->playTile($value, $currentPlayer, $key, 'right');
            $endTurn = true;
        } elseif ($value[0] == $bottomPileEnds[0]) {
            // Card will go at the left at the pile but flipped
            $this->bottomPile     = array_merge([array_reverse($value)], $this->bottomPile);
            $this->bottomPileEnds = [$value[1], $bottomPileEnds[1]];
            $this->playTile($value, $currentPlayer, $key, 'left');
            $endTurn = true;
        } elseif ($value[0] == $bottomPileEnds[1]) {
            // Card will go at the right of the pie
            $this->bottomPile     = array_merge($this->bottomPile, [$value]);
            $this->bottomPileEnds = [$bottomPileEnds[0], $value[1]];
            $this->playTile($value, $currentPlayer, $key, 'right');
            $endTurn = true;
        } else {
            $endTurn = false;
        }

        return $endTurn;
    }


    /**
     * Play the tile and remove it from 'hand'
     *
     * @param $value
     * @param $currentPlayer
     * @param $key
     * @param $position
     */
    private function playTile($value, $currentPlayer, $key, $position)
    {
        $this->dominoLogs[] = $currentPlayer . ' plays the tile ' . $this->getTileName($value) . ' by adding it to the ' . $position . ' of the pile';

        $currentBoard = "";
        foreach ($this->bottomPile as $k => $item) {
            $currentBoard .= $this->getTileName($item);
        }
        $this->dominoLogs[] = 'The current board is: ' . $currentBoard;
        unset($this->$currentPlayer[$key]);

        $this->updateView($currentPlayer, $value);
    }

    /**
     * We need to select the player to start the game
     * based on bigger double 
     * 
     *  @param string $firstTile
     * 
     */
    private function firstPlayer($firstTile)
    {
        $searchPlayer = "";

        if ($firstTile) {
            foreach ($this->players as $player) {
                $searchPlayer = array_search($firstTile, $this->$player);
               
                if ($searchPlayer !== false) {
                    return $player;
                }
            }
        }

        return $searchPlayer;
    }

    /**
     * We need to select the tile to start the game
     * based on bigger double 
     */
    private function firstTile()
    {
        $tileStart = $this->compareDoublesTiles();

        return $tileStart;
    }

    /**
     * Transforms the card array into a 'readable' domino piece
     *
     * @param array $values
     *
     * @return string
     */
    private function getTileName(array $values)
    {
        return '<' . current($values) . ':' . end($values) . '>';
    }

    /**
     * Compare tiles between Players
     *  
     */
    private function compareDoublesTiles()
    {
        $tileDouble = [];
        
        foreach ($this->players as $player) {
            foreach ($this->$player as $key => $value) {
                if ($value[0] === $value[1]) {
                    $tileDouble[] = [$value[0],$value[1]];
                }
            }
        }
       
        if (count($tileDouble) > 0) {
            $playerMax = max($tileDouble);
        } else {
            $playerMax = max($this->PlayerOne);
            $this->dominoLogs[]  = '*** NOTE: No Double Tile was detected,<br>so by default the first player to move will by: PlayerOne,<br>with the bigger Tile: ' . $this->getTileName($playerMax) . ' ***';
        }

        return $playerMax;
    }

    /**
     * Update view frontend side
     *
     *  @param string $action
     *  @param array $values
     *
     */
    private function updateView($action, array $values)
    {
        if ($action) {
            switch ($action) {
                case 'PlayerOne':
                case 'PlayerTwo':
                    $valToUpdate["playerUpdate"] = $this->currentPlayer;
                    $valToUpdate["tileUpdate"] = implode("",$values);

                    require './views/updateTiles.php';
                    break;
                case 'drawTile':
                    $valToUpdate["playerUpdate"] = $this->currentPlayer;
                    $valToUpdate["tileDraw"] = implode("",$values[1][0]);

                    require './views/drawTile.php';
                    break;
                case 'winner':
                    $_SESSION["dominoLog"] = $this->dominoLogs;

                    $playerWinner = $this->currentPlayer;

                    require './views/winnerPlayer.php';
                    break;
                
                default:
                    break;
            }
        }
    }

  }
  