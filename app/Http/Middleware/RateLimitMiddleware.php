<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next)
    {
        $key = $request->ip();
        $maxAttempts = 4;
        
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = 60;  
    
            $response = new Response('', 429);
            $response->setContent($response->getContent() . '
                <script>
                    var countdown = ' . $retryAfter . ';
                    var timer = setInterval(function() {
                        var minutes = Math.floor(countdown / 60);
                        var seconds = countdown % 60;
                        alert("Terlalu banyak permintaan. Silakan coba lagi dalam " + minutes + " menit " + seconds + " detik.");
                        countdown--;
                        if (countdown < 0) {
                            clearInterval(timer);
                            window.location.href = "/";
                        }
                    }, 1000);
                </script>
            ');

        return $response;
        }
    
        $this->limiter->hit($key, 1 * 60);
    
        return $next($request);
    }
}
