<?php

namespace App\Http\Middleware;

use Closure;
use App\Member;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header("Authorization")){
            $key = explode(" ", $request->header("Authorization"));
            $token = $key[1];
            $check = Member::where("token", $token)->count();
            if ($check > 0) {
                return $next($request);
            } else {
                return response(["error" => "Invalid Token"]);
            }
        }
        else {
            return response(["error" => "Unauthorized"]);
        }
    }
}
