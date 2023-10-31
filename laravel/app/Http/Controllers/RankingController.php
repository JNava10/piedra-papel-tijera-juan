<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function getUsers(int $top) {
        return response(
            [
                "ranking" => DB::table('players')
                    ->take($top)
                    ->orderBy('winned', 'desc')
                    ->get()
            ]
        );
    }

    public function getHands() {
        return response(
            [
                "ranking" => DB::table('hands')
                    ->orderBy('times_used', 'desc')
                    ->get(['id', 'name', 'times_used'])
            ]
        );
    }
}
