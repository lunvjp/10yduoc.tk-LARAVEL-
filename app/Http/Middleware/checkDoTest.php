<?php

namespace App\Http\Middleware;

use Closure;
use App\do_test;
use App\test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class checkDoTest
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
        /*
         * 1. Nếu user đã làm đề đó thì cho return $next($request)
         * 2. Nếu chưa làm thì tạo sesstion warning
         */
        $check = do_test::where([
            ['users_id',Auth::id()],
            ['tests_id',$request->test_id]
        ])->first();

        if (!$check) {
            $validTest = test::where('id',$request->test_id)->first();
            if (!$validTest) return redirect()->route('index');
            Session::flash('checkDoTest',$validTest->name);
        }

        // Xuống đây tức là đã làm rồi

        return $next($request);
    }
}
