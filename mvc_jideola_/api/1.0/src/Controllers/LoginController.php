<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class LoginController {
    use Request;

    public function login(){
        global $software;

        /* Validate */
        $checkArray = ['username', 'password'];
        foreach ($checkArray as $check){
            if(isset($this->request[$check])) {
                if (empty($this->request[$check])) $software->feedback(false, 'LOG_000', ['Oops! '.$check.' was not provided.']);
            }else $software->feedback(false, 'LOG_001', ['Oops! '.$check.' was not set.']);
        }

        $username = $this->request['username'];
        $password = $this->request['password'];

        /* Checks if login user exist */
        $user = $software->dbrow("select * from #__users where username = '$username'");
        $password_match = $software->validatePass($password, $user['password']);
        $session_token = $software->_generateToken();

        /* Clear old sessions */
        $query = "delete from #__sessions where session_user_id = '{$user['id']}' and session_expires <= '".time()."'  ";
        $software->dbquery($query);

        /* This checks user for 5 times failed login consecutively */
        $config = $software->app_settings();
        if( $user['login_fail'] >= $config['consecutive_login_fail'] && $user['blocked_till'] > time()){
            $software->feedback(false, 'LOGIN_104', ['Your account has been locked because of '.$user['login_fail'].' consecutive failed logins. This is a security feature to safeguard your account. You may not login again until '.date('h:i jS M Y', $user['blocked_till']) ]);
        }

        if($user){
            if ($user['username'] && !$password_match) {

                /* Block for 30 minutes */
                $nextlogin = time() + $config['login_fail_blockout_hours'];
                $query = "update #__users set login_fail = login_fail+1, blocked_till = '$nextlogin' where id = '{$user['id']}' ";
                $software->dbquery($query);
                $software->feedback(false, 'LOG_002', ['Oops! Invalid password entered']);
            }

            /* 
            * Start session 
            * Inactive session logout after 1hr
            */
            $expires = time() + $config['session_logout'];
            $query = "insert into #__sessions(session_id, session_user_id, session_expires, session_start_time, session_ip, session_user_agent)
                    values('$session_token', '{$user['id']}', '$expires', now(),'{$software->ip}', '{$software->browser}')";
            $software->dbquery($query);

            /* Update users table */
            $query = "update #__users set last_login = latest_login, latest_login = now(), login_count = login_count+1, login_fail=0 where id = '{$user['id']}'";
            $software->dbquery($query);

            $software->feedback(true, 'LOGIN_OK', ['Login successful'], ['username' => $user['username'], 'names' => $user['names'],
            'email' => $user['email'], 'token' => $session_token]);

        }else $software->feedback(false, 'LOG_003', ['Oops! Invalid username entered']);
    }
}