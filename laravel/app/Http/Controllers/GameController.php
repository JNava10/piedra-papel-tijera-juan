<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Hand;
use App\Models\Player;
use App\Models\Turn;
use Error;
use Exception;
use Illuminate\Http\Request;

class GameController {
    public function createGame(Request $request) {
        $game = new Game();

        try {
            if (
                !isset($request->maxRounds) ||
                !isset($request->player)
            ) {
                throw new Exception('Invalid body fields.');
            }

            if (!$this->playerExist($request->player)) {
                throw new Exception('Player don\'t exist.');
            }

            $game->maxRounds = $request->maxRounds;
            $game->playerOne = $request->player;
            $game->playerTwo = 0;
            $game->winnedBy = 0;
            $game->rounds = 1;

            return $game->save();
        } catch (Exception $exception) {
            return response($exception->getMessage(), 400);
        }
    }

    public function play(Request $request, int $gameId) {
        try {
            $turn = new Turn();
            $turnGame = Game::find($gameId);
            $roundTurns = Turn::where('game', '=', $gameId)->where('round', '=', $turnGame->rounds)->get();
            $firstTurnOfRound = $roundTurns->first();

            // Error Handling //

            if (
                !isset($request->playerId) ||
                !isset($request->hand)
            ) {
                throw new Exception('Invalid body fields.', 1);
            }

            if (!$this->playerExist($request->playerId)) {
                throw new Exception('Player don\'t exist.', 2);
            }

            if (!isset($turnGame)) {
                throw new Exception('No games founded.', 3);
            }

            if (
                $request->playerId !== $turnGame->playerOne &&
                $request->playerId !== $turnGame->playerTwo
            ) {
                throw new Exception('No permission.', 4);
            }

            if (!isset($roundTurns)) {
                throw new Exception('Game not found.', 5);
            }

            if (
                isset($firstTurnOfRound) &&
                $roundTurns->first()->player === $request->playerId
            ) {
                throw new Exception('Player already played this round.', 6);
            }

            if (!$this->isValidHand($request->hand)) {
                throw new Exception('Invalid hand.', 7);
            }

            if (
                isset($firstTurnOfRound) &&
                $this->handIsAlreadyPlayed($roundTurns, $request)
            ) {
                throw new Exception('Hand already played.', 8);
            }

            if (
                $roundTurns->count() === 2 &&
                $turnGame->rounds === $turnGame->maxRounds
            ) {
                throw new Exception('Game finished.', 9);
            }

            // Handle data without errors //

            if ($roundTurns->count() === 2) {
                $turn->round = $turnGame->rounds + 1;
                $turnGame->rounds++;
                $turnGame->save();
            } else {
                $turn->round = $turnGame->rounds;
            }

            $turn->game = $gameId;
            $turn->player = $request->playerId;
            $turn->hand = $request->hand;

            if (
                $turnGame->rounds === $turnGame->maxRounds &&
                isset($firstTurnOfRound)
            ) {
                $turn->save();
                return response(['winner' => $this->getWinner($turnGame)]);
            } else {
                return response($turn->save());
            }
        } catch (Exception $exception) {
            if ($exception->getCode() === 4) {
                return response($exception->getMessage(), 409);
            } else {
                return response($exception, 400);
            }
        }
    }

    public function joinGame(Request $request) {
        try {
            $firstGameFree = Game::where('playerTwo', 0)->first();
            $game = isset($request->gameId) ? Game::find($request->gameId) : $firstGameFree;

            if (!isset($request->playerId)) {
                throw new Exception('Invalid body fields.', 1);
            }

            if ($request->playerId === $game->playerOne || $request->playerId === $game->playerTwo) {
                throw new Exception('Player already joined.', 2);
            }

            if (!$this->playerExist($request->playerId)) {
                throw new Exception('Player don\'t exist.', 3);
            }

            if (!isset($game)) {
                throw new Exception('No games free.', 4);
            }

            $game->playerTwo = $request->playerId;

            return response($game->save());
        } catch (Exception $exception) {
            echo $exception;
            return response('Exception (' . $exception->getCode() . ')', 400);
        }
    }

    public function getAll() {
        return Game::all();
    }

    private function playerExist(int $playerId) {
        return Player::find($playerId) !== null;
    }

    private function isValidHand(int $hand) {
        return Hand::find($hand) !== null;
    }

    private function handIsAlreadyPlayed($roundTurns, $request) {
        return $request->hand === $roundTurns->first()->hand;
    }

    private function getWinner(Game $turnsGame) {
        $allGameTurns = Turn::where('game', '=', $turnsGame->id);
        $playerOneWins = 0;
        $playerTwoWins = 0;

        for ($i = 0; $i < $turnsGame->maxRounds; $i++) {
            $firstTurn = $allGameTurns->where('round', $i + 1)->get()[0];
            $secondTurn = $allGameTurns->where('round', $i + 1)->get()[1];
            $firstTurnHand = Hand::where('id', $firstTurn->hand)->get()->first();
            $secondTurnHand = Hand::where('id', $secondTurn->hand)->get()->first();

            if ($secondTurn->hand === $firstTurnHand->beats) {
                if ($firstTurn->player === $turnsGame->playerOne) {
                    $playerOneWins++;
                } else {
                    $playerTwoWins++;
                }
            } else {
                if ($secondTurn->player === $turnsGame->playerOne) {
                    $playerOneWins++;
                } else {
                    $playerTwoWins++;
                }
            }

//            echo json_encode([
//                "round" => $i,
//                "firstHand" => $firstTurnHand->name,
//                "secondHand" => $secondTurnHand->name
//            ]);

            $allGameTurns = Turn::where('game', '=', $turnsGame->id);
        }

        echo json_encode(['playeronewins' => $playerOneWins]);
        echo json_encode(['playertwowins' => $playerTwoWins]);

        if ($playerOneWins > $playerTwoWins) {
            return $turnsGame->playerOne;
        } elseif ($playerOneWins < $playerTwoWins){
            return $turnsGame->playerTwo;
        } else {
            return 0;
        }
    }
}
