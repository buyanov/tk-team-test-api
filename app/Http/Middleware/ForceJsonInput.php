<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ForceJsonInput
{
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isJson()) {
            $request->headers->set('Accept', 'application/vnd.api+json');
            throw new HttpException(400, 'Bad Request', null);
        }

        return $next($request);
    }
}
