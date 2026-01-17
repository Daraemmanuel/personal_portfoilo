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

        // Content Security Policy (CSP)
        // - In production: strict, only your domain + analytics + Bunny fonts
        // - In local/dev: allow Vite dev server and Bunny fonts
        if (config('app.env') === 'production') {
            $csp = implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com",
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
                "font-src 'self' https://fonts.bunny.net data:",
                "img-src 'self' data: https:",
                "connect-src 'self' https://www.google-analytics.com",
            ]);
        } else {
            // Local / non-production: allow Vite dev server + Bunny fonts
            // Note: IPv6 [::1] format is not valid in CSP, so we use localhost and 127.0.0.1
            $viteHosts = "http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173";

            $csp = implode('; ', [
                "default-src 'self' {$viteHosts}",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$viteHosts}",
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
                "font-src 'self' data: https://fonts.bunny.net",
                "img-src 'self' data: https:",
                "connect-src 'self' {$viteHosts}",
            ]);
        }

        $response->headers->set('Content-Security-Policy', $csp);

        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
