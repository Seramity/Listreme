<?php

namespace App\Middleware;

/**
 * Class RememberMeMiddleware
 *
 * Middleware that checks for a "remember me" cookie to re-login the user if their session ends.
 *
 * @package App\Middleware
 */
class RememberMeMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {

        if (isset($_COOKIE[$this->container->get('settings')['auth']['remember']]) && !$this->container->auth->check()) {
            $data = $_COOKIE[$this->container->get('settings')['auth']['remember']];
            $credentials = explode('___', $data);

            if (empty(trim($data)) || count($credentials) !== 2) {
                setcookie($this->container->get('settings')['auth']['remember'], "", time() - 3600, $request->getUri()->getBasePath(), null, false, true);
                $response->withRedirect($this->container->router->pathFor('auth.signin'));
            } else {
                $identifier = $credentials[0];
                $token = $this->container->hash->hash($credentials[1]);

                $user = $this->container->user
                    ->where('remember_identifier', $identifier)
                    ->first();

                if ($user) {
                    if ($this->container->hash->hashCheck($token, $user->remember_token)) {
                        $_SESSION['user'] = $user->auth_id;
                    } else {
                        $user->removeRemember();
                    }
                }
            }
        }

        // CALL NEXT MIDDLEWARE
        $response = $next($request, $response);
        return $response;
    }
}