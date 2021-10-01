<?php
/*
* Use try/catch so that is doesnt halt other cron jobs on error
*/
try {
    $last_pending = MVC.'extension/logs/keys.txt';
    $nextCron = file_get_contents($last_pending);
    if($thiscron > $nextCron){
        file_put_contents($last_pending,$thiscron+1440);

        /* Get from the file */
	   $pathPriv = INC."pem/";
	   $file = $pathPriv . 'priv.key';
	   $privKey = file_get_contents($file);

       /* Get from the file */
	   $pathPub = INC."pem/";
       $file = $pathPub . 'pub.key';
	   $pubKey = file_get_contents($file);

       if($privKey && $pubKey){

            $message = "Keys already created successfully ".date("Y-m-d h:i:s");
            $software->adminAlert($message);
        } 
       else {

            $software->createKeys();
            $message = "Keys created successfully On ".date("Y-m-d h:i:s");
            $software->adminAlert($message);
       }
        
    }
} catch (Exception $e) {
    $message = 'Attention technical team: Error on '.__FILE__.': '.$e->getMessage();
    $software->adminAlert($message);
}