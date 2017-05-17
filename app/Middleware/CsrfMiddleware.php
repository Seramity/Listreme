<?php

namespace App\Middleware;

/**
 * Class CsrfMiddleware
 *
 * Middleware that sets cross-site request forgery tokens for forms into the Slim Twig view.
 *
 * @package App\Middleware
 */
class CsrfMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $this->container->csrf->getTokenNameKey() . '" value="' . $this->container->csrf->getTokenName() . '">
                <input type="hidden" name="' . $this->container->csrf->getTokenValueKey() . '" value="' . $this->container->csrf->getTokenValue() . '">
            '
        ]);

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}