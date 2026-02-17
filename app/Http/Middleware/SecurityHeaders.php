<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Enable back/forward cache (bfcache) for HTML pages
        // Don't set Cache-Control headers that prevent bfcache
        // Only set restrictive cache headers for API/JSON responses
        if ($request->expectsJson() || $request->is('api/*')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        }

        // Content Security Policy (CSP)
        $isProduction = config('app.env') === 'production';
        $isDebug = config('app.debug', false);
        
        $viteHosts = "http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173";
        $analyticsHosts = "https://www.googletagmanager.com https://www.google-analytics.com";

        if ($isProduction && !$isDebug) {
            // Strict production policy
            $csp = implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$analyticsHosts}",
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
                "font-src 'self' https://fonts.bunny.net data:",
                "img-src 'self' data: https: blob:",
                "connect-src 'self' https://www.google-analytics.com",
            ]);
        } else {
            // Allow Vite and analytics for local/debug environments
            $csp = implode('; ', [
                "default-src 'self' {$viteHosts}",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$viteHosts} {$analyticsHosts}",
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
                "font-src 'self' data: https://fonts.bunny.net",
                "img-src 'self' data: https: blob:",
                "connect-src 'self' {$viteHosts} https://www.google-analytics.com",
            ]);
        }

        $response->headers->set('Content-Security-Policy', $csp);

        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
