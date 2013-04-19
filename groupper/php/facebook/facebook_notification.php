<?php

header("Access-Control-Allow-Origin:*");

$userId = $_POST['userId'];

$json_a=array();

$indexFile = "../../jsonfiles/index.json";
	
	//get content of the index file
if(file_exists($indexFile))
{
		$indexFileContent = file_get_contents($indexFile);

		$json_a = json_decode($indexFileContent, true);
		
		//identifying number of entry in index file
		$indexContentLength = count($json_a['index']);
		
		for($j=0; $j<$indexContentLength; $j++)
		{
		   		if($json_a['index'][$j]['asguser'] == $userId)	
		   		{
					$dataDirectory = $json_a['index'][$j]['data']['socialDataPaths']['facebook'];
					$ifLNUser=$json_a['index'][$j]['data']['socialIDs']['linkedinId'];
					$ifGPUser=$json_a['index'][$j]['data']['socialIDs']['googlePlusId'];
					$lastRunLog = $dataDirectory.'/lastrun.log';
					
					$lastRT = read_lastline_from_file($lastRunLog);	
					date_default_timezone_set('UTC');
					$normalTime = date('Y-m-d H:i:s', $lastRT );
					$lastRun = $normalTime.",".$ifLNUser.",".$ifGPUser;
					echo $lastRun;
					break;
		  		}
				else if ($j == $indexContentLength)
				{
					echo 'No Data Found with this user';
				}
		}
}

else {
	echo 'No Index File Exists';
}


//function for reading last line
function read_lastline_from_file($lastRunLog)
{
	$line = '';

	$f = fopen($lastRunLog, 'r');
	$cursor = -1;

	
 	//Trim trailing newline chars of the file
 	
	while ($char === "\n" || $char === "\r") 
	{
    	fseek($f, $cursor--, SEEK_END);
    	$char = fgetc($f);
	}

		 // Read until the start of file or first newline char

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