<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class CreatepayController {
    use Request;

    public function payment(){
        global $software;

        echo "I got into create payment safely....";
    
    }
}