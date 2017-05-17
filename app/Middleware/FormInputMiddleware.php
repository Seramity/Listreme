<?php

namespace App\Middleware;

/**
 * Class FormInputMiddleware
 *
 * Middleware that saves user input from forms into sessions in case form errors occur.
 * This prevents users having to re-enter data that is not sensitive.
 *
 * @package App\Middleware
 */
class FormInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['old_input'])) {
            $this->container->view->getEnvironment()->addGlobal('old_input', $_SESSION['old_input']);
            $_SESSION['old_input'] = $request->getParams();
        } else {
            unset($_SESSION['old_input']); // Removes bug of session never setting or stuck on empty
        }

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}