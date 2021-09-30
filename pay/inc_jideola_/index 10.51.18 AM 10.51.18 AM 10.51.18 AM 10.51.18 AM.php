<?php
ob_start();
session_start();

/* 
* Display errors 
*/

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

/* 
* Include files 
*/
if(file_exists(__DIR__.'/db.php')) {
	$params = include(__DIR__.'/db.php');
}

foreach (glob(__DIR__."/*.php") as $filename) { 
	$info = pathinfo($filename); 
	if ($info["filename"] != "db" && $info["filename"] != "rsa") {
		if($filename!=__FILE__) include $filename;
        echo "$filename filesize = " . filesize($filename) . "\n";
    }
}

$software = new softwareFunctions($params);
include_once MVC."load.php";
?>
