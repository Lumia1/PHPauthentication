<?php

namespace App\Middleware;

class OldInputMiddleware extends Middleware {
     public function __invoke($request, $responce, $next) {
        
            if(isset($_SESSION['old'])) {

            $this->container->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
            $_SESSION['old'] = $request->getParams();
            
            }


            $responce = $next($request, $responce);
            return $responce;
    }
}