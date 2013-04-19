<?php
header("Access-Control-Allow-Origin:*");

$profileinfo = $_POST['profileInfo'];
 

$profileinfo = stripslashes($profileinfo);


$userid = stripslashes($_POST['userId']);

$interactiondata = $_POST['interactionData'];

$interactiondata = stripslashes($interactiondata);
$jsoninteractionData = json_decode($interactiondata,true);

//echo $userid;
updateIndexFile($profileinfo, $userid, $interactiondata);


//function for writing index file
function updateIndexFile($profileinfo, $userid, $interactiondata)
{
		
	$json_a=array();
	$indexFile = "../../jsonfiles/index.json";

				 
	//get content of the index file
	if(file_exists($indexFile))
	
		$indexFileContent = file_get_contents($indexFile);

		$json_a = json_decode($indexFileContent, true);
		
		//identifying number of entry in index file
		$indexLength = count($json_a['index']);
		echo $indexLength;

	if($indexLength != null) //if index file exists
	{
		
	   for($count = 0; $count < $indexLength; $count++)
	   {
		   	
			//echo $json_a['index'][$count]['asguser'].$userid;  
		   if ($json_a['index'][$count]['data']['socialIDs']['facebookId']==$userid) 
		   {
				  
		  	 	  $userDir = $json_a['index'][$count]['data']['socialDataPaths']['facebook'].'/';
				  
				  
				  $profileFile = $userDir.$userid.".FBProfile.json";
				 
					//call function to write interaction file
				  $interactionFilePath = $userDir.$userid.".FBInteraction.txt";
				  
				  $logFile = $userDir."lastrun.log";
				  
				  update_log_file($logFile);
				  
				  interact($interactionFilePath, $interactiondata);
					
					//write profile info to the profile info file
					$HANDLE=fopen($profileFile, "w") or die('cannot open file');
					if($HANDLE!=null)
						fwrite($HANDLE, $profileinfo);

					fclose($HANDLE);
					break;		
			}
	   }			
	 }

}


//function for interaction data writing
function interact($interactionFilePath, $interactionInfo)
{

// set the default timezone to use. 
date_default_timezone_set('UTC');
$updatetime=date(DATE_RFC822);

$jsonstr_a = json_decode($interactionInfo,true);

//converting json object to php two dimensional array
$l=0;
foreach($jsonstr_a[friends] as $f)
{
	$friendInfo[$l]['email']=$f[email];
	$friendInfo[$l]['fromFrequency']=$f[fromFrequency];
	$friendInfo[$l]['toFrequency']=$f[toFrequency];
	$friendInfo[$l]['contextKey']=$f[contextKey];		
		 	
	$l++;	
}	
						
$updatetime=time();
writetofile($friendInfo, $interactionFilePath, $updatetime);
}

//function for writting file
function writetofile($friendInfo, $interactionFilePath, $updatetime)
{
	
	ob_start();
	print_r( 'Update Time: '.$updatetime);
	echo "\n";
	//print_r( $combindConnection[$j]['connection']);
	for($j=0;$j<sizeof($friendInfo);$j++)
	{
		if($j!=sizeof($friendInfo)-1)
		{
			print_r( $friendInfo[$j]['email']);
			print_r ("\t\t");
			print_r( $friendInfo[$j]['fromFrequency']);
			print_r ("\t\t");
			print_r ($friendInfo[$j]['toFrequency']);
			print_r ("\n");
		}
		else if($j==sizeof($friendInfo)-1)
		{
			print_r( $friendInfo[$j]['email']);
			print_r ("\t\t");
			print_r( $friendInfo[$j]['fromFrequency']);
			print_r ("\t\t");
			print_r ($friendInfo[$j]['toFrequency']);
		}
	}	
	$output = ob_get_clean();
	file_put_contents( $interactionFilePath, $output );
}

//function for updating log
function update_log_file($logfile)
{
	
	if (file_exists($logfile))
	{
		ob_start();
		print_r ("\n");
		print_r (time());
		$output = ob_get_clean();
		//fwrite($file, $output);
		file_put_contents($logfile, $output, FILE_APPEND );
	}	
	else 
	{
		 $file = fopen($logfile, "a");
		 fputs($file, time());
		 fclose($file);
	}
}
?>