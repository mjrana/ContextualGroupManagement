<?php
header("Access-Control-Allow-Origin:*");

$profileinfo = $_POST['profileInfo'];

$profileinfo = stripslashes($profileinfo);


$userid = stripslashes($_POST['userId']);

$interactiondata = $_POST['context_data'];

$interactiondata = stripslashes($interactiondata);
$jsoninteractionData = json_decode($interactiondata,true);

//echo $userid;
updateIndexFile($profileinfo, $userid, $jsoninteractionData);


//function for writing index file
function updateIndexFile($profileinfo, $userid, $jsoninteractionData)
{
		
	$json_a=array();
	$indexFile = "../../jsonfiles/index.json";
	
	//get content of the index file
	if(file_exists($indexFile))
	
		$indexFileContent = file_get_contents($indexFile);

		$json_a = json_decode($indexFileContent, true);
		
		//identifying number of entry in index file
		$indexLength = count($json_a['index']);
	//echo $indexLength;
	
	if($indexLength != null) //if index file exists
	{
		//echo $json_a['index'][$count]['asguser'];
	   for($count = 0; $count < $indexLength; $count++)
	   {
		   	
			//echo $json_a['index'][$count]['asguser'].$userid;  
		   if ($json_a['index'][$count]['data']['socialIDs']['linkedinId']==$userid) 
		   {
				  
		  	 	  $userDir = $json_a['index'][$count]['data']['socialDataPaths']['linkedin'].'/';
				  
				  
				  $profileFile = $userDir.$userid.".LNProfile.json";
				 // echo $userDir;
				 
					//call function to write interaction file
				  $interactionFile = $userDir.$userid.".LNInteraction.txt";
				  
				  $logFile = $userDir."lastrun.log";
				  
				  update_log_file($logFile);
				  
				  interact($interactionFile, $jsoninteractionData);
					
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
function interact($filename, $jsonstr_a)
{

// set the default timezone to use. 
date_default_timezone_set('UTC');
$updatetime=date(DATE_RFC822);

//converting json object to php two dimensional array
$l=0;
foreach($jsonstr_a[friends] as $f)
{
	
	$friendInfo[$l]['email']=$f[email];
	$friendInfo[$l]['fromFrequency']=$f[fromFrequency];
	$friendInfo[$l]['toFrequency']=$f[toFrequency];
	
	$l++;	
	
}	
						
// set the default timezone to use. 
//date_default_timezone_set('UTC');
//$updatetime=date(DATE_RFC822);
$updatetime=time();

writetofile($friendInfo, $filename, $updatetime);


}

//function for writting file
function writetofile($addr, $filename, $updatetime)
{
	//$NAME= $username;
	ob_start();
	print_r( 'Update Time: '.$updatetime);
	echo "\n";
	//print_r( $combindConnection[$j]['connection']);
	for($j=0;$j<sizeof($addr);$j++)
	{
		if($j!=sizeof($addr)-1)
		{
			print_r( $addr[$j]['email']);
			print_r ("\t\t");
			print_r( $addr[$j]['fromFrequency']);
			print_r ("\t\t");
			print_r ($addr[$j]['toFrequency']);
			print_r ("\t\t");
			print_r ( $addr[$j]['contextKey']);
			print_r ("\n");
		}
		else 
		{
			print_r( $addr[$j]['email']);
			print_r ("\t\t");
			print_r( $addr[$j]['fromFrequency']);
			print_r ("\t\t");
			print_r ($addr[$j]['toFrequency']);
			print_r ("\t\t");
			print_r ( $addr[$j]['contextKey']);
		}
	}
	$output = ob_get_clean();
	file_put_contents( $filename,$output );
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