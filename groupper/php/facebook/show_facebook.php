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
					$dataSourceID = $json_a['index'][$j]['data']['socialIDs']['facebookId'];
					showfile($dataDirectory,$dataSourceID);
		  		}
		}
}

//function for delete file
function showfile($dataDirectory,$dataSourceID)
{
	$dh  = opendir($dataDirectory);
	$path = "nothing";
	while (false !== ($filename = readdir($dh))) {
	    $files[] = $filename;
		if ($filename != "." && $filename != ".."){
			$fileExt=end(explode(".", $filename)); 
							
					if($fileExt==="json" ) //checks with extension for text files only
					{
						$path = "jsonfiles/facebook/".$dataSourceID."/".$filename;
						//echo $path;
					}	  
		}	
	}
	if($path =="nothing")
		echo "noFile";
	else {
		echo $path;
	}
}
?>