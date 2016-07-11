<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller {

    public function getChangePassword($request, $responce) {
          return $this->view->render($responce, 'auth/password/change.twig');
    }

    public function postChangePassword($request, $responce) {

          $validation = $this->validator->validate($request, [
                'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
                'password' => v::noWhitespace()->notEmpty(),
            ]);

          if ($validation->failed()) {
              return $responce->withRedirect($this->router->pathFor('auth.password.change'));
          }

          $this->auth->user()->setPassword($request->getParam('password'));

          $this->flash->addMessage('info', 'Your Password was changed.');
          return $responce->withRedirect($this->router->pathFor('home'));
    }

}

 ?>