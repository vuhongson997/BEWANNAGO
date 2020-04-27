<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class CheckSingleSession
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
        // \Auth::logoutOtherDevices(Auth::User()->session_id);
        $previous_session = Auth::User()->session_id;
        if ($previous_session !== Session::getId()) {
    
            Session::getHandler()->destroy($previous_session);
    
            $request->session()->regenerate();
            Auth::user()->session_id = Session::getId();
            Log::info('session',['id'=>Session::getId()]);
            Auth::user()->save();
        }
        return $next($request);
    }
    
}
