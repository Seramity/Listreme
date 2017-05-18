<?php

namespace App\Middleware;

/**
 * Class AuthMiddleware
 *
 * Middleware that checks if a user is logged in to grant permission to certain pages.
 *
 * @package App\Middleware
 */
class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(!$this->container->auth->check()) {
            $this->container->flash->addMessage('global_notice', 'You must be signed in to do that');
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}