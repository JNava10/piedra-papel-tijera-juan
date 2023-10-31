<?php

namespace App\Http\Middleware;

use App\Models\Player;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LoginValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $playerCredentials = Player::where('name', '=', $request->user)->get()->first();

            if (!$playerCredentials) {
                throw new Exception('User not found', 11);
            }

            if ($playerCredentials->password === md5($request->password)) {
                return $next($request);
            } else {
                throw new Exception('Invalid login data.', 12);
            }
        } catch (Exception $exception) {
            return response(['logged' => false], 400);
        }
    }
}
