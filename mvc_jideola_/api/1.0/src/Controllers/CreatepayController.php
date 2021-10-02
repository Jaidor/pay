<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class CreatepayController {
    use Request;

    public function payment(){
        global $software;

        /* Validate */
        $checkArray = ['username', 'firstname', 'surname', 'lastname', 'email', 'phone','address','password','confirmPassword','terms'];
        foreach ($checkArray as $check){
            if(isset($this->request[$check])) {
                if (empty($this->request[$check])) $software->feedback(false, 'REG_000', ['Oops! '.$check.' was not provided.']);
            }else $software->feedback(false, 'REG_001', ['Oops! '.$check.' was not set.']);
        }
    
    }
}