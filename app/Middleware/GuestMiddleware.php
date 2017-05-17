<?php

namespace App\Middleware;

/**
 * Class GuestMiddleware
 *
 * Middleware that checks if a user is not logged in to grant permission to certain pages.
 *
 * @package App\Middleware
 */
class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if($this->container->auth->check()) {
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}