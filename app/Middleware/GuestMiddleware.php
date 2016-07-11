<?php

namespace App\Middleware;

class GuestMiddleware extends Middleware {
     public function __invoke($request, $responce, $next) {
            
            if ($this->container->auth->check()) {
                return $responce->withRedirect($this->container->router->pathFor('home'));
            }
            
            $responce = $next($request, $responce);
            return $responce;
    }
}

?>