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
					$dataDirectory = $json_a['index'][$j]['data']['socialDataPaths']['googleplus'];
					$dataSourceID = $json_a['index'][$j]['data']['socialIDs']['googlePlusId'];
					
					showGoogleFile($dataDirectory,$dataSourceID);
		  		}
		}
}

//function for delete file
function showGoogleFile($dataDirectory,$dataSourceID)
{
	$dh  = opendir($dataDirectory);
	$pathvalidity=0;
	while (false !== ($filename = readdir($dh))) {
	    $files[] = $filename;
		if ($filename != "." && $filename != ".."){
			$fileExt=end(explode(".", $filename)); 
							
					if($fileExt==="txt" ) //checks with extension for text files only
					{
						$path = "jsonfiles/googlePlus/".$dataSourceID."/".$filename;
						$pathvalidity=1;
						echo $path;
						break;
					}	  
		}	
	}
	if ($pathvalidity==0) {
		echo "0";		
	}
	
}
?>