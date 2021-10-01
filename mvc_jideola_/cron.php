<?php
/* 
* Where version is the application based on the URL structure 
*/
header('Content-Type: application/json');

$application = $software->getVersion();
if(empty($application)) die(json_encode(['error' => 'Application not defined']));

$max_time_limit = 180; /* In secondsv */
set_time_limit($max_time_limit);
error_reporting(0);
ob_end_clean(); 

/* Prevent more than one instance per minute */
$lastcron = file_get_contents(MVC.'extension/logs/lastCron_'.$application.'.txt');
if(empty($lastcron))$lastcron = 0;
$thiscron = ceil(time()/60);
if($thiscron - $lastcron < 1 )  die(json_encode(['error' => 'Multiple cron instance per minute prevented!']));
file_put_contents(MVC.'extension/logs/lastCron_'.$application.'.txt',$thiscron);

$cronFiles = $software->mapFolder(MVC.'extension/cron');
sort($cronFiles);
echo 'Application: '.$application.'<hr />';
/* Place this before any script you want to calculate time */
$time_start = microtime(true); 


foreach($cronFiles as $cronFile){
	if(file_exists($cronFile) && !is_dir($cronFile) && $software->getExtension($cronFile) == 'php'){

        file_put_contents(MVC.'extension/logs/lastCron_'.$application.'.txt',$thiscron);

        echo str_replace(['.','_','php'],' ',basename($cronFile)).'<br />';
		$time_start2 = microtime(true);
		include_once($cronFile);
		$time_end2 = microtime(true);
		$execution_time2 = ($time_end2 - $time_start2);

		/* Execution time of the script */
		echo '<b>Execution Time:</b> '.number_format($execution_time2,9).' Secs<hr />';
	} 
	flush();
} 
$time_end = microtime(true);

/* Dividing with 60 will give the execution time in minutes other wise seconds */
$execution_time = $time_end - $time_start;

/* Execution time of the script */
echo '<b>Overall Execution Time:</b> '.number_format($execution_time,0).' Seconds<hr />';
$software->dbclose();
echo 'Finished!';

file_put_contents(MVC.'extension/logs/lastCron_'.$application.'.txt',$thiscron);
die();
?>