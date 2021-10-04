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
        $checkArray = ['payQuan', 'payFullnamee', 'payChecks', 'payDigit', 'payItem', 'payCat', 'payNor', 'payAvail', 'payUnit'];
        foreach ($checkArray as $check){
            if(isset($this->request[$check])) {
                if (empty($this->request[$check])) $software->feedback(false, 'PAY_000', ['Oops! '.$check.' was not provided.']);
            }else $software->feedback(false, 'PAY_001', ['Oops! '.$check.' was not set.']);
        }

       $quantity = $this->request['payQuan'];
       $normal_price = $this->request['payNor'];
       $total = ($normal_price * $quantity);


    //    $client = new MongoDB\Client(
    //     'mongodb+srv://kay:myRealPassword@cluster0.mongodb.net/?ssl=true&authSource=admin&serverSelectionTryOnce=false&serverSelectionTimeoutMS=15000"');
    
    //    $db = $client->test;




         // connect to mongodb
        // $m = new MongoClient();
        // echo "Connection to database successfully";
            

        // // select a database
        // $db = $m->mydb;
        // echo "Database mydb selected";
        // $collection = $db->mycol;
        // echo "Collection selected succsessfully";
            
        // $payData = array( 
        //     "item" => $this->request['payItem'], 
        //     "category" => $this->request['payCat'], 
        //     "normalPrice" => $this->request['payNor'],
        //     "availablePrice" => $this->request['payAvail'],
        //     "unitPrice" => $this->request['payUnit'],
        //     "fullname" => $this->request['payFullname'],
        //     "quantity" => $this->request['payQuan'],
        //     "paymentType" => $this->request['payChecks'],
        //     "total" => $total,
        //     "digit" => $this->request['payDigit']
        // );
            
        // $collection->insert($payData);

        // $client = new MongoDB\Client(
        //     'mongodb+srv://<username>:<password>@<cluster-address>/test?retryWrites=true&w=majority'
        // );
        // $db = $client->test;

        $software->feedback(true, 'OK', ['Payment was successful'], ['total'=>$total]);
    
    }
}