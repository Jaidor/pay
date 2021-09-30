<?php
defined("_afrisoft") or die();
set_time_limit(0);
header('Content-Type: application/json');
ob_end_clean();

$header =  apache_request_headers();
$authToken =  (isset($header['Authorization']))? $header['Authorization'] : '';
$authToken = str_replace('Basic ', '', $authToken);
$authToken = base64_decode($authToken);
if($authToken != "CALLBACK:TEST") die(json_encode(['status'=>false, 'code' => '09', 'message'=>'Authorization Header missing']));

$json = file_get_contents('php://input');
$array = json_decode($json,true);
$data = $software->array2string($array);

file_put_contents(MVC."extension/logs/callback_url.txt", print_r($array, true), FILE_APPEND);
