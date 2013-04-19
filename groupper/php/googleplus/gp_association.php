<?php
header("Access-Control-Allow-Origin:*");

$googlePlusUserId = $_POST['googlePlusUserId'];
$userID = $_POST['userId'];

$indexFilePath = "../../jsonfiles/index.json";
$googlePlusDirectory = "../../jsonfiles/googleplus/";

if(!is_dir($googlePlusDirectory))
  mkdir($googlePlusDirectory, 0777);

$googlePlusUserDirectory = $googlePlusDirectory.$googlePlusUserId;

if(!is_dir($googlePlusUserDirectory))
   mkdir($googlePlusUserDirectory, 0777);


// isIndex File created?
if(file_exists($indexFilePath)){

// search userID
	searchUser($userID, $indexFilePath, $googlePlusUserId, $googlePlusUserDirectory);
	
}else{
	
	echo 'Index File is missing!!!!';
}

//if index file exists call this function
function searchUser($userID, $indexFilePath, $googlePlusUserId, $googlePlusUserDirectory)
{
	$indexFileContentJson=array();
	
	$indexFileContent = file_get_contents($indexFilePath);
	$indexFileContentJson = json_decode($indexFileContent, true);
	$indexLength = count($indexFileContentJson['index']);
	for($counter=0;$counter<$indexLength;$counter++){
			if($indexFileContentJson["index"][$counter]["asguser"] == $userID)
			{
				$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["googlePlusId"] = $googlePlusUserId;			
				$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["googleplus"] = $googlePlusUserDirectory;
				writetoindex($indexFileContentJson, $indexFilePath);
				break;
			}			
	}	
}

function writetoindex($indexFileContentJson, $indexFilePath)
{
	$indexFileContentJson = json_encode($indexFileContentJson);
	//remove additionall backslashes & fron and end qoutes 
	$indexFileContentJson = trim(stripslashes($indexFileContentJson), '"');
	$indexfile=fopen($indexFilePath, "w") or die('cannot open file');
	
	fwrite($indexfile, $indexFileContentJson);
	fclose($indexfile);
	echo 'You are authorized!!!';
}

?>