<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class ConfigurationController {
    use Request;

    public function configure(){
        global $software;

        /* 
        * Update settings
        */
        if($this->request['type'] == 'modify'){
            // $username = $software->dbval("select role from #__users where id = '{$user['id']}'");
            // $cleaned=[];
            // foreach ($this->request as $key=> $info){
            //    if(is_array($info)) $cleaned[strtoupper($key)] = $info;
            //    else $cleaned[strtoupper($key)] = $info;
            // }
            // unset($cleaned['TYPE']);
            
            // $software->write_ini(CONFIG_FILE,$cleaned);

            // if($user['role'] == 1) $software->feedback(false, 'RES_000', ['Oops! Unauthorized...']);

            /* Validate */
            $check_array = ['site_name', 'site_title', 'site_description', 'default_role', 'pagesize', 'consecutive_login_fail','session_logout',
            'login_fail_blockout_hours','admin_phone','admin_email','admin_name','admin_email_name','admin_email_pass','admin_email_host','admin_email_port','admin_email_port2'];
            foreach ($check_array as $check){
                if(isset($this->request[$check])) {
                    if (empty($this->request[$check])) $software->feedback(false, 'RES_001', ['Oops! '.$check.' was not provided.']);
                }else $software->feedback(false, 'RES_002', ['Oops! '.$check.' was not set.']);
            }

            $config['site_name'] = $this->request['site_name'];
            $config['site_title'] = $this->request['site_title'];
            $config['site_description'] = $this->request['site_description'];
            $config['default_role'] = $this->request['default_role'];
            $config['pagesize'] = $this->request['pagesize'];
            $config['consecutive_login_fail'] = $this->request['consecutive_login_fail'];
            $config['session_logout'] = $this->request['session_logout'];
            $config['login_fail_blockout_hours'] = $this->request['login_fail_blockout_hours'];

            $config['admin_phone'] = $this->request['admin_phone'];
            $config['admin_email'] = $this->request['admin_email'];
            $config['admin_name'] = $this->request['admin_name'];
            $config['admin_email_name'] = $this->request['admin_email_name'];
            $config['admin_email_pass'] = $this->request['admin_email_pass'];
            $config['admin_email_host'] = $this->request['admin_email_host'];
            $config['admin_email_port'] = $this->request['admin_email_port'];
            $config['admin_email_port2'] = $this->request['admin_email_port2'];

            $config['email_verification_subject'] = $this->request['email_verification_subject'];
            $config['email_verification_message'] = $this->request['email_verification_message'];
            $info = $software->array2string($config);

            $query = "update #__config set config_data = '$info' where config_id = '1'";
            $update = $software->dbcountchanges($query);
            if($update > 0){
                $software->adminAlert("Settings modified by ---");
                $software->feedback(true, 'OK', ['Settings saved successfully.']);
            }else $software->feedback(false, 'RES_003', ['Unable to save settings.']);
        }

        /* 
        * Fetch settings
        */
        $config = $software->app_settings();

        $site['site_name'] = $config['site_name'];
        $site['site_title'] = $config['site_title'];
        $site['site_description'] = $config['site_description'];
        $site['default_role'] = $config['default_role'];
        $site['pagesize'] = $config['pagesize'];
        $site['consecutive_login_fail'] = $config['consecutive_login_fail'];
        $site['session_logout'] = $config['session_logout'];
        $site['login_fail_blockout_hours'] = $config['login_fail_blockout_hours'];

        $admin['admin_phone'] = $config['admin_phone'];
        $admin['admin_email'] = $config['admin_email'];
        $admin['admin_name'] = $config['admin_name'];
        $admin['admin_email_name'] = $config['admin_email_name'];
        $admin['admin_email_pass'] = $config['admin_email_pass'];
        $admin['admin_email_host'] = $config['admin_email_host'];
        $admin['admin_email_port'] = $config['admin_email_port'];
        $admin['admin_email_port2'] = $config['admin_email_port2'];

        $ver['email_verification_subject'] = $config['email_verification_subject'];
        $ver['email_verification_message'] = $config['email_verification_message'];

        $data['website_settings'] = $site;
        $data['mail_settings'] = $admin;
        $data['mail_verification'] = $ver;

        $software->feedback(true, 'OK', ['Fetched successfully'],[$data]);
    
    }
}