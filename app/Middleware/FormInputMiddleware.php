<?php

namespace App\Middleware;


class FormInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['old_input'])) {
            $this->container->view->getEnvironment()->addGlobal('old_input', $_SESSION['old_input']);
            $_SESSION['old_input'] = $request->getParams();
        }

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}