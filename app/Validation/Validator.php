<?php

namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator {

    protected $errors;

    public function validate($request, array $rules) {

            foreach ($rules as $key => $rule) {

                try {
                        $rule->setName(ucfirst($key))->assert($request->getParam($key));
                } 
                catch (NestedValidationException $e) {
                        $this->errors[$key] = $e->getMessages();
                }

            }

          $_SESSION['errors'] = $this->errors;

          return $this;  
    }


    public function failed() {
        return !empty($this->errors);
    }

}

?>