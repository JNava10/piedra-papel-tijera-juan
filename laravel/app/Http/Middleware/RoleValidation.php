<?php

namespace App\Http\Middleware;

use App\Models\Player;
use App\Models\Role;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $adminRole = Role::where('name', '=', 'admin')->get()->first();
            $player = Player::where('name', '=', $request->user)->get()->first()->role;

            if (!$player) {
                throw new Exception('User not found', 11);
            }

            if ($player->role !== $adminRole->id) {
                return $next($request);
            } else {
                throw new Exception('You are not admin.', 12);
            }
        } catch (Exception $exception) {
            return response(['logged' => false], 400);
        }
    }
}
