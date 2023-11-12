<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\BitwiseXor;

class PlayerController {
    public function create(Request $request) {
        $player = new Player();

        try {
            if (
                !isset($request->newUserName) ||
                !isset($request->newUserPassword)
            ) {
                throw new Exception('Invalid body fields.', 400);
            }

            $player->name = $request->newUserName;
            $player->password = md5($request->newUserPassword);
            $player->played = isset($request->newUserPlayed) ? $request->newUserPlayed : 0;
            $player->winned = isset($request->newUserWinned) ? $request->newUserWinned : 0;
            $player->role = isset($request->newUserRole) ? $request->newUserRole : 2; // Normal user by default.
            $player->enabled = isset($request->newUserEnabled) ? $request->newUserEnabled : true;

            if (!Role::all('id')->find($player->role)) {
                throw new Exception('Role not found', 10);
            }

            return response(['executed' => $player->save()]);
        } catch (Exception $exception) {
            return response(['exception' => $exception->getCode()]);
        }
    }

    public function read($id = null) {
        try {
            if (isset($id)) {
                return response(['users' => Player::find($id)]);
            }
            else {
                return response(['users' => Player::all()]);
            }
        } catch (Exception $exception) {
            return response(['exception' => $exception->getCode()]);
        }
    }

    public function update(Request $request, int $id) {
        $player = Player::find($id);

        if (isset($request->newUserName)) {
            $player->name = $request->newUserName;
        }

        if (isset($request->newUserPassword)) {
            $player->password = md5($request->newUserPassword);
        }

        if (isset($request->newUserPlayed)) {
            $player->played = $request->newUserPlayed;
        }

        if (isset($request->newUserWinned)) {
            $player->winned = $request->newUserWinned;
        }

        if (isset($request->newUserRole)) {
            $player->role = $request->newUserRole;
        }

        return ['executed' => $player->save()];
    }

    public function delete($id) {
        $player = Player::find($id);
        return response(['executed' => $player->delete()]);
    }
}
