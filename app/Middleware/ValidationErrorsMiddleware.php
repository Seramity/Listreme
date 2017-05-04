<?php

namespace App\Middleware;


class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['validation_errors'])) {
            $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['validation_errors']);
            unset($_SESSION['validation_errors']);
        }


        //CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}