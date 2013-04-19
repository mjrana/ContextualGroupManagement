<?php
header("Access-Control-Allow-Origin:*");


$userID = $_POST['userId'];
$linkedinDirectory = $userID;
$indexFilePath = "../../jsonfiles/index.json";
$linkedinDirectory = "../../jsonfiles/linkedin/";

//if(!is_dir($linkedinDirectory))
if (!file_exists($linkedinDirectory)) 
     mkdir($linkedinDirectory,0776);

$userLinkedinDirectory = $linkedinDirectory.$userID;

//if(!is_dir($userLinkedinDirectory))
if (!file_exists($userLinkedinDirectory)) 
     mkdir($userLinkedinDirectory,0776);

//group directory

$groupDirectory = $userLinkedinDirectory.'/mygroups';
if (!file_exists($groupDirectory)) 
     mkdir($groupDirectory,0776);
 
// isIndex File created?
if(file_exists($indexFilePath)){

// search userID
	searchUser($userID, $indexFilePath, $userLinkedinDirectory);
	
}else{
	
	CreateUserIndex($userID, $indexFilePath, $userLinkedinDirectory);
}

//if index file exists call this function
function searchUser($userID, $indexFilePath, $userLinkedinDirectory)
{
	$indexFileContentJson=array();
	
	$indexFileContent = file_get_contents($indexFilePath);
	$indexFileContentJson = json_decode($indexFileContent, true);

	$indexLength = count($indexFileContentJson['index']);
	
	for($counter=0;$counter<$indexLength;$counter++){
		if($indexFileContentJson["index"][$counter]["asguser"] == $userID)
		{
			break;
			//$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["linkedinId"] = $userID;
			
			//$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["linkedin"] = $userLinkedinDirectory;
		}		
	}
	
	
	if($counter == $indexLength)
	  	{
			//$counter = $indexLength;
			echo $counter;
			$indexFileContentJson["index"][$counter]["asguser"] = $userID;
			$indexFileContentJson["index"][$counter]['data']["username"] = $userID;
			$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["linkedinId"] = $userID;
			$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["linkedin"] = $userLinkedinDirectory;	
		}
	
	$indexFileContentJson=json_encode($indexFileContentJson);
	//remove additionall backslashes & fron and end qoutes 
	$indexFileContentJson = trim(stripslashes($indexFileContentJson), '"');
	$indexfile=fopen($indexFilePath, "w") or die('cannot open file');
	
	fwrite($indexfile, $indexFileContentJson);
	fclose($indexfile);
	echo 'you are authrized as LinkedIn User';
}

//if index file does not exist call this function
function CreateUserIndex($userID, $indexFilePath, $userLinkedinDirectory)
{
	    $counter = 0;
		$indexFileContentJson = array();
		$indexFileContentJson["index"][$counter]["asguser"] = $userID;
		$indexFileContentJson["index"][$counter]['data']["username"] = $userID;
		$indexFileContentJson["index"][$counter]["data"]["socialIDs"]["linkedinId"] = $userID;
		$indexFileContentJson["index"][$counter]["data"]["socialDataPaths"]["linkedin"] = $userLinkedinDirectory;	
  
	$indexFileContentJson = json_encode($indexFileContentJson);
	
	//remove additionall backslashes & fron and end qoutes 
	$indexFileContentJson = trim(stripslashes($indexFileContentJson), '"');
	
	$indexfile = fopen($indexFilePath, "w") or die('cannot open file');
	
	fwrite($indexfile, $indexFileContentJson);
	fclose($indexfile);
	echo 'you are authrized as LinkedIn User';
}

?>