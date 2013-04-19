<?php

$userName = $_POST['username'];
$profileFile='../../jsonfiles/'.$userName.'/'.$userName.'.FBProfile.json';

$lastRunLog = '../../jsonfiles/'.$userName.'/lastrun.log';

//if log file exists
if (file_exists($lastRunLog))
{
	//read last entry in the log file
	$lastRT = read_lastline_from_file($lastRunLog);	
   
}	
else 
	//assign 2000 as the default time if no log file is created
	$lastRT = 946684800;
	
//return created time
	echo $lastRT;


//function for reading last line
function read_lastline_from_file($lastRunLog)
{
	$line = '';

	$f = fopen($lastRunLog, 'r');
	$cursor = -1;

 	// Trim trailing newline chars of the file
 
	while ($char === "\n" || $char === "\r") 
	{
    	fseek($f, $cursor--, SEEK_END);
    	$char = fgetc($f);
	}

		 //Read until the start of file or first newline char

	while ($char !== false && $char !== "\n" && $char !== "\r") 
	{
		// Prepend the new char
    	
   		$line = $char . $line;
    	fseek($f, $cursor--, SEEK_END);
    	$char = fgetc($f);
	}

	return $line;
	
}
?>
