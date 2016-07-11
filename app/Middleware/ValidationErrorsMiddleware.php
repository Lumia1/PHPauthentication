<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware {
    public function __invoke($request, $responce, $next) {
        
            if(isset($_SESSION['errors'])) {
                $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
                unset($_SESSION['errors']);
            }


            $responce = $next($request, $responce);
            return $responce;
    }
}

?>