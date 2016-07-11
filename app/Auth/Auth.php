<?php

namespace App\Auth;

use App\Models\User;

class Auth {

    public function user() {
        if(isset($_SESSION['user'])) {
            return User::find($_SESSION['user']);
        }
    }

    public function check() {
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password) {

        // Grab The User by Email
        $user = User::where('email', $email)->first();

        // if the user does not exist return false
        if (!$user) {
                return false;
        }

        // verify password for that user
        if(password_verify($password, $user->password)) {
                // set into session
                $_SESSION['user'] = $user->id;
                return true;
        }

        return false;

    }

    public function logout() {
        unset($_SESSION['user']);
    }

}

?>