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
					deletefile($dataDirectory);
		  		}
		}
} else {
	echo 'Please Authorize Yourself and Generate Data File!!!';
}


//function for delete file
function deletefile($dataDirectory)
{
	$dh  = opendir($dataDirectory);
	$filefound=0;
	while (false !== ($filename = readdir($dh))) {
	    $files[] = $filename;
		if ($filename != "." && $filename != ".."){
			$path = realpath($dataDirectory."/".$filename);
			unlink($path);
			$filefound=1;
			  
		}	
	}
	if ($filefound==0) {
		echo "Please Generate Data File!!!";		
	}
	else {
		echo "Your Data Is deleted Successfully!!!";
	}
}


?>