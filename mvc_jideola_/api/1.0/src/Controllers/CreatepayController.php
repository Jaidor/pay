<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class CreatepayController {
    use Request;

    public function payment(){
        global $software;

        // Array
        // (
        //     [payQuan] => 4
        //     [payFullname] => Olaleye Olajide
        //     [payChecks] => token
        //     [payDigit] => 234647
        //     [payItem] => Flipflops
        //     [payCat] => Florexworld
        //     [payNor] => 50000
        //     [payAvail] => 123
        //     [payUnit] => 20000
        // )

        /* Validate */
        $checkArray = ['payQuan', 'payFullname', 'payChecks', 'payDigit', 'payItem', 'payCat','payNor','payAvail','payUnit'];
        foreach ($checkArray as $check){
            if(isset($this->request[$check])) {
                if (empty($this->request[$check])) $software->feedback(false, 'REG_000', ['Oops! '.$check.' was not provided.']);
            }else $software->feedback(false, 'REG_001', ['Oops! '.$check.' was not set.']);
        }
    
    }
}