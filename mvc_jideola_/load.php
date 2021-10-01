<?php
set_time_limit(120);
ob_end_clean();
header('Content-Type: application/json');

$header =  apache_request_headers();
$xAuthToken =  (isset($header['X-Authorization']))? $header['X-Authorization'] : '';  /* Some servers uses X-Authorization  */
$authToken =  (isset($header['Authorization']))? $header['Authorization'] : '';  /* While some servers uses Authorization  */

$finalToken =  ($xAuthToken)? $xAuthToken : $authToken;
$finalToken = str_replace('Bearer ', '', $finalToken);

$software->setHeader($header); /* Set header  */
$software->setToken($finalToken); /* Set session  */

$request_link = $_SERVER['REQUEST_URI'];
$request_link = explode('/',$request_link);

print_r($_SERVER['REQUEST_URI']);
// unset($request_link[0]);

// $method = $software->antiHacking($request_link[2]);
// $scope = $software->antiHacking($request_link[4]);
// $version = $software->antiHacking($request_link[5]);
// $call = $software->antiHacking($request_link[6]);

// /* Avoid camel case */
// $method = strtolower($method);
// $scope = strtolower($scope);
// $version = strtolower($version);
// $call = strtolower($call);

// /* Avoid directory traversal attack */
// $method = str_replace('../', '', $method);
// $call = str_replace('../', '', $call);
// $scope = str_replace('../', '', $scope);
// $version = str_replace('../', '', $version);
// $version = str_replace('v','',$version);


// $software->setMethod($method); /* Set method  */
// $software->setCall($call); /* Set call  */
// $software->setScope($scope); /* Set scope  */
// $software->setVersion($version); /* Set version  */

// if($scope == 'cron') {include_once MVC.'cron.php'; die();}
// if($scope == 'test') {include_once MVC.'test.php'; die();}
// if($scope == 'callback') {include_once  MVC .'extension/callback/'.$call.'.php'; die();}


// if (!is_dir(MVC .$scope)) die(json_encode(['error' => 'Application does not exist']));
// if (!is_dir(MVC .$scope.'/'. $version)) die(json_encode(['error' => 'Specified version does not exist']));
// $file_path = MVC.$scope."/".$version."/endpoints/".$call.".php";

// $_SERVER['ENDPOINT'] = $call;

// $autoload_path = MVC.$scope."/".$version."/autoload_index.php";
// if(file_exists($autoload_path)) include_once $autoload_path;
// else echo die(json_encode(['error' => 'Application could not load']));

// if(file_exists($file_path)) include_once $file_path;
// else die(json_encode(['error' => 'Silence is Golden']));
