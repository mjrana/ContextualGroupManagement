<?php
header("Access-Control-Allow-Origin:*");

$facebookUserId = $_POST['facebookUserId'];
$userID = $_POST['userId'];

$indexFilePath = "../../jsonfiles/index.json";
$facebookDirectory = "../../jsonfiles/facebook/";

if(!is_dir($facebookDirectory))
  mkdir($facebookDirectory, 0777);

$facebookUserDirectory = $facebookDirectory.$facebookUserId.'/';

if(!is_dir($facebookUserDirectory))
   mkdir($facebookUserDirectory, 0777);


// isIndex File created?
if(file_exists($indexFilePath)){

// search userID
	searchUser($userID, $indexFilePath, $facebookUserId, $facebookUserDirectory);
	
}else{
	
	echo 'Index File is missing!!!!';
}

//if index file exists call this function
function searchUser($userID, $indexFilePath, $facebookUserId, $facebookUserDirectory)
{
	$indexFileContentJson=array();
	
	$indexFileContent = file_get_contents($indexFilePath);
	$indexFileContentJson = json_decode($indexFileContent, true);

	$indexLength = count($indexFileContentJson['index']);
	for($counter=0;$counter<$indexLength;$counter++){
			if($indexFileContentJson["index"][$counter]["asguser"] == $userID)
			{
				$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["facebookId"] = $facebookUserId;
			
				$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["facebook"] = $facebookUserDirectory;
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