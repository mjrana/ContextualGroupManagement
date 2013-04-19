<?php
header("Access-Control-Allow-Origin:*");

$userID = $_POST['userId'];

$indexFilePath = "../../jsonfiles/index.json";
$googleplusDirectory = "../../jsonfiles/googleplus/";

//if(!is_dir($linkedinDirectory))
if (!file_exists($googleplusDirectory)) 
     mkdir($googleplusDirectory,0776);

$userGooglePlusDirectory = $googleplusDirectory.$userID;

//if(!is_dir($userLinkedinDirectory))
if (!file_exists($userGooglePlusDirectory)) 
     mkdir($userGooglePlusDirectory,0776);

//group directory

$groupDirectory = $userGooglePlusDirectory.'/mygroups';
if (!file_exists($groupDirectory)) 
     mkdir($groupDirectory,0776);

 
// isIndex File created?
if(file_exists($indexFilePath)){

// search userID
	searchUser($userID, $indexFilePath, $userGooglePlusDirectory);
	
}else{
	
	CreateUserIndex($userID, $indexFilePath, $userGooglePlusDirectory);
}

function searchUser($userID, $indexFilePath, $userGooglePlusDirectory)
{
	$indexFileContentJson=array();
	
	$indexFileContent = file_get_contents($indexFilePath);
	$indexFileContentJson = json_decode($indexFileContent, true);

	$indexLength = count($indexFileContentJson['index']);
	
	for($counter=0;$counter<$indexLength;$counter++){
		if($indexFileContentJson["index"][$counter]["asguser"] == $userID)
		{
			break;
		}		
	}
	
	
	if($counter == $indexLength)
	  	{
			$indexFileContentJson["index"][$counter]["asguser"] = $userID;
			$indexFileContentJson["index"][$counter]['data']["username"] = $userID;
			$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["googlePlusId"] = $userID;
			$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["googleplus"] = $userGooglePlusDirectory;	
		}
	
	$indexFileContentJson=json_encode($indexFileContentJson);
	//remove additionall backslashes & fron and end qoutes 
	$indexFileContentJson = trim(stripslashes($indexFileContentJson), '"');
	$indexfile=fopen($indexFilePath, "w") or die('cannot open file');
	
	fwrite($indexfile, $indexFileContentJson);
	fclose($indexfile);
	echo 'successful';
}

//if index file does not exist call this function
function CreateUserIndex($userID, $indexFilePath, $userGooglePlusDirectory)
{
	    $counter = 0;
		$indexFileContentJson = array();
		$indexFileContentJson["index"][$counter]["asguser"] = $userID;
		$indexFileContentJson["index"][$counter]['data']["username"] = $userID;
		$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["googlePlusId"] = $userID;
		$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["googleplus"] = $userGooglePlusDirectory;	
  
	$indexFileContentJson = json_encode($indexFileContentJson);
	
	//remove additionall backslashes & fron and end qoutes 
	$indexFileContentJson = trim(stripslashes($indexFileContentJson), '"');
	
	$indexfile = fopen($indexFilePath, "w") or die('cannot open file');
	
	fwrite($indexfile, $indexFileContentJson);
	fclose($indexfile);
	echo 'successful';
}



?>