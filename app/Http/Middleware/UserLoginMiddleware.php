<?php

namespace App\Http\Middleware;

use Closure;

class UserLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //当前会话中无用户
        if (!session('userInfo')) {
            //回到登录
            return redirect('/login');
        }
        return $next($request);
    }
}
