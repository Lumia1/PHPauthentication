<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware {
     public function __invoke($request, $responce, $next) {
            
            if (!$this->container->auth->check()) {
                    $this->container->flash->addMessage('error', 'Please Sign in before doing that.');
                    return $responce->withRedirect($this->container->router->pathFor('auth.signin'));
            }
            
            $responce = $next($request, $responce);
            return $responce;
    }
}

?>