<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Authetication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session = Session::getId();

        $admin = Admin::where('session_id', $session)->first();

        if($admin){
            return $next($request);
        }

        return response()->json([
            'status' => 0,
            'statuscode' => 401,
            'msg' => 'Unauthorized: Invalid or expired session',
        ],401);
    }
}
