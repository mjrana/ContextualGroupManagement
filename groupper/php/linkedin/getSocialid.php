<?php

header("Access-Control-Allow-Origin:*");
ini_set('memory_limit','1024M');

$rsocialId = $_POST['datasource'];

$userId = $_POST['user'];
//echo $userId;

$indexFileContent = file_get_contents("../../jsonfiles/index.json");

		$json_a = json_decode($indexFileContent, true);
		
		$indexContentLength = count($json_a['index']);
		
		for($j=0; $j<$indexContentLength; $j++)
		
		{
		
		   		if($json_a['index'][$j]['asguser'] == $userId)	
		   		{
					//echo $userId;
					$rsocialId = $json_a['index'][$j]['data']['socialIDs'][$rsocialId];
					
					break;
				}
				
		}
		echo $rsocialId;

?>