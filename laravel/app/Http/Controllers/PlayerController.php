<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Exception;
use Illuminate\Http\Request;

class PlayerController {
    public function create(Request $request) {
        $player = new Player();

        try {
            if (isset($request->name)) {
                $player->name = $request->name;
            }

            if (isset($request->password)) {
                $player->password = md5($request->password);
            }

            if (isset($request->played)) {
                $player->played = $request->played;
            } else {
                $player->played = 0;
            }

            if (isset($request->winned)) {
                $player->winned = $request->winned;
            } else {
                $player->winned = 0;
            }

            if (isset($request->role)) {
                $player->role = $request->role;
            } else {
                $player->role = 2;
            }

            if (isset($request->enabled)) {
                $player->enabled = true;
            } else {
                $player->enabled = true;
            }


            $inserted = response($player->save());
        } catch (Exception $exception) {
            return $exception;
        }

        return $inserted;
    }

    public function read($id = null) {
        try {
            if (isset($id)) {
                return response(Player::find($id));
            }
            else {
                return response(Player::all());
            }
        } catch (Exception $exception) {
            return response('Internal Server Error', 500);
        }
    }

    public function update(Request $request, int $id) {
        $player = Player::find($id);

        if (isset($request->name)) {
            $player->name = $request->name;
        }

        if (isset($request->password)) {
            $player->password = $request->password;
        }

        if (isset($request->played)) {
            $player->played = $request->played;
        }

        if (isset($request->winned)) {
            $player->winned = $request->winned;
        }

        if (isset($request->role)) {
            $player->role = $request->role;
        }

        // TODO: Check if new player is same of old player.
        return $player->save();
    }

    public function delete($id) {
        $player = Player::find($id);
        return response($player->delete());
    }
}
