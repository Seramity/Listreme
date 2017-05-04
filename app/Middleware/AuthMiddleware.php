<?php

namespace App\Middleware;


class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(!$this->container->auth->check()) {
            $this->container->flash->addMessage('global_notice', 'You must be signed in to access that page');
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}