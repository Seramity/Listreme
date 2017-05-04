<?php

namespace App\Middleware;


class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if($this->container->auth->check()) {
            $this->container->flash->addMessage('global_notice', 'You cannot access that page');
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}