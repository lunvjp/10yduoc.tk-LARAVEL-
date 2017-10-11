<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class admin
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
        // Kiểm tra admin ở đây
        $user = Auth::user();

        $email = $user->email;

        // Kiểm tra nếu không phải admin thì
        if ($email != 'momabz6@gmail.com') {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
