<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller {

    public function getSignOut($request, $responce) {
        $this->auth->logout();
        return $responce->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($request, $responce) {
          return $this->view->render($responce, 'auth/signin.twig');
    }

    public function postSignIn($request, $responce) {
          $auth = $this->auth->attempt(
              $request->getParam('email'),
              $request->getParam('password')
            );

          if(!$auth) {
            $this->flash->addMessage('error', 'Could not sign you in with those details :(');
            return $responce->withRedirect($this->router->pathFor('auth.signin'));
          }

          return $responce->withRedirect($this->router->pathFor('home'));
    }

    public function getSignUp($request, $responce) {
          return $this->view->render($responce, 'auth/signup.twig');
    }

    public function postSignUp($request, $responce) {

      $validation = $this->validator->validate($request, [
            'email' => v::noWhiteSpace()->notEmpty()->email()->emailAvailable(),
            'name' => v::notEmpty()->alpha(),
            'password' => v::noWhiteSpace()->notEmpty(),
        ]);

      if($validation->failed()) {
        return $responce->withRedirect($this->router->pathFor('auth.signup'));
      }

      $user = User::create([
        'email' => $request->getParam('email'),
        'name' => $request->getParam('name'),
        'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
      ]);

      $this->flash->addMessage('info', 'You have been signed up!');
      $this->auth->attempt($user->email, $request->getParam('password'));

      return $responce->withRedirect($this->router->pathFor('home'));
    }

}

 ?>