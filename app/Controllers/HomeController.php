<?php

namespace App\Controllers;

use App\Models\User;

class HomeController extends Controller {

    public function index($request, $responce) {
        return $this->view->render($responce, 'home.twig');;
    }

}

 ?>