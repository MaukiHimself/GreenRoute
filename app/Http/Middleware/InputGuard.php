<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Injects the system-wide input-restriction script (public/js/input-guard.js)
 * before </body> on every HTML page, so numeric/name field rules apply
 * everywhere without editing individual views.
 */
class InputGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = (string) $response->headers->get('Content-Type');
        if (stripos($contentType, 'text/html') === false && $contentType !== '') {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || stripos($content, '</body>') === false) {
            return $response;
        }

        $tag = '<script src="' . asset('js/input-guard.js') . '" defer></script>';

        // Inject before the last </body> only.
        $pos = strripos($content, '</body>');
        $content = substr($content, 0, $pos) . $tag . "\n" . substr($content, $pos);
        $response->setContent($content);

        return $response;
    }
}
