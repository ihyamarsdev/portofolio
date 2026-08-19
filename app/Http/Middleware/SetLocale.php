<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'id'];

        // 1. Check query string ?lang=id or ?lang=en
        if ($request->has('lang') && in_array($request->query('lang'), $supportedLocales)) {
            $locale = $request->query('lang');
            Session::put('locale', $locale);
        }
        // 2. Check session
        elseif (Session::has('locale') && in_array(Session::get('locale'), $supportedLocales)) {
            $locale = Session::get('locale');
        }
        // 3. Fallback to default config
        else {
            $locale = config('app.locale', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
