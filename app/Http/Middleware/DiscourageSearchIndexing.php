<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * On any non-production environment (staging, local, ...), tell search
 * engines not to index or follow anything. Production is untouched, so
 * the main site's SEO is unaffected when the same code is deployed there.
 */
class DiscourageSearchIndexing
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (config('app.env') !== 'production') {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow', false);
        }

        return $response;
    }
}
