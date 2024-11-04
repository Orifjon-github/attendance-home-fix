<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Locale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->header('Accept-Language');
        if (in_array($lang, ['ru', 'uz', 'en'])) App::setLocale($lang);

        return $next($request);
    }
}
