<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;
use Jideola\Traits\Templates;

class RegisterController {
    use Request,Templates;

    public function register(){
        global $software;

        /* Validate */
        // $checkArray = ['username', 'firstname', 'surname', 'lastname', 'email', 'phone','address','password','confirmPassword','terms'];
        // foreach ($checkArray as $check){
        //     if(isset($this->request[$check])) {
        //         if (empty($this->request[$check])) $software->feedback(false, 'REG_000', ['Oops! '.$check.' was not provided.']);
        //     }else $software->feedback(false, 'REG_001', ['Oops! '.$check.' was not set.']);
        // }

        // $email = $this->request['email'];
        // $phone = $this->request['phone'];
        // $username = $this->request['username'];
        // $pass = $this->request['password'];
        // $confirm_pass = $this->request['confirmPassword'];
        // $surname = $this->request['surname'];
        // $firstname = $this->request['firstname'];
        // $lastname = $this->request['lastname'];
        // $address = $this->request['address'];

        // if(!$software->isValidEmail($email)) $software->feedback(false, 'REG_002', ['Oops! Invalid email address.']);

        /* Checks if username, email & phone already exist */
        // $qry = "SELECT SUM(IF(email like '$email',1,0)) as email, SUM(IF(username like '$username',1,0)) as username,SUM(IF(phone like '$phone',1,0)) as phone FROM #__users";
	    // $used = $software->dbrow($qry);

        // if($used['username'] > 0) $software->feedback(false, 'REG_003', ['Oops! Username already exist.']);
        // if($used['email'] > 0) $software->feedback(false, 'REG_004', ['Oops! Email already exist.']);
        // if($used['phone'] > 0) $software->feedback(false, 'REG_005', ['Oops! Phone already exist.']);
      

        // if($pass != $confirm_pass) $software->feedback(false, 'REG_006', ['Password do not match']);
        // /* Check if password is strong */
        // if (strlen($pass) < 8)  $software->feedback(false, 'REG_007', ['Password should be at least 8 characters']);
        // if (!$software->passwordChecks($pass))  $software->feedback(false, 'REG_008', ['Password is not strong']);

        // $names = $surname.' '.$firstname.' '.$lastname;
        // $pass = $software->hashPass($pass);
        $config = $software->app_settings();

       /* Insert query */
    //    $query = $software->dbcountchanges("insert into #__users (username,names,email,phone,address,password,role) values('$username','$names','$email','$phone','$address','$pass','{$config['default_role']}')");
    $query = "1";
    $email="Ola_jidex@yahoo.com";
       if($query > 0) {

            /* 
            * Send mail
            */
            $button_link = '<div><a href="https://jideola.com/email/verify/'.$software->array2string($email).'" title="Email" class="btn btn-success btn-sm" target="_blank">Click here</a></div>';

            $send = array();
			$search = array();
			$replace = array();

			$search[] = '[[EMAIL]]';
			$search[] = '[[BUTTONLINK]]';
            $search[] = '[[SITENAME]]';
            $search[] = '[[MESSAGE]]';

			$replace[] = $email;
			$replace[] = $button_link;
            $replace[] = $config['site_name'];
            $replace[] = $config['email_verification_message'];
            $message = str_replace($search, $replace, Templates::EmailVerificationTemplate());

		    $send['message'] = $message;
			$send['email'] = $email;
            $send['sender'] = "";
            $send['attachment'] = "";
            $send['subject'] = $config['email_verification_subject'];
            $x = $software->sendMail($send);

            if($x) echo "I am Good";
            // $software->feedback(true, 'OK', ['Registration successful'],['note'=>'An email verification link has been sent to your email address ($email). Check your email and click on the link for activation']);
            else echo "I am not Good";
            // $software->feedback(true, 'OK', ['Registration successful'], ['note'=>'Unable to send your email verification link.']);
        }else $software->feedback(true, 'REG_100', ['Unable to register']);
    }
}