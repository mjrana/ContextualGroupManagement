<?php
header("Access-Control-Allow-Origin:*");


$userID = $_POST['userId'];
//$facebookDirectory = $userID;
$indexFilePath = "../../jsonfiles/index.json";
$facebookDirectory = "../../jsonfiles/facebook/";

//if(!is_dir($facebookDirectory))
if (!file_exists($facebookDirectory)) 
     mkdir($facebookDirectory,0776);

$userfacebookDirectory = $facebookDirectory.$userID;

//if(!is_dir($userfacebookDirectory))
if (!file_exists($userfacebookDirectory)) 
     mkdir($userfacebookDirectory,0776);

//group directory

$groupDirectory = $userfacebookDirectory.'/mygroups';
if (!file_exists($groupDirectory)) 
     mkdir($groupDirectory,0776);
 
// isIndex File created?
if(file_exists($indexFilePath)){

// search userID
	searchUser($userID, $indexFilePath, $userfacebookDirectory);
	
}else{
	
	CreateUserIndex($userID, $indexFilePath, $userfacebookDirectory);
}

//if index file exists call this function
function searchUser($userID, $indexFilePath, $userfacebookDirectory)
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
			//$counter = $indexLength;
			echo $counter;
			$indexFileContentJson["index"][$counter]["asguser"] = $userID;
			$indexFileContentJson["index"][$counter]['data']["username"] = $userID;
			$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["facebookId"] = $userID;
			$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["facebook"] = $userfacebookDirectory;	
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
function CreateUserIndex($userID, $indexFilePath, $userfacebookDirectory)
{
	    $counter = 0;
		$indexFileContentJson = array();
		$indexFileContentJson["index"][$counter]["asguser"] = $userID;
		$indexFileContentJson["index"][$counter]['data']["username"] = $userID;
		$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["facebookId"] = $userID;
		$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["facebook"] = $userfacebookDirectory;	
  
	$indexFileContentJson = json_encode($indexFileContentJson);
	
	//remove additionall backslashes & fron and end qoutes 
	$indexFileContentJson = trim(stripslashes($indexFileContentJson), '"');
	
	$indexfile = fopen($indexFilePath, "w") or die('cannot open file');
	
	fwrite($indexfile, $indexFileContentJson);
	fclose($indexfile);
	echo 'successful';
}

?>