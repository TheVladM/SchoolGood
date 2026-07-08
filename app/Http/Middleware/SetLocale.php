<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = ['fr', 'en'];
        $locale    = session('locale', config('app.locale', 'fr'));

        if (! in_array($locale, $supported)) {
            $locale = 'fr';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
